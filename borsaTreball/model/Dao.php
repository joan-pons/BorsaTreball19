<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Borsa;

use Borsa\Ajuda as Ajuda;
use Borsa\Familia as Familia;
use Borsa\Token as Token;
use Borsa\Usuari as Usuari;
use Correu\Bustia as Bustia;
use Borsa\Email as Email;
use Illuminate\Database\Capsule\Manager as DB;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Description of Dao
 *
 * @author joan
 */
class Dao
{

    public static function entrada(Request $request, Response $response, $args, \Slim\Container $container)
    {
        $container->dbEloquent;
        $data = $request->getParsedBody();
        $tipus = filter_var($data['tipus'], FILTER_SANITIZE_NUMBER_INT);
        $usuari = Usuari::where('nomUsuari', filter_var($data['nomUsuari'], FILTER_SANITIZE_EMAIL))->where('tipusUsuari', $tipus)->first();

        /*Si és el login d'una empresa i no l'ha trobat comprova que no sigui un professor intentant publicar una oferta en nom d'una empresa*/
        if ($usuari == null && $tipus == 20) {
            $usuari = Usuari::where('nomUsuari', filter_var($data['nomUsuari'], FILTER_SANITIZE_EMAIL))->where('tipusUsuari', 10)->first();

        }
        if ($usuari == null || !password_verify(filter_var($data['password'], FILTER_SANITIZE_STRING), $usuari->contrasenya)) {
            $missatge = array("missatge" => "L'usuari i/o la contrasenya estan equivocats.");
            return $response->withJson($missatge, 401);
        } else {
            session_unset();
            session_destroy();
            if ($usuari->getEntitat()->validat == 1 || $usuari->getEntitat()->validada == 1) {
                // Si es tracta d'un professor entrant com a empresa
                // Ha de canviar l'usuari del professor per el de l'empresa Pau Casesnoves
                if ($tipus == 20 && $usuari->tipusUsuari == 10) {
                    $usuari = Usuari::where('tipusUsuari', 20)->where('nomUsuari', 'borsa.treball@paucasesnovescifp.cat')->first();
                }
                session_start();
                $_SESSION['idUsuari'] = $usuari->idUsuari;
                $rols = [];
                foreach ($usuari->rols as $rol) {
                    $rols[] = $rol->idRol;
                }
                $_SESSION['rols'] = $rols;
                return $response->withJSON(array("missatge" => "L'usuari s'ha validat correctament"));
            } else {
                $missatge = array("missatge" => "L'usuari no està validat.");
                return $response->withJson($missatge, 401);
            }
        }
    }

    public static function entradaToken(Request $request, Response $response, $args, \Slim\Container $container, $desti)
    {
        $container->dbEloquent;
        $t = filter_var($request->getQueryParam('t'), FILTER_SANITIZE_STRING);
        $token = Token::find($t);
        $ara = strtotime('now');
        if ($token != null && $ara <= strtotime($token->data)) {
            $usuari = Usuari::find($token->idUsuari);
            $token->destroy($token->token);

            session_unset();
            session_destroy();

            session_start();
            $_SESSION['idUsuari'] = $usuari->idUsuari;
            $rols = [];
            foreach ($usuari->rols as $rol) {
                $rols[] = $rol->idRol;
            }
            $_SESSION['rols'] = $rols;
            return $response->withHeader('Location', $desti)->withStatus(302);
        } else {
            return $container->view->render($response, 'auxiliars/tokenNoValid.html.twig', ['missatge' => $t . " " . $ara . " " . ($ara <= strtotime($token->data))]);
        }
    }

    public static function generaToken($longitud, Usuari $usuari, $durada, \Slim\Container $container)
    {
        $container->dbEloquent;
        $token = bin2hex(random_bytes(($longitud - ($longitud % 2)) / 2));
        $r = new Token();
        $r->idUsuari = $usuari->idUsuari;
        $r->token = $token;
        $r->data = date('Y-m-d H:i:s', strtotime('+' . $durada . ' day'));
        $r->save();
        return $r;
    }

    public static function contrasenyaOblidada(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $tipus = filter_var($data['tipus'], FILTER_SANITIZE_NUMBER_INT);
            $usuari = Usuari::where('nomUsuari', filter_var($data['nomUsuari'], FILTER_SANITIZE_EMAIL))->where('tipusUsuari', $tipus)->get();
//            $resposta=array('usuari'=>$usuari, 'data'=>$data);
//            return $response->withJSON($resposta);
            if (count($usuari) > 0) {
                $usuari = $usuari[0];

                $r = Dao::generaToken(20, $usuari, 1, $container);
                $resultat = Bustia::enviarUnic($usuari->nomUsuari, 'Restablir la contrasenya de la borsa de treball del CIFP Pau Casesnoves', "/email/restablirContrasenya.twig", ['token' => $r->token], $container);
                if ($resultat == true) {
                    $missatge = array("missatge" => "Procés correcte.");
                    // $missatge[] = $r;
                    return $response->withJSON($missatge); //array('professor' => $professor, 'validat' => $validat, 'Activat' => $activat, 'Primera' => $validat and !$professor->validat, 'Segona' => !$validat, 'Resultat' => $resultat));
                } else {
                    $missatge = array("missatge" => "<p>No s'ha pogut enviar el missatge de confirmació. L'adreça de correu deu estar malament.</p> <p>Els canvis no s'han guardat a la base de dades.</p>");
                    return $response->withJSON($usuari, 422);
                }
            } else {
                if ($tipus == 10) {
                    $tipus = "professor";
                } elseif ($tipus == 20) {
                    $tipus = "empresa";
                } elseif ($tipus == 10) {
                    $tipus = "alumne";
                }
                $missatge = array("missatge" => "No es troba cap " . $tipus . " amb l'identificador demanat.");
                return $response->withJson($missatge, 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que el correu electrònic ja està registrat per un altre contacte.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "El professor no s'ha pogut modificar.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function canviarContrasenya(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            // $usuari = Usuari::where('nomUsuari', $args['nomUsuari'])->first();
            $usuari = Usuari::find(filter_var($args['idusuari'], FILTER_SANITIZE_NUMBER_INT));
            if ($usuari != null) {
                if (password_verify(filter_var($data['antic'], FILTER_SANITIZE_STRING), $usuari->contrasenya)) {
                    $usuari->contrasenya = password_hash(filter_var($data['nou'], FILTER_SANITIZE_STRING), PASSWORD_DEFAULT);
                    $usuari->save();
                    return $response->withStatus(200);
                } else {
                    return $response->withJson(array("missatge" => "La contrasenya antiga no és correcta."), 422);
                }
            } else {
                return $response->withJson(array("missatge" => "No es troba cap usuari amb el nom d'usuari donat."), 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            switch ($ex->getCode()) {
                case 23000:
                {
                    $missatge = array("missatge" => "La contrasenya no pot ser nula");
                    break;
                }
                default:
                {
                    $missatge = array("missatge" => "La contrasenya no s'ha pogut canviar correctament.");
                    break;
                }
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function restablirContrasenya(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            // $token = Usuari::where('nomUsuari', $args['nomUsuari'])->first();
            $token = Token::find(filter_var($data['token'], FILTER_SANITIZE_STRING));
            $usuari = Usuari::find(filter_var($data['idUsuari'], FILTER_SANITIZE_NUMBER_INT));
            $ara = strtotime('now');
            if ($token != null && $usuari != null && $ara <= strtotime($token->data)) {
                $usuari->contrasenya = password_hash(filter_var($data['nou'], FILTER_SANITIZE_STRING), PASSWORD_DEFAULT);
                $usuari->save();
                $token->destroy($token->token);
                return $response->withStatus(200);
            } else {
                return $response->withJson(array("missatge" => "El token no és vàlid."), 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            switch ($ex->getCode()) {
                case 23000:
                {
                    $missatge = array("missatge" => "La contrasenya no pot ser nula");
                    break;
                }
                default:
                {
                    $missatge = array("missatge" => "La contrasenya no s'ha pogut canviar correctament.");
                    break;
                }
            }
            return $response->withJson($missatge, 422);
        }
    }


    public static function alumnesOfertaComplets($oferta, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            //Allumnes per estudis, nota i any
            $alumnes = DB::select('select distinct a.* from Ofertes_has_Estudis o inner join Alumne_has_Estudis e on o.Estudis_codi=e.Estudis_codi inner join Alumnes a on e.Alumnes_idAlumne=a.idAlumne where Ofertes_idOferta=' . $oferta->idOferta . ' and a.actiu=true and e.any>=IFNULL(o.any,1900) and e.nota>=IFNULL(o.nota,5)  order by a.llinatges, a.nom');

            //Alumnes per Idiomes
            if ($oferta->idiomes->count() > 0 && count($alumnes) > 0) {
                $alumnesIdiomes = array();
                //Dins $alumnesIdiomes guarda els que hi ha a $alumnes que tenen els idiomes demanats
                foreach ($alumnes as $alumne) {
                    $coincidencies = DB::select('select count(o.idiomes_idIdioma) as recompte from Ofertes_has_Idiomes o left join Alumne_has_Idiomes al  on o.idiomes_idIdioma=al.idiomes_idIdiomes where o.Ofertes_idOferta=' . $oferta->idOferta . ' and al.Alumne_idAlumne=' . $alumne->idAlumne . ' and al.NIvellsIdioma_idNivellIdioma>=o.NivellsIdioma_idNivellIdioma');
                    if ($coincidencies[0]->recompte == $oferta->idiomes->count()) {
                        array_push($alumnesIdiomes, $alumne);
                    }
                }
                //Actualitza $alumnes amb els filtrats per idioma.
                $alumnes = $alumnesIdiomes;
            }

            // Filtrar alumnes per estat laboral
            if ($oferta->estatsLaborals->count() > 0 && count($alumnes) > 0) {
                $alumnesEstats = '(';
                $separador = '';
                foreach ($alumnes as $alumne) {
                    $alumnesEstats .= $separador . $alumne->idAlumne;
                    $separador = ', ';
                }
                $alumnesEstats .= ')';

                $alumnes = DB::select('select distinct a.* from Ofertes_has_EstatLaboral o inner join Alumne_has_EstatLaboral al on o.EstatLaboral_idEstatLaboral=al.EstatLaboral_idEstatLaboral inner join Alumnes a on idAlumne=Alumnes_idAlumne where o.Ofertes_idOferta=' . $oferta->idOferta . ' and Alumnes_idAlumne in ' . $alumnesEstats);
            }
            return $alumnes;
        } catch (\Illuminate\Database\QueryException $ex) {
            switch ($ex->getCode()) {
                case 23000:
                {
                    $missatge = array("missatge" => "Problemes filtrant els alumnes.");
                    break;
                }
                default:
                {
                    $missatge = array("missatge" => "Problemes filtrant els alumnes. ");
                    break;
                }
            }
            return null; //$response->withJson($missatge, 422);
        }
    }

    public static function alumnesOferta($oferta, \Slim\Container $container)
    {
        $alumnes = Dao::alumnesOfertaComplets($oferta, $container);
//        Tornar només els idAlumne
        $alumnesDefinitiu = array();
        foreach ($alumnes as $alumne) {
            array_push($alumnesDefinitiu, $alumne->idAlumne);
        }
        return $alumnesDefinitiu;
    }

    public static function comptarCandidats($oferta, \Slim\Container $container)
    {
        return count(Dao::alumnesOfertaComplets($oferta, $container));
    }

    public static function ajuda(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $ajuda = Ajuda::find($args['idAjuda']);
            if ($ajuda != null) {

                return $response->withJson(array("missatge" => $ajuda->missatge), 200);

            } else {
                return $response->withJson(array("missatge" => "No es troba l'ajuda demanada."), 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {

            return $response->withJson(array("missatge" => "No es troba l'ajuda demanada."), 422);
        }
    }

    public static function ciclesFamilia(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $familia = Familia::find($args['idFamilia']);
            if ($familia != null) {
                return $response->withJson($familia->cicles, 200);

            } else {
                return $response->withJson(array("missatge" => "No es troba la familia demanada."), 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {

            return $response->withJson(array("missatge" => "No es troba la familia demanada."), 422);
        }
    }

    private static function altaMail($destinataris, $text,\Slim\Container $container){
        $container->dbEloquent;
        $email=new Email();
        $email->text=$text;
        $email->save();
    }
}

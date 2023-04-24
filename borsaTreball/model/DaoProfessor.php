<?php

namespace Borsa;

use Borsa\Alumne as Alumne;
use Borsa\Empresa as Empresa;
use Borsa\Professor as Professor;
use Borsa\Estudis as Estudis;
use Borsa\Familia as Familia;
use Borsa\TipusEstudis as TipusEstudis;
use Correu\Bustia as Bustia;
use Illuminate\Database\Capsule\Manager as DB;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


/**
 * Description of DaoProfessor
 *
 * @author joan
 */
class DaoProfessor extends Dao
{
//TODO: Revisar Sanitize i actiu

    public static function altaProfessor(Request $request, Response $response, \Slim\Container $container)
    {

        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $professor = new Professor;
            $professor->nom = filter_var($data['nom'], FILTER_SANITIZE_STRING);
            $professor->llinatges = filter_var($data['llinatges'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $professor->telefon = filter_var($data['telefon'], FILTER_SANITIZE_STRING);
            $professor->email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
            $professor->save();
            $admins = DB::select('SELECT u.idUsuari, u.nomUsuari as email FROM borsa.Usuaris u inner join borsa.Usuaris_has_Rols r on u.idUsuari=r.Usuaris_idUsuari inner join borsa.Professors p on u.idEntitat=p.idProfessor where r.Rols_idRol=40 and p.actiu=1;');

            $longitud = 20;
            $token = bin2hex(random_bytes(($longitud - ($longitud % 2)) / 2));
            $r = new Token();
            $r->idUsuari = $professor->getUsuari()->idUsuari;
            $r->token = $token;
            $r->data = date('Y-m-d H:i:s', strtotime('+1 week'));
            $r->save();

            Bustia::enviar($admins, 'Sol·licitud d\'alta professorat', '/email/validarUsuari.html.twig', ['token' => $token], $container);
            return $response->withJSON($professor);
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que el correu electrònic ja està registrat.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "Les dades del professor no s'han pogut modificar correctament.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function modificarProfessor(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $professor = Professor::find(filter_var($args['idProfessor'], FILTER_SANITIZE_NUMBER_INT));
            if ($professor != null) {
                $professor->nom = filter_var($data['nom'], FILTER_SANITIZE_STRING);
                $professor->llinatges = filter_var($data['llinatges'], FILTER_SANITIZE_STRING);
                $professor->telefon = filter_var($data['telefon'], FILTER_SANITIZE_STRING);
                $professor->email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
                $professor->save();
                $missatge = array("missatge" => "Professor modificat.");
                return $response->withJSON($missatge);
            } else {
                $missatge = array("missatge" => "No s'ha trobat el professor que es vol modificar.");
                return $response->withJson($missatge, 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que el correu electrònic ja està registrat.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "Les dades del professor no s'han pogut modificar correctament.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function afegirEstudis(Request $request, Response $response, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $professor = Professor::find(filter_var($data['idProfessor'], FILTER_SANITIZE_NUMBER_INT));
            if ($professor != null) {
                $codiEstudis = filter_var($data['codiEstudis'], FILTER_SANITIZE_STRING);
                $professor->estudis()->sync([$codiEstudis], false);
                $missatge = array("missatge" => "Estudis afegits.");
                return $response->withJSON($missatge);
            } else {
                $missatge = array("missatge" => "No s'ha trobat el professor que es vol modificar.");
                return $response->withJson($missatge, 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que ja és responsable dels estudis que vol afegir.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "Els estudis no s'han pogut afegir correctament a la seva llista.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function esborrarEstudis(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;

            $professor = Professor::find(filter_var($args['idProfessor'], FILTER_SANITIZE_NUMBER_INT));
            $estudis = filter_var($args['codiEstudis'], FILTER_SANITIZE_STRING);
            if ($professor != null) {
                $professor->estudis()->detach([$estudis]);
                $missatge = array("missatge" => "Estudis eliminats.");
                return $response->withJSON($missatge);
            } else {
                return $response->withJson("No es troba cap contacte amb l'identificador demanat.", 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que el correu electrònic ja està registrat per un altre contacte.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "El contacte no s'ha pogut eliminar, possiblement per tenir ofertes associades.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function activar(Request $request, Response $response, $args, \Slim\Container $container)
    {
        //TODO Canviar validació professors amb missatge per rebuig
        try {
            $container->dbEloquent;

            $professor = Professor::find(filter_var($args['idProfessor'], FILTER_SANITIZE_NUMBER_INT));
            if ($professor != null) {
                $data = $request->getParsedBody();
                $validat = filter_var($data['validat'], FILTER_SANITIZE_STRING) == 'true';
                $activat = filter_var($data['actiu'], FILTER_SANITIZE_STRING) == 'true' && $validat;

                if ($validat and $professor->validat != 1) {
                    $longitud = 20;
                    $token = bin2hex(random_bytes(($longitud - ($longitud % 2)) / 2));
                    $r = new Token();
                    $r->idUsuari = $professor->getUsuari()->idUsuari;
                    $r->token = $token;
                    $r->data = date('Y-m-d H:i:s', strtotime('+1 week'));
                    $r->save();
                    $resultat = Bustia::enviarUnic($professor->email, 'Validació correcta', "/email/instruccionsValidat.html.twig", ['usuari' => $professor->email, 'contrasenya' => $professor->getUsuari()->contrasenya, 'professor' => true, 'token' => $token], $container);
                } else if (!$validat) {
                    $resultat = Bustia::enviarUnic($professor->email, 'Validació incorrecta', "/email/rebutjarProfessor.twig", [], $container);
                } else {
                    $resultat = Bustia::enviarUnic($professor->email, 'Canvi d\'estat Actiu / Inactiu', "/email/activarProfessor.twig", [], $container);
                }
                if ($resultat == true) {
                    if ($validat == false) {
                        $validat = 2;
                    }
                    $professor->validat = $validat;
                    $professor->actiu = $activat;
                    $professor->save();
                    $missatge = array("missatge" => "Professor validat.");
                    return $response->withJSON($missatge); //array('professor' => $professor, 'validat' => $validat, 'Activat' => $activat, 'Primera' => $validat and !$professor->validat, 'Segona' => !$validat, 'Resultat' => $resultat));
                } else {
                    $missatge = array("missatge" => "<p>No s'ha pogut enviar el missatge de confirmació. L'adreça de correu deu estar malament.</p> <p>Els canvis no s'han guardat a la base de dades.</p>");
                    return $response->withJSON($missatge, 422);

                }
            } else {
                return $response->withJson("No es troba cap professor amb l'identificador demanat.", 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
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

    public static function rols(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;

            $professor = Professor::find(filter_var($args['idProfessor'], FILTER_SANITIZE_NUMBER_INT));
            if ($professor != null) {
                $usuari = $professor->getUsuari();
                return $response->withJSON($usuari->rols);
            } else {
                return $response->withJson("No es troba cap professor amb l'identificador demanat.", 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
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

    public static function afegirRol(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $professor = Professor::find(filter_var($args['idProfessor'], FILTER_SANITIZE_NUMBER_INT));
            if ($professor != null) {
                $usuari = $professor->getUsuari();
                $data = $request->getParsedBody();
                $idrol = filter_var($data['idRol'], FILTER_SANITIZE_NUMBER_INT);
                $usuari->rols()->sync([$idrol], false);
                Bustia::enviarUnic($professor->email, 'Rol administrador', "/email/administrador.twig", ['administrador' => true], $container, false);

                return $response->withJSON($professor);
            } else {
                $missatge = array("missatge" => "No s'ha trobat el professor que es vol modificar.");
                return $response->withJson($missatge, 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que ja és administrador", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "Els estudis no s'han pogut afegir correctament a la seva llista.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function eliminarRol(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $professor = Professor::find(filter_var($args['idProfessor'], FILTER_SANITIZE_NUMBER_INT));

            if ($professor != null) {
                $usuari = $professor->getUsuari();
                $data = $request->getParsedBody();
                $idRol = filter_var($data['idRol'], FILTER_SANITIZE_NUMBER_INT);
                $usuari->rols()->detach([$idRol]);
                Bustia::enviarUnic($professor->email, 'Rol administrador', "/email/administrador.twig", ['administrador' => false], $container);

                return $response->withJSON($professor);
            } else {
                $missatge = array("missatge" => "No s'ha trobat el professor que es vol modificar.");
                return $response->withJson($missatge, 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que ja és administrador", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "Els estudis no s'han pogut afegir correctament a la seva llista.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function publicarOferta(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
//            return $response->withJson($data);
            //TODO:Recuperar alumnes filtrats.
            $oferta = Oferta::find(filter_var($args['idOferta'], FILTER_SANITIZE_NUMBER_INT));
            $professor = Professor::find(filter_var($data['idProfessor'], FILTER_SANITIZE_NUMBER_INT));
            if ($professor != null && $oferta != null) {
                $oferta->validada = filter_var($data['validada'], FILTER_SANITIZE_NUMBER_INT);
                $oferta->professorValidada = $professor->idProfessor;

                if (count($data['alumnes']) == 0) {
                    $alumnesDefinitiu = Dao::alumnesOfertaComplets($oferta, $container);

                    //Genera un array només d'ID per al sync
                    $alumnesId = array();
                    foreach ($alumnesDefinitiu as $alumne) {
                        array_push($alumnesId, $alumne->idAlumne);
                    }
                } else {
                    $alumnesId = filter_var_array($data['alumnes'], FILTER_SANITIZE_NUMBER_INT);
                    $alumnesDefinitiu = [];
                    foreach ($alumnesId as $id) {
                        $alumnesDefinitiu[] = Alumne::find($id);
                    }
                }

                if (count($alumnesDefinitiu) > 0) {
                    $oferta->alumnes()->sync($alumnesId);
                    $oferta->save();

                    Bustia::enviar($alumnesDefinitiu, 'Oferta de feina', '/email/oferta.twig', ['oferta' => $oferta], $container);
                    Bustia::enviarUnic($oferta->empresa->email, 'Resultat de la validació de l\'oferta', '/email/ofertaResultat.html.twig', ['oferta' => $oferta], $container);
                    $missatge = array("missatge" => "Oferta validada i emails enviats");
                    return $response->withJSON($missatge);
                } else {
                    $missatge = array("missatge" => "No hi ha cap alumne per aquesta oferta");
                    return $response->withJSON($missatge, 422);
                }
            } else {
                $missatge = array("missatge" => "No s'ha trobat el professor o l'oferta que es vol validar.");
                return $response->withJson($missatge, 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que el correu electrònic ja està registrat.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "L'oferta no s'ha pogut publicar correctament.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }

            return $response->withJson($missatge, 422);
        }
    }

    public static function rebutjarOferta(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $oferta = Oferta::find(filter_var($args['idOferta'], FILTER_SANITIZE_NUMBER_INT));
            $professor = Professor::find(filter_var($data['idProfessor'], FILTER_SANITIZE_NUMBER_INT));
            if ($professor != null && $oferta != null) {
                $oferta->validada = 2;
                $oferta->professorValidada = $professor->idProfessor;
                $oferta->rebuig = filter_var($data['motius'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                $oferta->dataPublicacio = null;
                $oferta->save();

                Bustia::enviarUnic($oferta->empresa->email, 'Resultat de la validació de l\'oferta', '/email/ofertaResultat.html.twig', ['oferta' => $oferta], $container);
                $missatge = array("missatge" => "Oferta enviada i emails enviats");
                return $response->withJSON($missatge);
            } else {
                $missatge = array("missatge" => "No s'ha trobat el professor o l'oferta que es vol validar.");
                return $response->withJson($missatge, 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que el correu electrònic ja està registrat.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "L'oferta no s'ha pogut publicar correctament.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function obrirAlumnes(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $config = Configuracio::find(1);

            if ($config != null) {
                $data = $request->getParsedBody();
                $config->inici = filter_var($data['inici'], FILTER_SANITIZE_STRING);
                $config->final = filter_var($data['final'], FILTER_SANITIZE_STRING);
                $config->save();
                return $response->withJSON("Ok");
            } else {
                $missatge = array("missatge" => "No s'ha trobat la configuració que es vol modificar.");
                return $response->withJson($missatge, 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que ja és administrador", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "Els estudis no s'han pogut afegir correctament a la seva llista.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function reenviarMailProfessorsAltaAlumne(Request $request, Response $response, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;

            $alumnes = Alumne::whereDate('dataAlta', '>', '2022-06-09')->where('validat', '=', 0)->get();

//            $numAlumnes = DB::select('SELECT count(*) FROM borsa.Alumnes where estudisAlta=\'' . $alumne->estudisAlta . '\'  and validat=0 and dataAlta > date_add(NOW(), INTERVAL -7 DAY)');
            if (true) { //TODO $numAlumnes  == 0) {
                foreach ($alumnes as $alumne) {
                    $estudis = Estudis::find($alumne->estudisAlta);
                    if (count($estudis->professors) > 0) {
                        foreach ($estudis->professors as $professor) {
                            $usuari = Usuari::where('nomUsuari', $professor->email)->get();
                            $container->logger->addInfo($alumne->idAlumne . " " . $professor->email);

                            $r = Dao::generaToken(20, $usuari[0], 7, $container);
                            Bustia::enviarUnic($professor->email, "Validació d'alumnes pendent", 'email/validarAlumne.html.twig', ['token' => $r->token], $container);
                        }
//                Bustia::enviar($estudis->professors, "Validació d'alumnes pendent", 'email/validarAlumne.html.twig', [], $container);
                    } else {
                        $professors = Usuari::where('tipusUsuari', 10)->get();
                        $admins = array();
                        foreach ($professors as $p) {
                            if ($p->teRol(40) && $p->getEntitat()->actiu == 1) {
                                $admins[] = $p->getEntitat();

                            }
                        }
                        $cicle = Estudis::find($alumne->estudisAlta);
                        Bustia::enviar($admins, "Validació d'alumnes pendents sense professor responsable", 'email/validarAlumne.html.twig', ['alumne' => $alumne, 'cicle' => $cicle], $container);
                    }
                }
            }
            $missatge = array("missatge" => "Alta correcta");
            return $response->withJson($missatge);
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());

            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que el correu electrònic ja està registrat.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "Les dades de l'alumne no s'han pogut modificar correctament.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }

    }

    public static function reenviarMailProfessorsAltaEmpresa(Request $request, Response $response, \Slim\Container $container)
    {
        //TODO: Filtrar descripció
        try {
            $container->dbEloquent;
            $empreses = Empresa::whereDate('dataAlta', '>', '2022-06-09')->where('validada', '=', 0)->get();
            foreach ($empreses as $empresa) {

                $professors = DB::select('SELECT p.* FROM borsa.Estudis_has_Responsables r inner join borsa.Estudis e on e.codi = r.Estudis_codi inner join borsa.Professors p on r.Professors_idProfessor=p.idProfessor where p.actiu=1 and e.familia=\'' . $empresa->familia . '\'');
                foreach ($professors as $professor) {
                    $container->logger->addInfo($empresa->idEmpresa . ' ' . $professor->idProfessor);

                    if (count($professors) > 0) {
                        $usuari = Usuari::where('nomUsuari', $professor->email)->get();
                        $r = Dao::generaToken(20, $usuari[0], 7, $container);
                        Bustia::enviarUnic($professor->email, 'Validació d\'empresa pendent', '/email/validarEmpresa.html.twig', ['empresa' => $empresa->nom, 'token' => $r->token], $container);

//                Bustia::enviar($professors, 'Validació d\'empresa pendent sense professor assignat', '/email/validarEmpresa.html.twig', ['token'], $container);
                    } else {
                        $professors = Usuari::where('tipusUsuari', 10)->get();
                        $admins = array();
                        foreach ($professors as $p) {
                            if ($p->teRol(40) && $p->getEntitat()->actiu == 1) {
                                $admins[] = $p->getEntitat();
                            }
                        }
                        $familia = Familia::Find($empresa->familia);
                        Bustia::enviar($admins, "Validació d'empresa pendent sense professor assignat.", 'email/validarEmpresaNoProfessor.html.twig', ['empresa' => $empresa, 'familia' => $familia], $container);
                    }
                }
            }
//            $missatge = array("missatge" => 'Alta correcta.', "empresa" => $empresa, "data" => $data);
//            return $response->withJson($missatge);
            return $response->withJson('[2]');
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que el correu electrònic ja està registrat per una altra empresa.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "L'empresa no s'ha pogut donar d'alta correctament.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function insertarEstudis(Request $request, Response $response, \Slim\Container $container)
    {

        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $estudis = new Estudis;
            $estudis->codi = filter_var($data['codi'], FILTER_SANITIZE_STRING);
            $estudis->nom = filter_var($data['nom'], FILTER_SANITIZE_STRING);
            $actiu = filter_var($data['actiu'], FILTER_SANITIZE_STRING);
            $estudis->familia = filter_var($data['familia'], FILTER_SANITIZE_STRING);
            $estudis->tipusEstudis = filter_var($data['tipusEstudis'], FILTER_SANITIZE_STRING);
            if ($actiu == "true") {
                $estudis->actiu = 1;
            } else {
                $estudis->actiu = 0;
            }
            $estudis->save();
            return $response->withJSON($estudis);
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que el correu electrònic ja està registrat.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "Les dades del professor no s'han pogut modificar correctament.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function modificarEstudis(Request $request, Response $response, \Slim\Container $container)
    {

        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $estudis = Estudis::find(filter_var($data['codi'], FILTER_SANITIZE_STRING));
//            $estudis->codi = filter_var($data['codi'], FILTER_SANITIZE_STRING);
            $estudis->nom = filter_var($data['nom'], FILTER_SANITIZE_STRING);
            $actiu = filter_var($data['actiu'], FILTER_SANITIZE_STRING);
            $estudis->familia = filter_var($data['familia'], FILTER_SANITIZE_STRING);
            $estudis->tipusEstudis = filter_var($data['tipusEstudis'], FILTER_SANITIZE_STRING);
            if ($actiu == "true") {
                $estudis->actiu = 1;
            } else {
                $estudis->actiu = 0;
            }
            $estudis->save();
            $missatge = array("missatge" => "Professor modificat.");
            return $response->withJSON($missatge);
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que el correu electrònic ja està registrat.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "Les dades del professor no s'han pogut modificar correctament.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function eliminarEstudis(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $cicle = Estudis::find(filter_var($args['idCicle'], FILTER_SANITIZE_STRING));

            if ($cicle != null) {
                $res = $cicle->delete();
                if ($res) {
                    $missatge = '{"missatge":"El cicle s\'ha eliminat correctament"}';
                } else {
                    $missatge = '{"missatge":"El cicle no s\'ha pogut eliminar"}';
                }
                return $response->withJSON($missatge);
            } else {
                $missatge = array("missatge" => "No s'ha trobat el cicle que es vol eliminar.");
                return $response->withJson($missatge, 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "<p>No es pot eliminar el cicle. Té dades associades: alumnes, ofertes, ... </p><p>Si vol que no aparegui als usuaris modifiqui'l i marqui'l com a no actiu.</p>", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "Els estudis no s'han pogut afegir correctament a la seva llista.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function insertarFamilia(Request $request, Response $response, \Slim\Container $container)
    {

        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $familia = new Familia;
            $familia->id = filter_var($data['id'], FILTER_SANITIZE_STRING);
            $familia->nom = filter_var($data['nom'], FILTER_SANITIZE_STRING);
            $familia->save();
            return $response->withJSON($familia);
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que l'identificador ja està registrat.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "Les dades de la familia no s'han pogut modificar correctament.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function modificarFamilia(Request $request, Response $response, \Slim\Container $container)
    {

        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $familia = Familia::find(filter_var($data['id'], FILTER_SANITIZE_STRING));
            $familia->id = filter_var($data['id'], FILTER_SANITIZE_STRING);
            $familia->nom = filter_var($data['nom'], FILTER_SANITIZE_STRING);
            $familia->save();
            $missatge = array("missatge" => "Familia professional modificada correctament.");
            return $response->withJSON($missatge);
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que l'identificador ja està registrat.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "Les dades de la familia professional no s'han pogut modificar correctament.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }


    public static function eliminarFamilia(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $familia = Familia::find(filter_var($args['idFamilia'], FILTER_SANITIZE_STRING));

            if ($familia != null) {
                $res = $familia->delete();
                if ($res) {
                    $missatge = '{"missatge":"La família professional s\'ha eliminat correctament"}';
                } else {
                    $missatge = '{"missatge":"La família professional no s\'ha pogut eliminar"}';
                }
                return $response->withJSON($missatge);
            } else {
                $missatge = array("missatge" => "No s'ha trobat la família professinal que es vol eliminar.");
                return $response->withJson($missatge, 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "<p>No es pot eliminar la família. Té dades associades: estudis, ofertes, ... </p>", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "Els estudis no s'han pogut afegir correctament a la seva llista.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function insertarTipusEstudis(Request $request, Response $response, \Slim\Container $container)
    {

        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $tipusEstudis = new TipusEstudis;
            $tipusEstudis->idTipus = filter_var($data['id'], FILTER_SANITIZE_STRING);
            $tipusEstudis->nomTipus = filter_var($data['nom'], FILTER_SANITIZE_STRING);
            $tipusEstudis->save();
            return $response->withJSON($tipusEstudis);
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que l'identificador ja està registrat.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "Les dades del tipus d'estudis no s'han pogut modificar correctament.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function modificarTipusEstudis(Request $request, Response $response, \Slim\Container $container)
    {

        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $tipusEstudis = TipusEstudis::find(filter_var($data['idTipus'], FILTER_SANITIZE_STRING));
            $tipusEstudis->nomTipus = filter_var($data['nomTipus'], FILTER_SANITIZE_STRING);
            $tipusEstudis->save();
            $missatge = array("missatge" => "Tipus d'estudis modificada correctament.");
            return $response->withJSON($missatge);
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que l'identificador ja està registrat.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "Les dades del tipus d'estudis no s'han pogut modificar correctament.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }


    public static function eliminarTipusEstudis(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $tipusEstudis = TipusEstudis::find(filter_var($args['idTipus'], FILTER_SANITIZE_STRING));

            if ($tipusEstudis != null) {
                $res = $tipusEstudis->delete();
                if ($res) {
                    $missatge = '{"missatge":"El tipus d\'estudis s\'ha eliminat correctament"}';
                } else {
                    $missatge = '{"missatge":"El tipus d\'estudis no s\'ha pogut eliminar"}';
                }
                return $response->withJSON($missatge);
            } else {
                $missatge = array("missatge" => "No s'ha trobat el tipus d\'estudis  que es vol eliminar.");
                return $response->withJson($missatge, 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "<p>No es pot eliminar el tipus d'estudis. Té dades associades: estudis, ofertes, ... </p>", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "<p>No es pot eliminar el tipus d'estudis. Té dades associades: estudis, ofertes, ... </p>", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }


}

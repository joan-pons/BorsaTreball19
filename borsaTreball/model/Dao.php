<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Borsa;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Borsa\Usuari as Usuari;
use Borsa\Ajuda as Ajuda;
use Borsa\Familia as Familia;

use Illuminate\Database\Capsule\Manager as DB;

/**
 * Description of Dao
 *
 * @author joan
 */
class Dao
{

    public function entrada(Request $request, Response $response, $args, \Slim\Container $container)
    {
        $container->dbEloquent;
        $data = $request->getQueryParams();
        $usuari = Usuari::where('nomUsuari', filter_var($data['nomUsuari'], FILTER_SANITIZE_EMAIL))->first();
        if ($usuari == null || $usuari->contrasenya != filter_var($data['password'],FILTER_SANITIZE_STRING) || $usuari->tipusUsuari != filter_var($data['tipus'],FILTER_SANITIZE_NUMBER_INT)) {
            $missatge = array("missatge" => "L'usuari i/o la contrasenya estan equivocats.");
            return $response->withJson($missatge, 401);
        } else {
            session_unset();
            session_destroy();

            session_start();
            $_SESSION['idUsuari'] = $usuari->idUsuari;
            $rols = [];
            foreach ($usuari->rols as $rol) {
                $rols[] = $rol->idRol;
            }
            $_SESSION['rols'] = $rols;
            return $response->withJSON(array("missatge" => "L'usuari s'ha validat correctament"));
        }
    }

    public function canviarContrasenya(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            // $usuari = Usuari::where('nomUsuari', $args['nomUsuari'])->first();
            $usuari = Usuari::find(filter_var($args['idusuari'],FILTER_SANITIZE_NUMBER_INT));
            if ($usuari != null) {
                if ($usuari->contrasenya == filter_var($data['antic'],FILTER_SANITIZE_STRING)) {
                    $usuari->contrasenya = filter_var($data['nou'],FILTER_SANITIZE_STRING);
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


    public function alumnesOfertaComplets($oferta, \Slim\Container $container)
    {
        $container->dbEloquent;
        //Allumnes per estudis, nota i any
        $alumnes = DB::select('select distinct a.* from Ofertes_has_Estudis o inner join Alumne_has_Estudis e on o.Estudis_codi=e.Estudis_codi inner join Alumnes a on e.Alumnes_idAlumne=a.idAlumne where Ofertes_idOferta=' . $oferta->idOferta . ' and a.actiu=true and e.any>=IFNULL(o.any,1900) and e.nota>=IFNULL(o.nota,5)');

        //Alumnes per Idiomes
        if ($oferta->idiomes->count() > 0 && count($alumnes) > 0) {
            $alumnesIdiomes = array();
            foreach ($alumnes as $alumne) {
                $coincidencies = DB::select('select count(o.idiomes_idIdioma) as recompte from Ofertes_has_Idiomes o left join Alumne_has_Idiomes al  on o.idiomes_idIdioma=al.idiomes_idIdiomes where o.Ofertes_idOferta=' . $oferta->idOferta . ' and al.Alumne_idAlumne=' . $alumne->idAlumne . ' and al.NIvellsIdioma_idNivellIdioma>=o.NivellsIdioma_idNivellIdioma');
                if ($coincidencies[0]->recompte == $oferta->idiomes->count()) {
                    array_push($alumnesIdiomes, $alumne);
                }
            }
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
    }

    public function alumnesOferta($oferta, \Slim\Container $container)
    {
        $alumnes = Dao::alumnesOfertaComplets($oferta, $container);
//        Tornar només els idAlumne
        $alumnesDefinitiu = array();
        foreach ($alumnes as $alumne) {
            array_push($alumnesDefinitiu, $alumne->idAlumne);
        }
        return $alumnesDefinitiu;
    }

    public function comptarCandidats($oferta, \Slim\Container $container)
    {
        return count(Dao::alumnesOfertaComplets($oferta, $container));
    }

    public function ajuda(Request $request, Response $response, $args, \Slim\Container $container)
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

    public function ciclesFamilia(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $familia = Familia::find($args['idFamilia']);
            if ($familia != null) {
                return $response->withJson($familia->cicles, 200);

            } else {
                return $response->withJson(array("missatge" => "No es troba l'ajuda demanada."), 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {

            return $response->withJson(array("missatge" => "No es troba l'ajuda demanada."), 422);
        }
    }
}

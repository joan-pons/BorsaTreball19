<?php

namespace Borsa;

use Borsa\Empresa as Empresa;
use Borsa\Oferta as Oferta;
use Correu\Bustia;
use Illuminate\Database\Capsule\Manager as DB;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Description of DaoEmpresa
 *
 * @author joan
 */
class DaoEmpresa extends Dao
{

    public static function altaEmpresa(Request $request, Response $response, \Slim\Container $container)
    {
        //TODO: Filtrar descripció
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $empresa = new Empresa;
            $empresa->nom = filter_var($data['nom'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $empresa->descripcio = $data['descripcio'];
            $empresa->adreca = filter_var($data['adreca'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $empresa->codiPostal = filter_var($data['codiPostal'], FILTER_SANITIZE_STRING);
            $empresa->localitat = filter_var($data['localitat'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $empresa->provincia = filter_var($data['provincia'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $empresa->telefon = filter_var($data['telefon'], FILTER_SANITIZE_STRING);
            $empresa->email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
            $empresa->activa = filter_var($data['actiu'], FILTER_SANITIZE_STRING) == 'true';
            $empresa->validada = false;
            $empresa->url = filter_var($data['url'], FILTER_SANITIZE_URL);
            $empresa->familia = filter_var($data['familia'], FILTER_SANITIZE_STRING);
            $empresa->guardar = filter_var($data['guardar'], FILTER_SANITIZE_STRING);
            $empresa->cedir = filter_var($data['cedir'], FILTER_SANITIZE_STRING);
            //$empresa->DataAlta= \Carbon::now();
            $empresa->save();
            $professors = DB::select('SELECT p.* FROM borsa.Estudis_has_Responsables r inner join borsa.Estudis e on e.codi = r.Estudis_codi inner join borsa.Professors p on r.Professors_idProfessor=p.idProfessor where p.actiu=1 and e.familia=\'' . $empresa->familia . '\'');
            if (count($professors) > 0) {
                foreach ($professors as $professor) {
                    $usuari = Usuari::where('nomUsuari', $professor->email)->get();
                    $r = Dao::generaToken(20, $usuari[0], 7, $container);
                    Bustia::enviarUnic($professor->email, 'Validació d\'empresa pendent', '/email/validarEmpresa.html.twig', ['empresa' => $empresa->nom, 'token' => $r->token], $container);
                }
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
            $missatge = array("missatge" => 'Alta correcta.', "empresa" => $empresa, "data" => $data);
            return $response->withJson($missatge);
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

    public static function modificarEmpresa(Request $request, Response $response, $args, \Slim\Container $container)
    {
        //TODO: Filtrar descripció
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $empresa = Empresa::find(filter_var($args['idEmpresa'], FILTER_SANITIZE_NUMBER_INT));
            if ($empresa != null) {
                $empresa->nom = filter_var($data['nom'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                $empresa->descripcio = $data['descripcio'];
                $empresa->adreca = filter_var($data['adreca'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                $empresa->codiPostal = filter_var($data['codiPostal'], FILTER_SANITIZE_STRING);
                $empresa->localitat = filter_var($data['localitat'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                $empresa->Provincia = filter_var($data['provincia'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                $empresa->telefon = filter_var($data['telefon'], FILTER_SANITIZE_STRING);
                $empresa->email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
                $empresa->activa = filter_var($data['actiu'], FILTER_SANITIZE_STRING) == 'true';
                $empresa->validada = filter_var($data['validada'], FILTER_SANITIZE_STRING);
                $empresa->url = filter_var($data['url'], FILTER_SANITIZE_URL);
                //$empresa->DataAlta= \Carbon::now();
                $empresa->save();
                return $response->withJson($empresa);
            } else {
                $missatge = array("missatge" => "No s'ha trobat l'empresa que es vol modificar.");
                return $response->withJson($missatge, 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que el correu electrònic ja està registrat per una altra empresa.");
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.");
                    break;
                default:
                    $missatge = array("missatge" => "Les dades de l'empresa no s'han pogut modificar correctament.");
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function altaContacte(Request $request, Response $response, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $contacte = new Contacte;
            $contacte->nom = filter_var($data['nom'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $contacte->llinatges = filter_var($data['llinatges'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $contacte->telefon = filter_var($data['telefon'], FILTER_SANITIZE_STRING);
            $contacte->email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
            $contacte->carrec = filter_var($data['carrec'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $contacte->Empreses_idEmpresa = filter_var($data['idEmpresa'], FILTER_SANITIZE_NUMBER_INT);
            $contacte->save();
            return $response->withJson($contacte);
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
                    $missatge = array("missatge" => "El contacte no s'ha pogut afegir correctament a la llista de contactes de l'empresa.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function modificarContacte(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $contacte = Contacte::find(filter_var($args['idContacte'], FILTER_SANITIZE_NUMBER_INT));
            if ($contacte != null) {
                $contacte->nom = filter_var($data['nom'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                $contacte->llinatges = filter_var($data['llinatges'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                $contacte->telefon = filter_var($data['telefon'], FILTER_SANITIZE_STRING);
                $contacte->carrec = filter_var($data['carrec'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                $contacte->email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
                $contacte->Empreses_idEmpresa = filter_var($data['idEmpresa'], FILTER_SANITIZE_NUMBER_INT);
                $contacte->save();
                return $response->withJson($contacte);
            } else {
                $missatge = array("missatge" => "No s'ha trobat el contacte que es vol modificar.");
                return $response->withJson($missatge, 422);
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
                    $missatge = array("missatge" => "Les dades del contacte no s'han pogut modificar correctament.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function esborrarContacte(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;

            $contacte = Contacte::find(filter_var($args['idContacte'], FILTER_SANITIZE_NUMBER_INT));
            if ($contacte != null) {
                Contacte::destroy($args['idContacte']);
                return $response->withJson($contacte);
            } else {
                return $response->withJson("No es troba cap contacte amb l'identificador demanat.", 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "No s'ha pogut eliminar. Segurament degut a que el contacte està associat a alguna oferta.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
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

    public static function validar(Request $request, Response $response, $args, \Slim\Container $container, $professor)
    {

        try {
            $container->dbEloquent;
            $empresa = Empresa::find(filter_var($args['idEmpresa'], FILTER_SANITIZE_NUMBER_INT));
            if ($empresa != null) {
                $data = $request->getParsedBody();
                //$empresa->activa = false;//
                $empresa->activa = filter_var($data['activa'], FILTER_SANITIZE_STRING);
                $empresa->validada = filter_var($data['validada'], FILTER_SANITIZE_STRING);
                $empresa->rebuig = filter_var($data['rebuig'], FILTER_SANITIZE_STRING);
                if ($empresa->validada < 2) {
                    $empresa->rebuig = null;
                }
                $empresa->profValidada = $professor->idProfessor;
                $empresa->save();

                $longitud = 20;
                $token = bin2hex(random_bytes(($longitud - ($longitud % 2)) / 2));
                $r = new Token();
                $r->idUsuari = $empresa->getUsuari()->idUsuari;
                $r->token = $token;
                $r->data = date('Y-m-d H:i:s', strtotime('+1 week'));
                $r->save();

                $usuari = Usuari::where('idEntitat', $empresa->idEmpresa)->where('tipusUsuari', 20)->get();
                if ($empresa->validada) { //Acceptada
                    Bustia::enviarUnic($empresa->email, 'Sol·licitud aprovada', '/email/instruccionsValidat.html.twig', ['usuari' => $empresa->email, 'contrasenya' => $usuari[0]->contrasenya, 'token' => $token], $container);
                } else { //Rebutjada
                    Bustia::enviarUnic($empresa->email, 'Sol·licitud rebutjada', '/email/rebutjarEmpresa.twig', ['motius' => $empresa->rebuig, 'usuari' => $empresa->email], $container);
                }

                $professors = DB::select('SELECT p.* FROM borsa.Estudis_has_Responsables r inner join borsa.Estudis e on e.codi = r.Estudis_codi inner join borsa.Professors p on r.Professors_idProfessor=p.idProfessor where p.actiu=1 and e.familia=\'' . $empresa->familia . '\'');
                if (count($professors) > 1) {
                    foreach ($professors as $profe) {
                        if ($profe->idProfessor != $professor->idProfessor)
                            $usuari = Usuari::where('nomUsuari', $profe->email)->get();
                        $r = Dao::generaToken(20, $usuari[0], 7, $container);
                        Bustia::enviarUnic($profe->email, 'Empresa validada per un company', '/email/empresaValidada.html.twig', ['empresa' => $empresa->nom, 'professor' => $professor], $container);
                    }
                }

                return $response->withJson($empresa);
            } else {
                return $response->withJson("No es troba cap empresa amb l'identificador demanat.", 422);
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
                    $missatge = array("missatge" => "L'empresa no s'ha pogut modificar.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function altaOferta(Request $request, Response $response, \Slim\Container $container)
    {
        //TODO Filtrar descripció
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $oferta = new Oferta;

            $oferta->titol = filter_var($data['titol'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $oferta->descripcio = $data['descripcio'];
            if ($data['dPublicacio'] == '') {
                $oferta->dataPublicacio = null;
            } else {
                $oferta->dataPublicacio = filter_var($data['dPublicacio'], FILTER_SANITIZE_STRING);
            }
            $oferta->dataFinal = filter_var($data['dFinal'], FILTER_SANITIZE_STRING);
            $oferta->localitat = filter_var($data['localitat'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $oferta->tipusContracte = filter_var($data['tContracte'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $oferta->horari = filter_var($data['horari'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $oferta->professorValidada = null;
            $oferta->validada = false;
            $oferta->Empreses_idEmpresa = filter_var($data['idEmpresa'], FILTER_SANITIZE_NUMBER_INT);
            $cicles = array();
            if ($data['estudis'] != null) {
                foreach ($data['estudis'] as $cicle){
                    $cicles[$cicle['id']]=array('any'=>1900,'nota'=>5);
                }
            }
            $oferta->save();
            $oferta->estudis()->sync($cicles);
            return $response->withJson($oferta);
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que el correu electrònic ja està registrat per una altra empresa.", 'info' => $ex->getCode() . " " . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getCode() . " " . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "L'oferta no s'ha pogut donar d'alta correctament. ", 'info' => $ex->getCode() . " " . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function modificarOferta(Request $request, Response $response, $args, \Slim\Container $container)
    {
        //TODO Filtrar descripció
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $oferta = Oferta::find(filter_var($args['idOferta'], FILTER_SANITIZE_NUMBER_INT));
            $oferta->titol = filter_var($data['titol'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $oferta->descripcio = $data['descripcio'];
            if ($data['dPublicacio'] == '') {
                $oferta->dataPublicacio = null;
            } else {
                $oferta->dataPublicacio = filter_var($data['dPublicacio'], FILTER_SANITIZE_STRING);
            }
            $oferta->dataFinal = filter_var($data['dFinal'], FILTER_SANITIZE_STRING);
            $oferta->localitat = filter_var($data['localitat'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $oferta->tipusContracte = filter_var($data['tContracte'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $oferta->horari = filter_var($data['horari'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $oferta->professorValidada = null;
            $oferta->validada = false;
            $oferta->Empreses_idEmpresa = filter_var($data['idEmpresa'], FILTER_SANITIZE_NUMBER_INT);
            $cicles = array();
            if ($data['estudis'] != null) {
                foreach ($data['estudis'] as $cicle){
                    $cicles[$cicle['id']]=array('any'=>1900,'nota'=>5);
                }
            }
            $oferta->save();
            $oferta->estudis()->sync($cicles);
            return $response->withJson($oferta);
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que el correu electrònic ja està registrat per una altra empresa.", 'info' => $ex->getCode() . " " . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getCode() . " " . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "L'oferta no s'ha pogut modificar correctament. ", 'info' => $ex->getCode() . " " . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function afegirEstudisOferta(Request $request, Response $response, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $oferta = Oferta::find(filter_var($data['identificador'], FILTER_SANITIZE_NUMBER_INT));
            if ($oferta != null) {
                $codiEstudis = filter_var($data['codiEstudis'], FILTER_SANITIZE_STRING);
                //  $alumne->estudis()->sync(array($codiEstudis => array('any' => $data['any'], 'nota' => $data['nota'])), false);
                $oferta->estudis()->attach($codiEstudis, array('any' => filter_var($data['any'], FILTER_SANITIZE_NUMBER_INT), 'nota' => filter_var($data['nota'], FILTER_SANITIZE_NUMBER_INT)));
                return $response->withJson(array('quantitat' => Dao::comptarCandidats($oferta, $container)));
            } else {
                $missatge = array("missatge" => "No s'ha trobat l'oferta que es vol modificar.");
                return $response->withJson($missatge, 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que ja estan registrats els estudis que vol afegir.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "Els estudis no s'han pogut afegir correctament a la llista de l'oferta.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function esborrarEstudis(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;

            $oferta = Oferta::find(filter_var($args['idOferta'], FILTER_SANITIZE_NUMBER_INT));
            $estudis = filter_var($args['codiEstudis'], FILTER_SANITIZE_STRING);
            if ($oferta != null) {
                $oferta->estudis()->detach([$estudis]);
                return $response->withJson(array('quantitat' => Dao::comptarCandidats($oferta, $container)));
            } else {
                return $response->withJson("No es troba cap estudis amb l'identificador demanat.", 422);
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
                    $missatge = array("missatge" => "No s'ha pogut dur a terme l'eliminació.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function modificarEstudis(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $oferta = Oferta::find(filter_var($args['idOferta'], FILTER_SANITIZE_NUMBER_INT));
            if ($oferta != null) {
                $codiEstudis = filter_var($args['codiEstudis'], FILTER_SANITIZE_STRING);
                $oferta->estudis()->sync(array($codiEstudis => array('any' => filter_var($data['any'], FILTER_SANITIZE_NUMBER_INT), 'nota' => filter_var($data['nota'], FILTER_SANITIZE_NUMBER_INT))), false);
                return $response->withJson(array('quantitat' => Dao::comptarCandidats($oferta, $container)));
            } else {
                $missatge = array("missatge" => "No s'ha trobat l'alumne que es vol modificar.");
                return $response->withJson($missatge, 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que ja tens registrats els estudis que vols afegir.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "Els estudis no s'han pogut afegir correctament a la llista de l'oferta.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function modificarIdiomes(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $oferta = Oferta::find(filter_var($args['idOferta'], FILTER_SANITIZE_NUMBER_INT));
            if ($oferta != null) {
                $rebudes = $data['nivells'];
                $dades = array();
                if ($rebudes != null) {
                    //TODO: SANITIZE
                    foreach ($rebudes as $nivell) {
                        $dades[$nivell['idIdioma']] = array('NivellsIdioma_idNivellIdioma' => $nivell['NivellsIdioma_idNivellIdioma']);
                    }
                }
                $oferta->idiomes()->sync($dades);
                return $response->withJson(array('quantitat' => Dao::comptarCandidats($oferta, $container)));
            } else {
                $missatge = array("missatge" => "No s'ha trobat l'oferta que es vol modificar.");
                return $response->withJson($missatge, 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que ja tens registrats els estudis que vols afegir.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "Els estudis no s'han pogut afegir correctament a la llista de l'oferta.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function modificarEstatLaboral(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $oferta = Oferta::find($args['idOferta']);
            if ($oferta != null) {
                $rebudes = $data['estats'];
                $dades = array();
                if ($rebudes != null) {
                    foreach ($rebudes as $estat) {
                        array_push($dades, filter_var($estat, FILTER_SANITIZE_NUMBER_INT));
                    }
                }
                $oferta->estatsLaborals()->sync($dades);
                return $response->withJson(array('quantitat' => Dao::comptarCandidats($oferta, $container)));
            } else {
                $missatge = array("missatge" => "No s'ha trobat l'oferta que es vol modificar.");
                return $response->withJson($missatge, 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que ja tens registrats els estudis que vols afegir.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "Les situacions laborals no s'han pogut afegir correctament a la llista de l'oferta.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function afegirContOf(Request $request, Response $response, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $oferta = Oferta::find(filter_var($data['idOferta'], FILTER_SANITIZE_NUMBER_INT));
            if ($oferta != null) {
                $idContacte = filter_var($data['idContacte'], FILTER_SANITIZE_NUMBER_INT);
                $oferta->contactes()->sync([$idContacte], false);

                return $response->withJson($oferta);
            } else {
                $missatge = array("missatge" => "No s'ha trobat l'oferta que es vol modificar.");
                return $response->withJson($missatge, 422);
            }
            return $response->withJson('POST contactesOferta afegirContacteOferta');
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
                    $missatge = array("missatge" => "El contacte no s'han pogut afegir correctament a la llista de contactes de l'oferta.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function esborrarContacteOferta(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $oferta = Oferta::find(filter_var($data['idOferta'], FILTER_SANITIZE_NUMBER_INT));
            if ($oferta != null) {
                $idContacte = filter_var($data['idContacte'], FILTER_SANITIZE_NUMBER_INT);
                $oferta->contactes()->detach([$idContacte]);
//                curl_multi_select();
                $missatge = array("missatge", "OK");
                return $response->withJson($missatge);
            } else {
                return $response->withJson("No es troba cap oferta amb l'identificador demanat.", 422);
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
                    $missatge = array("missatge" => "El contacte no s'ha pogut eliminar.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function esborrarOferta(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $oferta = Oferta::find(filter_var($args['idOferta'], FILTER_SANITIZE_NUMBER_INT));
            if ($oferta != null && $oferta->validada != 1) {
                Oferta::destroy(filter_var($args['idOferta'], FILTER_SANITIZE_NUMBER_INT));
                return $response->withJson(['oferta' => $oferta], 200);
            } else {
                return $response->withJson(array("missatge" => "No es troba cap oferta amb l'identificador demanat, o l'oferta ja està publicada."), 422);
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
                    $missatge = array("missatge" => "L'oferta no s'ha pogut eliminar.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public static function publicarOferta(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $oferta = Oferta::find(filter_var($args['idOferta'], FILTER_SANITIZE_NUMBER_INT));
            $container->dbEloquent;
            if ($oferta != null) {
                $oferta->dataPublicacio = date("Y/m/d");
                $oferta->validada = 0;
                $oferta->rebuig = null;
                $oferta->professorValidada = null;
                $professors = DB::select('select p.email from borsa.Ofertes_has_Estudis o inner join borsa.Estudis_has_Responsables e on o.Estudis_codi=e.Estudis_codi inner join borsa.Professors p on e.Professors_idProfessor=p.idProfessor where p.actiu=1 and o.Ofertes_idOferta=' . $oferta->idOferta);
                $oferta->save();
                foreach ($professors as $professor) {
                    $usuari = Usuari::where('nomUsuari', $professor->email)->get();
                    $r = Dao::generaToken(20, $usuari[0], 7, $container);
                    Bustia::enviarUnic($professor->email, 'Validacions d\'ofertes pendents', '/email/validarOferta.html.twig', ['token' => $r->token], $container);
                }
                return $response->withJson("Ok", 200);
            } else {
                return $response->withJson("No es troba cap oferta amb l'identificador demanat.", 422);
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
                    $missatge = array("missatge" => "L'oferta no s'ha pogut publicar.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }
}

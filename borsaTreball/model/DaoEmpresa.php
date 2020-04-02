<?php

namespace Borsa;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Borsa\Empresa as Empresa;
use Borsa\Oferta as Oferta;
use Illuminate\Database\Capsule\Manager as DB;
use Correu\Bustia;

/**
 * Description of DaoEmpresa
 *
 * @author joan
 */
class DaoEmpresa extends Dao
{

    public function altaEmpresa(Request $request, Response $response, \Slim\Container $container)
    {
        //TODO: Filtrar descripció
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $empresa = new Empresa;
            $empresa->nom = filter_var($data['nom'], FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
            $empresa->descripcio =  $data['descripcio'];
            $empresa->adreca = filter_var($data['adreca'], FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
            $empresa->codiPostal = filter_var($data['codiPostal'], FILTER_SANITIZE_STRING);
            $empresa->localitat = filter_var($data['localitat'], FILTER_SANITIZE_STRING.FILTER_FLAG_NO_ENCODE_QUOTES);
            $empresa->provincia = filter_var($data['provincia'], FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
            $empresa->telefon = filter_var($data['telefon'], FILTER_SANITIZE_STRING);
            $empresa->email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
            $empresa->activa = filter_var($data['actiu'], FILTER_SANITIZE_STRING) == 'true';
            $empresa->validada = false;
            $empresa->url = filter_var($data['url'], FILTER_SANITIZE_URL);
            $empresa->familia=filter_var($data['familia'], FILTER_SANITIZE_STRING);
            //$empresa->DataAlta= \Carbon::now();
            $empresa->save();
            $professors = DB::select('SELECT p.email FROM borsa.Estudis_has_Responsables r inner join borsa.Estudis e on e.codi = r.Estudis_codi inner join borsa.Professors p on r.Professors_idProfessor=p.idProfessor where p.actiu=1 and e.familia=\''.$empresa->familia.'\'');
            Bustia::enviar($professors, 'Validacions d\'empreses pendents', '/email/validarEmpresa.html.twig', [], $container);
//            return $response->withJson($empresa);
            return $response->withJson($professors);
        } catch (\Illuminate\Database\QueryException $ex) {
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

    public function modificarEmpresa(Request $request, Response $response, $args, \Slim\Container $container)
    {
        //TODO: Filtrar descripció
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $empresa = Empresa::find(filter_var($args['idEmpresa'],FILTER_SANITIZE_NUMBER_INT));
            if ($empresa != null) {
                $empresa->nom = filter_var($data['nom'], FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
                $empresa->descripcio = $data['descripcio'];
                $empresa->adreca = filter_var($data['adreca'], FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
                $empresa->codiPostal = filter_var($data['codiPostal'], FILTER_SANITIZE_STRING);
                $empresa->localitat = filter_var($data['localitat'], FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
                $empresa->Provincia = filter_var($data['provincia'], FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
                $empresa->telefon = filter_var($data['telefon'], FILTER_SANITIZE_STRING);
                $empresa->email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
                $empresa->activa = filter_var($data['actiu'], FILTER_SANITIZE_STRING) == 'true';
                $empresa->validada = false;
                $empresa->url = filter_var($data['url'], FILTER_SANITIZE_URL);
                //$empresa->DataAlta= \Carbon::now();
                $empresa->save();
                return $response->withJson($empresa);
            } else {
                $missatge = array("missatge" => "No s'ha trobat l'empresa que es vol modificar.");
                return $response->withJson($missatge, 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
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

    public function altaContacte(Request $request, Response $response, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $contacte = new Contacte;
            $contacte->nom = filter_var($data['nom'], FILTER_SANITIZE_STRING);
            $contacte->llinatges = filter_var($data['llinatges'], FILTER_SANITIZE_STRING);
            $contacte->telefon = filter_var($data['telefon'], FILTER_SANITIZE_STRING);
            $contacte->email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
            $contacte->carrec = filter_var($data['carrec'], FILTER_SANITIZE_STRING);
            $contacte->Empreses_idEmpresa = filter_var($data['idEmpresa'], FILTER_SANITIZE_NUMBER_INT);
            $contacte->save();
            return $response->withJson($contacte);
        } catch (\Illuminate\Database\QueryException $ex) {
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

    public function modificarContacte(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $contacte = Contacte::find(filter_var($args['idContacte'],FILTER_SANITIZE_NUMBER_INT));
            if ($contacte != null) {
                $contacte->nom = filter_var($data['nom'], FILTER_SANITIZE_STRING);
                $contacte->llinatges = filter_var($data['llinatges'], FILTER_SANITIZE_STRING);
                $contacte->telefon = filter_var($data['telefon'], FILTER_SANITIZE_STRING);
                $contacte->carrec = filter_var($data['carrec'], FILTER_SANITIZE_STRING);
                $contacte->email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
                $contacte->Empreses_idEmpresa = filter_var($data['idEmpresa'], FILTER_SANITIZE_NUMBER_INT);
                $contacte->save();
                return $response->withJson($contacte);
            } else {
                $missatge = array("missatge" => "No s'ha trobat el contacte que es vol modificar.");
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
                    $missatge = array("missatge" => "Les dades del contacte no s'han pogut modificar correctament.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public function esborrarContacte(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;

            $contacte = Contacte::find(filter_var($args['idContacte'],FILTER_SANITIZE_NUMBER_INT));
            if ($contacte != null) {
                Contacte::destroy($args['idContacte']);
                return $response->withJson($contacte);
            } else {
                return $response->withJson("No es troba cap contacte amb l'identificador demanat.", 422);
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
                    $missatge = array("missatge" => "El contacte no s'ha pogut eliminar, possiblement per tenir ofertes associades.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public function validar(Request $request, Response $response, $args, \Slim\Container $container)
    {

        try {
            $container->dbEloquent;
            $empresa = Empresa::find(filter_var($args['idEmpresa'],FILTER_SANITIZE_NUMBER_INT));
            if ($empresa != null) {
                $data = $request->getParsedBody();
                $empresa->activa = false;//filter_var($data['activa'], FILTER_SANITIZE_STRING) == 'true';
                $empresa->validada = filter_var($data['validada'], FILTER_SANITIZE_STRING) == 'true';
                $empresa->rebuig = filter_var($data['rebuig'], FILTER_SANITIZE_STRING);
                $empresa->save();
                $mails=[];
                $mails[]=(object) array('email'=>$empresa->email);

                $usuari=Usuari::where('idEntitat',$empresa->idEmpresa)->where('tipusUsuari',20)->get();
                if($empresa->rebuig==null) { //Acceptada
                    Bustia::enviar($mails, 'Sol·licitud aprovada', '/email/instruccionsValidat.html.twig', ['usuari'=>$empresa->email, 'contrasenya'=>$usuari[0]->contrasenya], $container);
                }else{ //Rebutjada
                    Bustia::enviar($mails, 'Sol·licitud rebutjada', '/email/rebutjarEmpresa.twig', ['motius'=>$empresa->rebuig, 'usuari'=>$empresa->email], $container);
                }
                return $response->withJson($empresa);
            } else {
                return $response->withJson("No es troba cap empresa amb l'identificador demanat.", 422);
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
                    $missatge = array("missatge" => "L'empresa no s'ha pogut modificar.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public function altaOferta(Request $request, Response $response, \Slim\Container $container)
    {
        //TODO Filtrar descripció
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $oferta = new Oferta;
            $oferta->titol = filter_var($data['titol'], FILTER_SANITIZE_STRING);
            $oferta->descripcio = $data['descripcio'];
            if ($data['dPublicacio'] == '') {
                $oferta->dataPublicacio = null;
            } else {
                $oferta->dataPublicacio = filter_var($data['dPublicacio'], FILTER_SANITIZE_STRING);
            }
            $oferta->dataFinal = filter_var($data['dFinal'], FILTER_SANITIZE_STRING);
            $oferta->localitat = filter_var($data['localitat'], FILTER_SANITIZE_STRING);
            $oferta->tipusContracte = filter_var($data['tContracte'], FILTER_SANITIZE_STRING);
            $oferta->horari = filter_var($data['horari'], FILTER_SANITIZE_STRING);
            $oferta->professorValidada = null;
            $oferta->validada = false;
            $oferta->Empreses_idEmpresa = filter_var($data['idEmpresa'], FILTER_SANITIZE_NUMBER_INT);
            //$empresa->DataAlta= \Carbon::now();
            $oferta->save();
            return $response->withJson($oferta);
        } catch (\Illuminate\Database\QueryException $ex) {
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

    public function modificarOferta(Request $request, Response $response, $args, \Slim\Container $container)
    {
        //TODO Filtrar descripció
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $oferta = Oferta::find(filter_var($args['idOferta'],FILTER_SANITIZE_NUMBER_INT));
            $oferta->titol = filter_var($data['titol'], FILTER_SANITIZE_STRING);
            $oferta->descripcio = $data['descripcio'];
            if ($data['dPublicacio'] == '') {
                $oferta->dataPublicacio = null;
            } else {
                $oferta->dataPublicacio = filter_var($data['dPublicacio'], FILTER_SANITIZE_STRING);
            }
            $oferta->dataFinal = filter_var($data['dFinal'], FILTER_SANITIZE_STRING);
            $oferta->localitat = filter_var($data['localitat'], FILTER_SANITIZE_STRING);
            $oferta->tipusContracte = filter_var($data['tContracte'], FILTER_SANITIZE_STRING);
            $oferta->horari = filter_var($data['horari'], FILTER_SANITIZE_STRING);
            $oferta->professorValidada = null;
            $oferta->validada = false;
            $oferta->Empreses_idEmpresa = filter_var($data['idEmpresa'], FILTER_SANITIZE_NUMBER_INT);
            //$empresa->DataAlta= \Carbon::now();
            $oferta->save();
            return $response->withJson($oferta);
        } catch (\Illuminate\Database\QueryException $ex) {
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

    public function afegirEstudisOferta(Request $request, Response $response, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $oferta = Oferta::find(filter_var($data['identificador'],FILTER_SANITIZE_NUMBER_INT));
            if ($oferta != null) {
                $codiEstudis = filter_var($data['codiEstudis'], FILTER_SANITIZE_STRING);
                //  $alumne->estudis()->sync(array($codiEstudis => array('any' => $data['any'], 'nota' => $data['nota'])), false);
                $oferta->estudis()->attach($codiEstudis, array('any' => filter_var($data['any'],FILTER_SANITIZE_NUMBER_INT), 'nota' => filter_var($data['nota'],FILTER_SANITIZE_NUMBER_INT)));
                return $response->withJson(array('quantitat' => Dao::comptarCandidats($oferta, $container)));
            } else {
                $missatge = array("missatge" => "No s'ha trobat l'oferta que es vol modificar.");
                return $response->withJson($missatge, 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
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

    public function esborrarEstudis(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;

            $oferta = Oferta::find(filter_var($args['idOferta'],FILTER_SANITIZE_NUMBER_INT));
            $estudis = filter_var($args['codiEstudis'],FILTER_SANITIZE_STRING);
            if ($oferta != null) {
                $oferta->estudis()->detach($estudis);
                return $response->withJson(array('quantitat' => Dao::comptarCandidats($oferta, $container)));
            } else {
                return $response->withJson("No es troba cap estudis amb l'identificador demanat.", 422);
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
                    $missatge = array("missatge" => "No s'ha pogut dur a terme l'eliminació.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public function modificarEstudis(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $oferta = Oferta::find(filter_var($args['idOferta'],FILTER_SANITIZE_NUMBER_INT));
            if ($oferta != null) {
                $codiEstudis = filter_var($args['codiEstudis'], FILTER_SANITIZE_STRING);
                $oferta->estudis()->sync(array($codiEstudis => array('any' => filter_var($data['any'],FILTER_SANITIZE_NUMBER_INT), 'nota' => filter_var($data['nota'],FILTER_SANITIZE_NUMBER_INT))), false);
                return $response->withJson(array('quantitat' => Dao::comptarCandidats($oferta, $container)));
            } else {
                $missatge = array("missatge" => "No s'ha trobat l'alumne que es vol modificar.");
                return $response->withJson($missatge, 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
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

    public function modificarIdiomes(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $oferta = Oferta::find(filter_var($args['idOferta'],FILTER_SANITIZE_NUMBER_INT));
            if ($oferta != null) {
//                $codiEstudis = filter_var($args['codiEstudis'], FILTER_SANITIZE_STRING);
//                $alumne->estudis()->sync(array($codiEstudis => array('any' => $data['any'], 'nota' => $data['nota'])), false);
                $rebudes = $data['nivells'];
                $dades = array();
                //TODO: SANITIZE
                foreach ($rebudes as $nivell) {
                    $dades[$nivell['idIdioma']] = array('NivellsIdioma_idNivellIdioma' => $nivell['NivellsIdioma_idNivellIdioma']);
                }
                $oferta->idiomes()->sync($dades);
                return $response->withJson(array('quantitat' => Dao::comptarCandidats($oferta, $container)));
            } else {
                $missatge = array("missatge" => "No s'ha trobat l'oferta que es vol modificar.");
                return $response->withJson($missatge, 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
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

    public function modificarEstatLaboral(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $oferta = Oferta::find($args['idOferta']);
            if ($oferta != null) {
//                $codiEstudis = filter_var($args['codiEstudis'], FILTER_SANITIZE_STRING);
//                $alumne->estudis()->sync(array($codiEstudis => array('any' => $data['any'], 'nota' => $data['nota'])), false);
                $rebudes = $data['estats'];
                $dades = array();
                foreach ($rebudes as $estat) {
                    array_push($dades, filter_var($estat,FILTER_SANITIZE_NUMBER_INT));
                }
                $oferta->estatsLaborals()->sync($dades);
                return $response->withJson(array('quantitat' => Dao::comptarCandidats($oferta, $container)));
            } else {
                $missatge = array("missatge" => "No s'ha trobat l'oferta que es vol modificar.");
                return $response->withJson($missatge, 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
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

    public function afegirContOf(Request $request, Response $response, \Slim\Container $container)
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

    public function esborrarContacteOferta(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $oferta = Oferta::find(filter_var($data['idOferta'], FILTER_SANITIZE_NUMBER_INT));
            if ($oferta != null) {
                $idContacte = filter_var($data['idContacte'], FILTER_SANITIZE_NUMBER_INT);
                $oferta->contactes()->detach($idContacte);
                curl_multi_select();
                return $response->withJson($oferta);
            } else {
                return $response->withJson("No es troba cap oferta amb l'identificador demanat.", 422);
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
                    $missatge = array("missatge" => "El contacte no s'ha pogut eliminar.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public function esborrarOferta(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $oferta = Oferta::find(filter_var($args['idOferta'], FILTER_SANITIZE_NUMBER_INT));
            if ($oferta != null && !$oferta->validada) {
                Oferta::destroy(filter_var($args['idOferta'], FILTER_SANITIZE_NUMBER_INT));
                return $response->withJson(['oferta' => $oferta]);
            } else {
                return $response->withJson("No es troba cap oferta amb l'identificador demanat, o l'oferta ja està publicada.", 422);
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
                    $missatge = array("missatge" => "L'oferta no s'ha pogut eliminar.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public function publicarOferta(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $oferta = Oferta::find(filter_var($args['idOferta'], FILTER_SANITIZE_NUMBER_INT));
            $container->dbEloquent;
            if ($oferta != null) {
                $oferta->dataPublicacio = date("Y/m/d");
                $oferta->validada=0;
                $oferta->rebuig=null;
                $oferta->professorValidada=null;
                $professors = DB::select('select p.email from borsa.Ofertes_has_Estudis o inner join borsa.Estudis_has_Responsables e on o.Estudis_codi=e.Estudis_codi inner join borsa.Professors p on e.Professors_idProfessor=p.idProfessor where p.actiu=1 and o.Ofertes_idOferta=' . $oferta->idOferta);
                $oferta->save();
                Bustia::enviar($professors, 'Validacions d\'ofertes pendents', '/email/validarOferta.html.twig', [], $container);
                return $response->withJson($professors);
            } else {
                return $response->withJson("No es troba cap oferta amb l'identificador demanat.", 422);
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
                    $missatge = array("missatge" => "L'oferta no s'ha pogut publicar.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }
}

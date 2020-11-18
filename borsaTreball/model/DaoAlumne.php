<?php

namespace Borsa;

use Borsa\Alumne as Alumne;
use Correu\Bustia;
use Illuminate\Database\Capsule\Manager as DB;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Description of DaoProfessor
 *
 * @author joan
 */
class DaoAlumne extends Dao
{

    public function altaAlumne(Request $request, Response $response, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $alumne = new Alumne();
            $alumne->nom = filter_var($data['nom'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $alumne->llinatges = filter_var($data['llinatges'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $alumne->email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
            $alumne->estudisAlta = filter_var($data['cicle'], FILTER_SANITIZE_EMAIL);
            $alumne->actiu = 0;
            $alumne->guardar = filter_var($data['guardar'], FILTER_SANITIZE_STRING);
            $alumne->cedir = filter_var($data['cedir'], FILTER_SANITIZE_STRING);
            $alumne->save();

            $numAlumnes = DB::select('SELECT count(*) FROM borsa.Alumnes where estudisAlta=\'' . $alumne->estudisAlta . '\'  and validat=0 and dataAlta > date_add(NOW(), INTERVAL -7 DAY)');
            if (true){ //TODO $numAlumnes  == 0) {
                $estudis = Estudis::find($alumne->estudisAlta);
                if (count($estudis->professors) > 0) {
                    foreach ($estudis->professors as $professor) {
                        $usuari = Usuari::where('nomUsuari', $professor->email)->get();

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
            $missatge = array("missatge" => "Alta correcta");
            return $response->withJson($missatge);
        } catch (\Illuminate\Database\QueryException $ex) {
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

    public function modificarDades(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $alumne = Alumne::find(filter_var($args['idAlumne'], FILTER_SANITIZE_NUMBER_INT));
            if ($alumne != null) {
                $alumne->nom = filter_var($data['nom'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                $alumne->llinatges = filter_var($data['llinatges'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                $alumne->descripcio = $data['descripcio'];
                $alumne->adreca = filter_var($data['adreca'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                $alumne->codiPostal = filter_var($data['codiPostal'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                $alumne->localitat = filter_var($data['localitat'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                $alumne->provincia = filter_var($data['provincia'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                $alumne->telefon = filter_var($data['telefon'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                $alumne->email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
                $alumne->actiu = filter_var($data['actiu'], FILTER_SANITIZE_STRING) == 'true';
                $alumne->url = filter_var($data['url'], FILTER_SANITIZE_URL);
                $alumne->save();
                return $response->withJson($alumne);
            } else {
                $missatge = array("missatge" => "No s'ha trobat l'alumne que es vol modificar.");
                return $response->withJson($missatge, 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
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

    public function afegirEstudis(Request $request, Response $response, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $alumne = Alumne::find($data['identificador']);
            if ($alumne != null) {
                $codiEstudis = filter_var($data['codiEstudis'], FILTER_SANITIZE_STRING);
                //  $alumne->estudis()->sync(array($codiEstudis => array('any' => $data['any'], 'nota' => $data['nota'])), false);
                $alumne->estudis()->attach($codiEstudis, array('any' => $data['any'], 'nota' => $data['nota']));
                return $response->withJson($alumne);
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
                    $missatge = array("missatge" => "Els estudis no s'han pogut afegir correctament a la teva llista.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public function esborrarEstudis(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;

            $alumne = Alumne::find($args['idAlumne']);
            $estudis = $args['codiEstudis'];
            if ($alumne != null) {
                $alumne->estudis()->detach([$estudis]);
                return $response->withJson($alumne);
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
            $alumne = Alumne::find($args['idAlumne']);
            if ($alumne != null) {
                $codiEstudis = filter_var($args['codiEstudis'], FILTER_SANITIZE_STRING);
                $alumne->estudis()->sync(array($codiEstudis => array('any' => $data['any'], 'nota' => $data['nota'])), false);
                return $response->withJson($alumne);
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
                    $missatge = array("missatge" => "Els estudis no s'han pogut afegir correctament a la teva llista.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
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
            $alumne = Alumne::find($args['idAlumne']);
            if ($alumne != null) {
//                $codiEstudis = filter_var($args['codiEstudis'], FILTER_SANITIZE_STRING);
//                $alumne->estudis()->sync(array($codiEstudis => array('any' => $data['any'], 'nota' => $data['nota'])), false);
                $rebudes = $data['nivells'];
                $dades = array();
                foreach ($rebudes as $nivell) {

                    $dades[$nivell['idIdioma']] = array('NivellsIdioma_idNivellIdioma' => $nivell['NivellsIdioma_idNivellIdioma']);
                }
                $alumne->idiomes()->sync($dades);
                return $response->withJson($dades);
            } else {
                $missatge = array("missatge" => "No s'ha trobat l'alumne que es vol modificar.");
                return $response->withJson($missatge, 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que ja tens registrats els estudis que vols afegir.", 'info' => $ex->getCode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getCode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "Els idiomes no s'han pogut modificar correctament a la teva llista.", 'info' => $ex->getCode() . ' ' . $ex->getMessage());
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
            $alumne = Alumne::find($args['idAlumne']);
            if ($alumne != null) {
//                $codiEstudis = filter_var($args['codiEstudis'], FILTER_SANITIZE_STRING);
//                $alumne->estudis()->sync(array($codiEstudis => array('any' => $data['any'], 'nota' => $data['nota'])), false);
                $rebudes = $data['estats'];
                $dades = array();
                foreach ($rebudes as $estat) {

                    array_push($dades, $estat);
                }
                $alumne->estatsLaborals()->sync($dades);
                return $response->withJson($dades);
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
                    $missatge = array("missatge" => "Els estats laborals no s'han pogut afegir correctament a la teva llista.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public function validarAlumnes(Request $request, Response $response, $args, \Slim\Container $container, $professor)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $resultats = array();
            foreach ($data as $alu) {
                $nou = filter_var($alu['validat'], FILTER_SANITIZE_NUMBER_INT);
                if ($nou != 0) {
                    $alumne = Alumne::find(filter_var($alu['idAlumne'], FILTER_SANITIZE_NUMBER_INT));
                    $alumne->validat = $nou;
                    $alumne->profValidat = $professor->idProfessor;
                    $alumne->save();

                    $longitud = 20;
                    $token = bin2hex(random_bytes(($longitud - ($longitud % 2)) / 2));
                    $r = new Token();
                    $r->idUsuari = $alumne->getUsuari()->idUsuari;
                    $r->token = $token;
                    $r->data = date('Y-m-d H:i:s', strtotime('+1 week'));
                    $r->save();

                    $resultat = Bustia::enviarUnic($alumne->email, 'Validació a la borsa de treball del Pau Casesnoves', "/email/resultatValidacioAlumne.html.twig", ['alumne' => $alumne, 'contrasenya' => $alumne->getUsuari()->contrasenya, 'token' => $token], $container);
                    $resultats[] = array("email" => $alumne->email, "resultat" => $resultat);
                }
            }
            $missatge = array("missatge" => "OK");
            return $response->withJson($resultats);
        } catch (\Illuminate\Database\QueryException $ex) {
            switch ($ex->getCode()) {
                case 23000:
                    $missatge = array("missatge" => "Dades duplicades. Segurament degut a que ja tens registrats els estudis que vols afegir.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                case 'HY000':
                    $missatge = array("missatge" => "Algunes de les dades obligatòries han arribat sense valor.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
                default:
                    $missatge = array("missatge" => "Els estats laborals no s'han pogut afegir correctament a la teva llista.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

}

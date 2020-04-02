<?php

namespace Borsa;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Borsa\Professor as Professor;
use Illuminate\Database\Capsule\Manager as DB;
use Correu\Bustia as Bustia;


/**
 * Description of DaoProfessor
 *
 * @author joan
 */
class DaoProfessor extends Dao
{
//TODO: Revisar Sanitize i actiu
    public function altaProfessor(Request $request, Response $response, \Slim\Container $container)
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
            Bustia::enviar($admins, 'Sol·licitud d\'alta professorat', '/email/validarUsuari.html.twig', [], $container);
            return $response->withJSON($professor);
        } catch (\Illuminate\Database\QueryException $ex) {
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

    public function modificarProfessor(Request $request, Response $response, $args, \Slim\Container $container)
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

    public function afegirEstudis(Request $request, Response $response, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $professor = Professor::find(filter_var($data['idProfessor'],FILTER_SANITIZE_NUMBER_INT));
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

    public function esborrarEstudis(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;

            $professor = Professor::find(filter_var($args['idProfessor'], FILTER_SANITIZE_NUMBER_INT));
            $estudis = filter_var($args['codiEstudis'], FILTER_SANITIZE_STRING);
            if ($professor != null) {
                $professor->estudis()->detach($estudis);
                $missatge = array("missatge" => "Estudis eliminats.");
                return $response->withJSON($missatge);
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

    public function activar(Request $request, Response $response, $args, \Slim\Container $container)
    {
        //TODO Canviar validació professors amb missatge per rebuig
        try {
            $container->dbEloquent;

            $professor = Professor::find(filter_var($args['idProfessor'], FILTER_SANITIZE_NUMBER_INT));
            if ($professor != null) {
                $data = $request->getParsedBody();
                $validat = filter_var($data['validat'], FILTER_SANITIZE_STRING) == 'true';
                $activat = filter_var($data['actiu'], FILTER_SANITIZE_STRING) == 'true' && $validat;
                $correu=$professor->email;
                //TODO: Eliminar el meu correu
                if ($validat and $professor->validat!=1) {
                    $resultat = Bustia::enviarUnic($professor->email, 'Validació correcta', "/email/instruccionsValidat.html.twig", ['usuari' => $professor->email, 'contrasenya' => $professor->getUsuari()->contrasenya, 'professor' => true], $container);
                } else if (!$validat) {
                    $resultat = Bustia::enviarUnic($professor->email, 'Validació incorrecta', "/email/rebutjarProfessor.twig", [], $container);
                } else {
                    $resultat = Bustia::enviarUnic($professor->email, 'Canvi d\'estat Actiu / Inactiu', "/email/activarProfessor.twig", [], $container);
                }
                if ($resultat == true) {
                    if($validat==false){
                        $validat=2;
                    }
                    $professor->validat = $validat;
                    $professor->actiu = $activat;
                    $professor->save();
                    $missatge = array("missatge" => "Professor validat.");
                    return $response->withJSON($missatge); //array('professor' => $professor, 'validat' => $validat, 'Activat' => $activat, 'Primera' => $validat and !$professor->validat, 'Segona' => !$validat, 'Resultat' => $resultat));
                }else{
                    $missatge = array("missatge" => "<p>No s'ha pogut enviar el missatge de confirmació. L'adreça de correu deu estar malament.</p> <p>Els canvis no s'han guardat a la base de dades.</p>");
                    return $response->withJSON($missatge, 422);

                }
            } else {
                return $response->withJson("No es troba cap professor amb l'identificador demanat.", 422);
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

    public function rols(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;

            $professor = Professor::find(filter_var($args['idProfessor'],FILTER_SANITIZE_NUMBER_INT));
            if ($professor != null) {
                $usuari = $professor->getUsuari();
                return $response->withJSON($usuari->rols);
            } else {
                return $response->withJson("No es troba cap professor amb l'identificador demanat.", 422);
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

    public function afegirRol(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $professor = Professor::find(filter_var($args['idProfessor'], FILTER_SANITIZE_NUMBER_INT));
            if ($professor != null) {
                $usuari = $professor->getUsuari();
                $data = $request->getParsedBody();
                $idrol = filter_var($data['idRol'], FILTER_SANITIZE_NUMBER_INT);
                $usuari->rols()->sync([$idrol], false);
                Bustia::enviarUnic('ptj@paucasesnovescifp.cat', 'Rol administrador', "/email/administrador.twig", ['administrador' => true], $container);

                return $response->withJSON($professor);
            } else {
                $missatge = array("missatge" => "No s'ha trobat el professor que es vol modificar.");
                return $response->withJson($missatge, 422);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
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

    public function eliminarRol(Request $request, Response $response, $args, \Slim\Container $container)
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

    public function publicarOferta(Request $request, Response $response, $args, \Slim\Container $container)
    {
        try {
            $container->dbEloquent;
            $data = $request->getParsedBody();
            $oferta = Oferta::find(filter_var($args['idOferta'], FILTER_SANITIZE_NUMBER_INT));
            $professor = Professor::find(filter_var($data['idProfessor'],FILTER_SANITIZE_NUMBER_INT));
            if ($professor != null && $oferta != null) {
                $oferta->validada = filter_var($data['validada'], FILTER_SANITIZE_NUMBER_INT);
                $oferta->professorValidada = $professor->idProfessor;
                $alumnesDefinitiu = Dao::alumnesOfertaComplets($oferta, $container);

                //Genera un array només d'ID per al sync
                $alumnesId = array();
                foreach ($alumnesDefinitiu as $alumne) {
                    array_push($alumnesId, $alumne->idAlumne);
                }


                $oferta->alumnes()->sync($alumnesId);
                $oferta->save();

                Bustia::enviar($alumnesDefinitiu, 'Oferta de feina', '/email/oferta.twig', ['oferta' => $oferta], $container);
                Bustia::enviarUnic($oferta->empresa->email, 'Resultat de la validació de l\'oferta', '/email/ofertaResultat.html.twig', ['oferta' => $oferta], $container);
                $missatge = array("missatge" => "Oferta validada i emails enviats");
                return $response->withJSON($missatge);
            } else {
                $missatge = array("missatge" => "No s'ha trobat el professor o l'oferta que es vol validar.");
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
                    $missatge = array("missatge" => "L'oferta no s'ha pogut publicar correctament.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return $response->withJson($missatge, 422);
        }
    }

    public function rebutjarOferta(Request $request, Response $response, $args, \Slim\Container $container)
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

}

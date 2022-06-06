<?php

use Borsa\Alumne as Alumne;
use Borsa\Configuracio as Configuracio;
use Borsa\Dao as Dao;
use Borsa\DaoAlumne as DaoAlumne;
use Borsa\DaoEmpresa as DaoEmpresa;
use Borsa\DaoProfessor as DaoProfessor;
use Borsa\Empresa as Empresa;
use Borsa\EstatLaboral as EstatLaboral;
use Borsa\Estudis as Estudis;
use Borsa\Familia as Familia;
use Borsa\Idioma as Idioma;
use Borsa\NivellIdioma as NivellIdioma;
use Borsa\Oferta as Oferta;
use Borsa\Professor as Professor;
use Borsa\Token as Token;
use Borsa\Usuari as Usuari;

use Correu\Bustia as Bustia;


use Illuminate\Database\Capsule\Manager as DB;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

//use Correu\Bustia as Bustia;

require 'vendor/autoload.php';


session_start();

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['driver'] = "mysql";
$config['db']['host'] = "localhost";

require("incloure/login.php");

$config['db']['database'] = "borsa";
$config['db']['charset'] = "utf8";
$config['db']['collation'] = "utf8_unicode_ci";
$config['db']['prefix'] = "";


//Crear aplicació utilitzant la configuració demanada
$app = new \Slim\App(["settings" => $config]);


//Contenidor de dependències
$container = $app->getContainer();

$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

$container['dbEloquent'] = function ($container) {
    $db = $container['settings']['db'];
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($db, 'default');

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('plantilles');

    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

$container['mailer'] = function ($container) {
    $mailer = new PHPMailer(TRUE);

    $mailer->CharSet = 'UTF-8';

    $mailer->isSMTP();

    $mailer->Host = 'smtp.gmail.com';  // your email host, to test I use localhost and check emails using test mail server application (catches all  sent mails)
    $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;              // set blank for localhost
    $mailer->SMTPAuth = true;                 // I set false for localhost

    $mailer->Port = 587;                           // 25 for local host
//    $mailer->Username = 'modem.colonia@gmail.com';    // I set sender email in my mailer call
    $mailer->Username = 'borsa.treball@paucasesnovescifp.cat';    // I set sender email in my mailer call

    require("incloure/password.php");

    $mailer->isHTML(true);
    $mailer->SMTPDebug = 0; //4; //SMTP::DEBUG_SERVER;
    $mailer->FromName = "Borsa de treball del CIFP Pau Casesnoves";
    return new \Correu\Mailer($container->view, $mailer);
};

//$container['mailer'] = function ($container) {
//    $mailer = new PHPMailer(true);
//
//    $mailer->CharSet = 'UTF-8';
//
//    $mailer->isSMTP();
//
//    $mailer->Host = '127.0.0.1';//'ip-172-31-44-199.ec2.internal';  // your email host, to test I use localhost and check emails using test mail server application (catches all  sent mails)
//    $mailer->SMTPSecure = '';#PHPMailer::ENCRYPTION_STARTTLS;              // set blank for localhost
////    $mailer->SMTPAuth = true;                 // I set false for localhost
////
//    $mailer->Port = 25;                           // 25 for local host
//////    $mailer->Username = 'modem.colonia@gmail.com';    // I set sender email in my mailer call
//    $mailer->Username = 'borsa.treball@paucasesnovescifp.cat';    // I set sender email in my mailer call
////
//    require("incloure/password.php");
//
//    $mailer->isHTML(true);
//    $mailer->SMTPDebug = 4; //4; //SMTP::DEBUG_SERVER;
//    $mailer->Debugoutput = function($str, $level) {echo "debug level $level; message: $str";};
//    $mailer->FromName = "Borsa de treball del CIFP Pau Casesnoves";
//    return new \Correu\Mailer($container->view, $mailer);
//};

$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['view']->render($response->withStatus(404), 'auxiliars/noTrobat.html.twig');
    };
};

$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('Borsa_logger');
    $file_handler = new \Monolog\Handler\StreamHandler('./sgol/borsa.log');
    $logger->pushHandler($file_handler);
    return $logger;
};

// Index
$app->get('/', function ($request, $response, $args) {
    return $this->view->render($response, 'index.html.twig');
});

// Index
$app->get('/proves', function ($request, $response, $args) {
    $html=$this->view->fetch('/email/instruccionsValidat.html.twig',["token"=>"1234"]);
//    $resultat=Dao::altaMail(null,$html,$this);
//    $email=Bustia::enviarUnic('ptj@paucasesnovescifp.cat', 'Email de proves','/email/instruccionsValidat.html.twig',["token"=>"1234"],$this);
      $email=Bustia::enviar(['ptj@paucasesnovescifp.cat','joan.pons.tugores@gmail.com'], 'Email de proves','/email/instruccionsValidat.html.twig',["token"=>"1234"],$this);
//    $email=Bustia::afegirEmail('Email de proves',['ptj@paucasesnovescifp.cat','vqm@paucasesnovescifp.cat'],'/email/instruccionsValidat.html.twig',["token"=>"1234"],$this);
    return $response->withJSON($email);
//    return $response->withJSON($resultat);
//    return $this->view->render($response, 'index.html.twig');
});

$app->get('/provesCorreu', function ($request, $response, $args) {
    $alumne = array('validat' => 2, 'email' => 'kllskdf@ssdf.cat');
    return $this->view->render($response, 'email/validarOferta.html.twig', []);
//    $resultat = Bustia::enviarUnic("ptj@paucasesnovescifp.cat", 'Proves', "/email/rebutjarProfessor.twig", [], $this);
//    if ($resultat == true) {
//        $missatge = array("missatge" => "Procés correcte.");
//        return $response->withJSON($missatge); //array('professor' => $professor, 'validat' => $validat, 'Activat' => $activat, 'Primera' => $validat and !$professor->validat, 'Segona' => !$validat, 'Resultat' => $resultat));
//    } else {
//        $missatge = array("missatge" => "<p>No s'ha pogut enviar el missatge de confirmació. L'adreça de correu deu estar malament.</p> <p>Els canvis no s'han guardat a la base de dades.</p>");
//        return $response->withJSON($missatge, 422);
//    }
});

$app->get('/sortir', function ($request, $response, $args) {
    session_unset();
    session_destroy();
    return $response->withRedirect("/borsaTreball");
});

$app->get('/sortirLogo', function ($request, $response, $args) {
    session_unset();
    session_destroy();
    return $response->withRedirect("http://www.paucasesnovescifp.cat");
});

$app->post('/login', function ($request, $response, $args) {
    return Dao::entrada($request, $response, $args, $this);
});

$app->get('/ajuda/{idAjuda}', function ($request, $response, $args) {
    return Dao::ajuda($request, $response, $args, $this);
});
$app->get('/cicles/{idFamilia}', function ($request, $response, $args) {
    return Dao::ciclesFamilia($request, $response, $args, $this);
});

$app->get('/contrasenyaOblidada', function ($request, $response, $args) {
    $this->dbEloquent;
    $tipus = filter_var($request->getQueryParam('t'), FILTER_SANITIZE_STRING);
    return $this->view->render($response, 'contrasenyaOblidada.twig', ['tipus' => $tipus]);
});

$app->put('/contrasenyaOblidada', function ($request, $response, $args) {
    return Dao::contrasenyaOblidada($request, $response, $args, $this);
});

$app->get('/restablirContrasenya', function ($request, $response, $args) {
    $this->dbEloquent;
    $t = filter_var($request->getQueryParam('t'), FILTER_SANITIZE_STRING);
    $token = Token::find($t);
    $ara = strtotime('now');
    if ($token != null && $ara <= strtotime($token->data)) {
        $usuari = Usuari::find($token->idUsuari);
        return $this->view->render($response, 'restablirContrasenya.twig', ['restablir' => true, 'usuari' => $token, 'tipus' => $usuari->tipusUsuari, 'dtoken' => strtotime($token->data), 'ara' => $ara]);
    } else {
        return $this->view->render($response, 'auxiliars/tokenNoValid.html.twig', []);
    }
});


$app->get('/valOferta', function ($request, $response, $args) {
    return Dao::entradaToken($request, $response, $args, $this, 'professor/ofertes');
});

$app->get('/valEmpresa', function ($request, $response, $args) {
    return Dao::entradaToken($request, $response, $args, $this, 'professor/empresesPendents');
});

$app->get('/valAlumnes', function ($request, $response, $args) {
    return Dao::entradaToken($request, $response, $args, $this, 'professor/alumnesPendents');
});

$app->get('/valProfessor', function ($request, $response, $args) {
    return Dao::entradaToken($request, $response, $args, $this, '/administrador/usuarisPendents');
});


$app->put('/restablirContrasenya/{token}', function ($request, $response, $args) {
    return Dao::restablirContrasenya($request, $response, $args, $this);
});
//$app->get('/sha', function ($request, $response, $args) {
//    $this->dbEloquent;
//    $usuaris = Usuari::all();
//    foreach ($usuaris as $usuari) {
//        $usuari->contrasenya = password_hash('123456789', PASSWORD_DEFAULT);
//        $usuari->save();
//    }
//});
/*
$app->get('/mailing', function ($request, $response, $args) {
    $this->dbEloquent;
    $oferta = Oferta::find(1);
    $nivells = $nivells = NivellIdioma::all();
//      if ($this->mailer->send('/email/validarUsuari.html.twig', [], function($message) {
    //    if ($this->mailer->send('/email/validarEmpresa.html.twig',[], function($message) {
    //    if ($this->mailer->send('/email/instruccionsValidat.html.twig',['usuari'=>'ptj@iespaucasesnoves.cat', 'contrasenya'=>'8acd34df'], function($message) {
    //    if ($this->mailer->send('/email/validarOferta.html.twig',[], function($message) {
    $motius = "L'oferta no és adequada per als estudis als que va dirigida";
    // if ($this->mailer->send('/email/ofertaResultat.html.twig',['validada'=>false, 'oferta'=>$oferta, 'motius'=>$motius], function($message) {
    if ($this->mailer->send('/email/oferta.twig', ['oferta' => $oferta, 'nivells' => $nivells], function ($message) {
        $message->from("borsa.treball@paucasesnovescifp.cat");
        $message->bcc('joan.pons.tugores@gmail.com');
        $message->bcc('ptj@paucasesnovescifp.cat');
        $message->subject('Oferta de treball.');
    })) {
        return $response->withJSON(array('Ok'));
    } else {
        return $response->withJSON(array('KO'), 422);
    }
});

$app->get('/provaMailOferta/{idOferta}', function (Request $request, Response $response, $args) {
    $this->dbEloquent;
    $oferta = Oferta::find($args['idOferta']);
    if (oferta != null) {
        return $this->view->render($response, 'email/oferta.twig', ['oferta' => $oferta]);
    } else {
        return $response->withJSON('Errada: ' . $_SESSION);
    }
});
*/

//  //////////////////////////////////////////////////////////
// //////////////////                       /////////////////
//||||||||||||||||||       Empresa         |||||||||||||||||
// \\\\\\\\\\\\\\\\\\                       \\\\\\\\\\\\\\\\\
//  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
// Entrada Empresa

$app->get('/empresaLogin', function ($request, $response, $args) {
    return $this->view->render($response, 'empresa/indexEmpresa.html.twig', ['tipus' => 20]);
});

//Alta empresa
$app->get('/altaEmpresa', function ($request, $response, $args) {
    $this->dbEloquent;
    $families = Familia::orderBy('nom', 'ASC')->get();
    return $this->view->render($response, 'empresa/altaEmpresa.html.twig', ['families' => $families]);
});

$app->post('/altaEmpresa', function ($request, $response) {
    return DaoEmpresa::altaEmpresa($request, $response, $this);
});

$app->group('/empresa', function () {
    $this->get('/dashBoard', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $empresa = $usuari->getEntitat();
            return $this->view->render($response, 'empresa/dashBoard.html.twig', ['empresa' => $empresa]); //,'contactes'=>$contactes]);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->get('/modificarDades', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $empresa = $usuari->getEntitat();
            return $this->view->render($response, 'empresa/empresaDades.html.twig', ['objEmpresa' => $empresa]);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->put('/modificarDades/{idEmpresa}', function ($request, $response, $args) {
        return DaoEmpresa::modificarEmpresa($request, $response, $args, $this);
    });

    $this->get('/afegirContacte', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $empresa = $usuari->getEntitat();
            return $this->view->render($response, 'empresa/afegirContacte.html.twig', ['objEmpresa' => $empresa]);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->post('/afegirContacte', function ($request, $response, $args) {
        return DaoEmpresa::altaContacte($request, $response, $this);
    });

    $this->put('/modificarContacte/{idContacte}', function ($request, $response, $args) {
        return DaoEmpresa::modificarContacte($request, $response, $args, $this);
    });

    $this->delete('/esborrarContacte/{idContacte}', function ($request, $response, $args) {
        return DaoEmpresa::esborrarContacte($request, $response, $args, $this);
    });

    $this->get('/contactes', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $empresa = $usuari->getEntitat();
            return $this->view->render($response, 'empresa/contactes.html.twig', ['empresa' => $empresa]);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->get('/contactesProves', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $empresa = $usuari->getEntitat();
            return $response->withJson($empresa->contactes);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->get('/canviarContrasenya', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $empresa = $usuari->getEntitat();
            return $this->view->render($response, 'empresa/contrasenya.html.twig', ['empresa' => $empresa, "tipusUsuari" => 20, "usuari" => $usuari]);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->put('/canviarContrasenya/{idusuari}', function ($request, $response, $args) {
        return DaoEmpresa::canviarContrasenya($request, $response, $args, $this);
    });

//Ofertes
    $this->get('/ofertes', function (Request $request, Response $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $empresa = $usuari->getEntitat();
            $nivells = NIvellIdioma::all();
            return $this->view->render($response, 'empresa/ofertes.html.twig', ['empresa' => $empresa, 'nivells' => $nivells]);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->get('/afegirOferta', function (Request $request, Response $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $empresa = $usuari->getEntitat();
            return $this->view->render($response, 'empresa/ofertaAfegir.html.twig', ['empresa' => $empresa]);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->post('/afegirOferta', function (Request $request, Response $response, $args) {
        return DaoEmpresa::altaOferta($request, $response, $this);
    });


    $this->get('/modificarOferta', function (Request $request, Response $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        $oferta = Oferta::find($request->getQueryParam('idOferta'));
        if ($usuari != null && $oferta != null) {
            $empresa = $usuari->getEntitat();
            return $this->view->render($response, 'empresa/ofertaDades.html.twig', ['empresa' => $empresa, 'oferta' => $oferta]);
        } else {
            return $response->withJSON('Errada: ', 404);
        }
    });

    $this->put('/modificarOferta/{idOferta}', function (Request $request, Response $response, $args) {
        return DaoEmpresa::modificarOferta($request, $response, $args, $this);
    });

    $this->get('/estudis', function (Request $request, Response $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        $oferta = Oferta::find($request->getQueryParam('idOferta'));
        if ($usuari != null && $oferta != null) {
            $empresa = $usuari->getEntitat();
            $etiquetes = array("subtitol" => "que han d'haver cursat els alumnes"/* per a l'oferta " . $oferta->idOferta . ' ' . $oferta->titol */, "labelLlista" => "que ha seleccionat", 'correcte' => "L'oferta filtrarà els alumnes per aquests estudis.");
            $estudis = Estudis::where('actiu', 1)->orderBy('codi', 'ASC')->get();
            $families = Familia::orderBy('nom', 'ASC')->get();
            return $this->view->render($response, 'empresa/estudisOferta.html.twig', ['empresa' => $empresa, 'identificador' => $oferta->idOferta, 'entitat' => $oferta, "etiquetes" => $etiquetes, 'estudis' => $estudis, 'families' => $families]);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->post('/estudis', function ($request, $response, $args) {
        return DaoEmpresa::afegirEstudisOferta($request, $response, $this);
    });

    $this->delete('/estudis/{idOferta}/{codiEstudis}', function ($request, $response, $args) {
        return DaoEmpresa::esborrarEstudis($request, $response, $args, $this);
    });

    $this->put('/estudis/{idOferta}/{codiEstudis}', function ($request, $response, $args) {
        return DaoEmpresa::modificarEstudis($request, $response, $args, $this);
    });

    $this->get('/idiomes', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        $oferta = Oferta::find($request->getQueryParam('idOferta'));
        if ($usuari != null && $oferta != null) {
            $empresa = $usuari->getEntitat();
            $etiquetes = array("nom" => $empresa->nom, "labelLlista" => "demanats, nivell mínim");
            $idiomes = Idioma::orderBy('idioma', 'ASC')->get();
            $nivellsIdioma = NivellIdioma::orderBy('idNivellIdioma', 'ASC')->get();
            return $this->view->render($response, 'empresa/idiomes.html.twig', ['actor' => $oferta, 'identificador' => $oferta->idOferta, 'etiquetes' => $etiquetes, 'idiomes' => $idiomes, 'nivellsIdioma' => $nivellsIdioma]);
        } else {
            return $response->withJSON('Errada: ', 500);
        }
    });

    $this->put('/idiomes/{idOferta}', function ($request, $response, $args) {
        return DaoEmpresa::modificarIdiomes($request, $response, $args, $this);
    });

    $this->get('/estatLaboral', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        $oferta = Oferta::find($request->getQueryParam('idOferta'));
        if ($usuari != null && $oferta != null) {
            $empresa = $usuari->getEntitat();
            $etiquetes = array("nom" => $empresa->nom, "labelLlista" => "que han d'acceptar els candidats");
            $estats = EstatLaboral::orderBy('idEstatLaboral', 'ASC')->get();
            return $this->view->render($response, 'empresa/estatLaboral.html.twig', ['empresa' => $empresa, 'actor' => $oferta, 'identificador' => $oferta->idOferta, 'etiquetes' => $etiquetes, 'estats' => $estats]);
        } else {
            return $response->withJSON('Errada: ', 500);
        }
    });

    $this->put('/estatLaboral/{idOferta}', function ($request, $response, $args) {
        return DaoEmpresa::modificarEstatLaboral($request, $response, $args, $this);
    });


    $this->get('/contactesOferta', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        $oferta = Oferta::find($request->getQueryParam('idOferta'));
        if ($usuari != null && $oferta != null) {
            $empresa = $usuari->getEntitat();
            $etiquetes = array("nom" => $empresa->nom, "labelLlista" => "que rebran les respostes dels candidats");
            return $this->view->render($response, 'empresa/contactesOferta.html.twig', ['empresa' => $empresa, 'oferta' => $oferta, 'etiquetes' => $etiquetes]);
            //return $response->withJSON($oferta->estatsLaborals);
        } else {
            return $response->withJSON('Errada: ', 500);
        }
    });
    $this->post('/contactesOferta', function ($request, $response, $args) {
        return DaoEmpresa::afegirContOf($request, $response, $this);
    });

    $this->delete('/contactesOferta/{idOferta}/{idContacte}', function ($request, $response, $args) {
        return DaoEmpresa::esborrarContacteOferta($request, $response, $args, $this);
    });


    $this->delete('/oferta/{idOferta}', function ($request, $response, $args) {
        return DaoEmpresa::esborrarOferta($request, $response, $args, $this);
    });

    $this->get('/ofertaCompleta/{idOferta}', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        $oferta = Oferta::find($args['idOferta']);
        $recompte = $request->getQueryParam('recompte');
        if ($usuari != null && $oferta != null) {
            $empresa = $usuari->getEntitat();
            $nivellsIdioma = NivellIdioma::orderBy('idNivellIdioma', 'ASC')->get();
            $recompte = Dao::comptarCandidats($oferta, $this);
            $etiquetes = array("nom" => $empresa->nom, "labelLlista" => "en els que vol que es trobin els candidats");
            return $this->view->render($response, 'auxiliars/ofertaCompleta.html.twig', ['empresa' => $empresa, 'oferta' => $oferta, 'etiquetes' => $etiquetes, 'nivells' => $nivellsIdioma, 'recompte' => $recompte]);
            //return $response->withJSON($oferta->estatsLaborals);
        } else {
            return $response->withJSON('Errada: ', 500);
        }
    });

    $this->put('/publicarOferta/{idOferta}', function ($request, $response, $args) {
        return DaoEmpresa::publicarOferta($request, $response, $args, $this);
    });
})->add(function ($request, $response, $next) {
    if (isset($_SESSION['rols']) && (in_array(20, $_SESSION['rols']) || in_array(40, $_SESSION['rols']))) {
        return $response = $next($request, $response);
    } else {
        return $this->view->render($response, '/auxiliars/noAutoritzat.html.twig')->withStatus(403);
    }
});

//  //////////////////////////////////////////////////////////
// //////////////////                       /////////////////
//||||||||||||||||||       Alumne          |||||||||||||||||
// \\\\\\\\\\\\\\\\\\                       \\\\\\\\\\\\\\\\\
//  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
//
//Entrada Alumnes
$app->get('/alumneLogin', function ($request, $response, $args) {
    return $this->view->render($response, 'alumne/indexAlumne.html.twig', ['tipus' => 30]);
});

//Alta Alumne
$app->get('/altaAlumne', function ($request, $response, $args) {
    $this->dbEloquent;
    $config = Configuracio::find(1);
    $avui = strtotime("now");
    if ($avui >= strtotime($config->inici) && $avui <= strtotime($config->final)) {
        $families = Familia::orderBy('nom', 'ASC')->get();
        $estudis = Estudis::where('actiu', 1)->orderBy('nom', 'ASC')->get();

        return $this->view->render($response, 'alumne/altaAlumne.html.twig', ['families' => $families, 'estudis' => $estudis]);
    } else {
        if ($avui < strtotime($config->inici)) {
            return $this->view->render($response, 'auxiliars/altaAlumneTancada.html.twig', ['config' => $config]);
        } else {
            return $this->view->render($response, 'auxiliars/altaAlumneTancada.html.twig', []);
        }
    }
});

$app->post('/altaAlumne', function ($request, $response) {
    return DaoAlumne::altaAlumne($request, $response, $this);

});


$app->group('/alumne', function () {
    $this->get('/dashBoard', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $alumne = $usuari->getEntitat();
            $nivellsIdioma = NivellIdioma::orderBy('idNivellIdioma', 'ASC')->get();

            return $this->view->render($response, 'alumne/dashBoard.html.twig', ['alumne' => $alumne, 'usuari' => $usuari, 'nivellsIdioma' => $nivellsIdioma]);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->get('/modificarDades', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION['idUsuari']);
        $alumne = $usuari->getEntitat();
        return $this->view->render($response, 'alumne/alumneDades.html.twig', ['alumne' => $alumne]);
    });

    $this->put('/modificarDades/{idAlumne}', function ($request, $response, $args) {
        return DaoAlumne::modificarDades($request, $response, $args, $this);
    });


    $this->get('/canviarContrasenya', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $alumne = $usuari->getEntitat();
            return $this->view->render($response, 'alumne/contrasenya.html.twig', ['alumne' => $alumne, "tipusUsuari" => 30, "usuari" => $usuari]);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->put('/canviarContrasenya/{idusuari}', function ($request, $response, $args) {
        return DaoAlumne::canviarContrasenya($request, $response, $args, $this);
    });

    $this->get('/estudis', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $alumne = $usuari->getEntitat();
            $etiquetes = array("subtitol" => "pels que vols que les empreses et trobin", "labelLlista" => "que has acabat", 'correcte' => "A partir d'ara rebràs notificacions de les ofertes relacionades amb aquests estudis.");
            $families = Familia::orderBy('nom', 'ASC')->get();
            $estudis = Estudis::where('actiu', 1)->orderBy('nom', 'ASC')->get();
            return $this->view->render($response, 'alumne/alumneEstudis.html.twig', ['entitat' => $alumne, 'identificador' => $alumne->idAlumne, "etiquetes" => $etiquetes, 'estudis' => $estudis, 'families' => $families]);
        } else {
            return $response->withJSON('Errada: ', 500);
        }
    });

    $this->post('/estudis', function ($request, $response, $args) {
        return DaoAlumne::afegirEstudis($request, $response, $this);
    });

    $this->delete('/estudis/{idAlumne}/{codiEstudis}', function ($request, $response, $args) {
        return DaoAlumne::esborrarEstudis($request, $response, $args, $this);
    });

    $this->put('/estudis/{idAlumne}/{codiEstudis}', function ($request, $response, $args) {
        return DaoAlumne::modificarEstudis($request, $response, $args, $this);
    });

    $this->get('/estudis/{IdAlumne}', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $alumne = $usuari->getEntitat();
            return $response->withJSON($alumne->estudis);
        } else {
            return $response->withJSON('Errada: ', 500);
        }
    });

    $this->get('/idiomes', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $alumne = $usuari->getEntitat();
            $etiquetes = array("subtitol" => "pels que vols que les empreses et trobin", "labelLlista" => "que parles");
            $idiomes = Idioma::orderBy('idioma', 'ASC')->get();
            $nivellsIdioma = NivellIdioma::orderBy('idNivellIdioma', 'ASC')->get();
            return $this->view->render($response, 'alumne/idiomes.html.twig', ['actor' => $alumne, 'identificador' => $alumne->idAlumne, 'etiquetes' => $etiquetes, 'idiomes' => $idiomes, 'nivellsIdioma' => $nivellsIdioma]);
        } else {
            return $response->withJSON('Errada: ', 500);
        }
    });

    $this->put('/idiomes/{idAlumne}', function ($request, $response, $args) {
        return DaoAlumne::modificarIdiomes($request, $response, $args, $this);
    });

    $this->get('/idiomes/{idAlumne}', function ($request, $response, $args) {
        $this->dbEloquent;
        $alumne = Alumne::find($args["idAlumne"]);
        if ($alumne != null) {

            return $response->withJSON($alumne->idiomes);
        } else {
            return $response->withJSON('Errada: ', 500);
        }
    });


    $this->get('/estatLaboral', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $alumne = $usuari->getEntitat();
            $etiquetes = null; //array("subtitol" => "pels que vols que les empreses et trobin", "labelLlista" => "que has acabat");
            $estatsLaborals = EstatLaboral::orderBy('nomEstatLaboral', 'ASC')->get();
            return $this->view->render($response, 'alumne/estatLaboral.html.twig', ['actor' => $alumne, 'estats' => $estatsLaborals, 'identificador' => $alumne->idAlumne]);
        } else {
            return $response->withJSON('Errada: ', 500);
        }
    });

    $this->put('/estatLaboral/{idAlumne}', function ($request, $response, $args) {
        return DaoAlumne::modificarEstatLaboral($request, $response, $args, $this);
    });

    $this->get('/ofertes', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $alumne = $usuari->getEntitat();
            $etiquetes = null; //array("subtitol" => "pels que vols que les empreses et trobin", "labelLlista" => "que has acabat");
            return $this->view->render($response, 'alumne/ofertes.html.twig', ['actor' => $alumne]);
        } else {
            return $response->withJSON('Errada: ', 500);
        }
    });


    $this->get('/empreses', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $alumne = $usuari->getEntitat();
            $dades = $request->getQueryParams();

            if (array_key_exists('codiEstudis', $dades)) {
                $empreses = DB::select('SELECT distinct em.* from Ofertes_has_Estudis oe inner join Ofertes on oe.Ofertes_idOferta=idOferta inner join Empreses em on em.idEmpresa=Ofertes.Empreses_idEmpresa where  em.activa=1 and Ofertes.validada=1 and oe.Estudis_codi=\'' . filter_var($dades['codiEstudis'], FILTER_SANITIZE_STRING) . '\'');
                $codiEstudis = filter_var($dades['codiEstudis'], FILTER_SANITIZE_STRING);
            } else {
                $empreses = null;
                $codiEstudis = null;
            }
            return $this->view->render($response, 'alumne/empreses.html.twig', ['actor' => $alumne, 'codiEstudis' => $codiEstudis, 'empreses' => $empreses]);
        } else {
            return $response->withJSON('Errada: ', 500);
        }
    });
})->add(function ($request, $response, $next) {
    if (isset($_SESSION['rols']) && (in_array(30, $_SESSION['rols']) || in_array(40, $_SESSION['rols']))) {
        return $response = $next($request, $response);
    } else {
        return $this->view->render($response, '/auxiliars/noAutoritzat.html.twig')->withStatus(403);
    }
});

//  //////////////////////////////////////////////////////////
// //////////////////                       /////////////////
//||||||||||||||||||       Professor       |||||||||||||||||
// \\\\\\\\\\\\\\\\\\                       \\\\\\\\\\\\\\\\\
//  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
//
//Entrada Professors
$app->get('/professorLogin', function ($request, $response, $args) {
    return $this->view->render($response, 'professor/indexProfessor.html.twig', ['tipus' => 10]);
});

//Alta Professor
$app->get('/altaProfessor', function ($request, $response, $args) {
    return $this->view->render($response, 'professor/altaProfessor.html.twig');
});

$app->post('/altaProfessor', function ($request, $response) {
    return DaoProfessor::altaProfessor($request, $response, $this);
});

$app->group('/professor', function () {
    $this->get('/dashBoard', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $professor = $usuari->getEntitat();
            $ofertes = array();
            $empreses = array();
            $alumnes = array();

            $alumnesPendents = Alumne::where('validat', 0)->orderBy('llinatges', 'ASC')->orderBy('nom', 'ASC')->get();
            $empresesPendents = Empresa::where('validada', 0)->where('rebuig', null)->orderBy('DataAlta', 'ASC')->orderBy('Nom', 'ASC')->get();

            foreach ($professor->estudis as $estudis) {
                foreach ($estudis->ofertes as $oferta) {
                    if ($oferta->dataPublicacio != null && $oferta->validada == 0 && !in_array($oferta, $ofertes)) {
                        $ofertes[] = $oferta;
                    }
                }
                foreach ($alumnesPendents as $alumne) {
                    if ($alumne->estudisAlta == $estudis->codi) {
                        $alumnes[] = $alumne;
                    }
                }

                foreach ($empresesPendents as $empresa) {
                    if ($empresa->familia == $estudis->familia && !in_array($empresa, $empreses)) {
                        $empreses[] = $empresa;
                    }
                }
            }


            $companys = null;
            if ($usuari->teRol(40)) {
                $companys = Professor::where('validat', 0)->orderBy('llinatges', 'ASC')->orderBy('nom', 'ASC')->get();
            }
            return $this->view->render($response, 'professor/dashBoard.html.twig', ['professor' => $professor, 'usuari' => $usuari, 'empreses' => $empreses, 'companys' => $companys, 'ofertes' => $ofertes, 'alumnes' => $alumnes]);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->get('/modificarDades', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $prof = $usuari->getEntitat();
            return $this->view->render($response, 'professor/professorDades.html.twig', ['professor' => $prof]);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->put('/modificarDades/{idProfessor}', function ($request, $response, $args) {
        return DaoProfessor::modificarProfessor($request, $response, $args, $this);
    });

    $this->get('/canviarContrasenya', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $professor = $usuari->getEntitat();
            return $this->view->render($response, 'professor/contrasenya.html.twig', ['professor' => $professor, "tipusUsuari" => 10, "usuari" => $usuari]);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->put('/canviarContrasenya/{idusuari}', function ($request, $response, $args) {
        return DaoProfessor::canviarContrasenya($request, $response, $args, $this);
    });

    $this->get('/estudis', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $professor = $usuari->getEntitat();
            $etiquetes = array("subtitol" => "dels que haurà de validar ofertes", "labelLlista" => "dels que és responsable");
            $estudis = Estudis::where('actiu', 1)->orderBy('Nom', 'ASC')->get();
            $families = Familia::orderBy('nom', 'ASC')->get();

            return $this->view->render($response, 'professor/professorEstudis.html.twig', ['professor' => $professor, "etiquetes" => $etiquetes, 'estudis' => $estudis, 'families' => $families]);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->post('/estudis', function ($request, $response, $args) {
        return DaoProfessor::afegirEstudis($request, $response, $this);
    });

    $this->delete('/estudis/{idProfessor}/{codiEstudis}', function ($request, $response, $args) {
        return DaoProfessor::esborrarEstudis($request, $response, $args, $this);
    });

    $this->get('/estudisProfessor/{id}', function (Request $request, Response $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $prof = $usuari->getEntitat();
            $estudis = $prof->estudis;
            return $response->withJSON($estudis);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });


    $this->get('/ofertes', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $professor = $usuari->getEntitat();
            $etiquetes = array("subtitol" => "dels que haurà de validar ofertes", "labelLlista" => "dels que és responsable");
            $ofertes = array();
            foreach ($professor->estudis as $estudis) {
                foreach ($estudis->ofertes as $oferta) {
                    if ($oferta->dataPublicacio != null and $oferta->validada == 0) {
                        $ofertes[$oferta->idOferta] = $oferta;
                    }
                }
            }
            $nivells = NivellIdioma::all();
            return $this->view->render($response, 'professor/ofertesPendents.html.twig', ['professor' => $professor, "etiquetes" => $etiquetes, 'ofertes' => $ofertes, 'nivells' => $nivells]);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->get('/ofertesValidades', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $professor = $usuari->getEntitat();
            $etiquetes = array("subtitol" => "dels que haurà de validar ofertes", "labelLlista" => "dels que és responsable");
            $ofertes = array();
            foreach ($professor->estudis as $estudis) {
                foreach ($estudis->ofertes as $oferta) {
                    if ($oferta->dataPublicacio != null and $oferta->validada != 0) {
                        $ofertes[$oferta->idOferta] = $oferta;
                    }
                }
            }
            $nivells = NivellIdioma::all();
            return $this->view->render($response, 'professor/ofertesValidades.html.twig', ['professor' => $professor, "etiquetes" => $etiquetes, 'ofertes' => $ofertes, 'nivells' => $nivells]);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->put('/publicarOferta/{idOferta}', function ($request, $response, $args) {
        return DaoProfessor::publicarOferta($request, $response, $args, $this);
    });

    $this->delete('/publicarOferta/{idOferta}', function ($request, $response, $args) {
        return DaoProfessor::rebutjarOferta($request, $response, $args, $this);
    });

    $this->get('/empresesPendents', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION['idUsuari']);
        $estudis = null;
        $families = null;
        if ($usuari != null) {
            $prof = $usuari->getEntitat();
            $empreses = null;
            if ($usuari->teRol(40)) {
                $empreses = Empresa::where('validada', 0)->where('rebuig', null)->orderBy('dataAlta', 'ASC')->orderBy('nom', 'ASC')->get();
            } else {
                $estudis = $prof->estudis;
                $families = [];
                foreach ($estudis as $e) {
                    $families[] = $e->familia;
                }
                $families = array_unique($families, SORT_STRING);
                $empreses = Empresa::whereIn('familia', $families)->where('validada', 0)->where('rebuig', null)->orderBy('dataAlta', 'ASC')->orderBy('nom', 'ASC')->get();
            }
            return $this->view->render($response, 'professor/empresesPendents.html.twig', ['professor' => $prof, 'usuari' => $usuari, 'empreses' => $empreses, 'families' => $families, 'estudis' => $estudis]);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->get('/empresesValidades', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION['idUsuari']);
        $estudis = null;
        $families = null;
        if ($usuari != null) {
            $prof = $usuari->getEntitat();
            $empreses = null;
            if ($usuari->teRol(40)) {
                $empreses = Empresa::where('validada', '!=', 0)->orderBy('nom', 'ASC')->get();
            } else {
                $estudis = $prof->estudis;
                $families = [];
                foreach ($estudis as $e) {
                    $families[] = $e->familia;
                }
                $families = array_unique($families, SORT_STRING);
                $empreses = Empresa::whereIn('familia', $families)->where('validada', '!=', 0)->orderBy('nom', 'ASC')->get();
            }
            return $this->view->render($response, 'professor/empresesValidades.html.twig', ['professor' => $prof, 'usuari' => $usuari, 'empreses' => $empreses, 'families' => $families, 'estudis' => $estudis]);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->put('/empreses/{idEmpresa}', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION['idUsuari']);
        $prof = $usuari->getEntitat();
        return DaoEmpresa::validar($request, $response, $args, $this, $prof);
    });

    $this->get("/alumnesPendents", function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $professor = $usuari->getEntitat();
            $alumnes = array();

            $alumnesPendents = Alumne::where('validat', 0)->orderBy('llinatges', 'ASC')->orderBy('nom', 'ASC')->get();

            foreach ($professor->estudis as $estudis) {
                foreach ($alumnesPendents as $alumne) {
                    if ($alumne->estudisAlta == $estudis->codi) {
                        $alumnes[] = $alumne;
                    }
                }

            }

            return $this->view->render($response, 'professor/alumnesPendents.html.twig', ['professor' => $professor, 'usuari' => $usuari, 'alumnes' => $alumnes]);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->get("/llistaAlumnesValidats", function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $professor = $usuari->getEntitat();
            $alumnes = array();

            $alumnesPendents = Alumne::where('validat', 1)->orWhere('validat', 2)->orderBy('llinatges', 'ASC')->orderBy('nom', 'ASC')->get();

            foreach ($professor->estudis as $estudis) {
                foreach ($alumnesPendents as $alumne) {
                    if ($alumne->estudisAlta == $estudis->codi) {
                        $alumnes[] = $alumne;
                    }
                }

            }

            return $this->view->render($response, 'professor/alumnesValidats.html.twig', ['professor' => $professor, 'usuari' => $usuari, 'alumnes' => $alumnes]);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->put("/alumnesPendents", function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $professor = $usuari->getEntitat();
            return DaoAlumne::validarAlumnes($request, $response, $args, $this, $professor);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->get('/candidats/{idOferta}', function (Request $request, Response $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $oferta = Oferta::find(filter_var($args['idOferta'], FILTER_SANITIZE_NUMBER_INT));
            return $response->withJson(Dao::alumnesOfertaComplets($oferta, $this));
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });


})->add(function ($request, $response, $next) {
    if (isset($_SESSION['rols']) && (in_array(10, $_SESSION['rols']) || in_array(40, $_SESSION['rols']))) {
        return $response = $next($request, $response);
    } else {
        return $this->view->render($response, '/auxiliars/noAutoritzat.html.twig')->withStatus(403);
    }
});

//  //////////////////////////////////////////////////////////
// //////////////////                       /////////////////
//||||||||||||||||||       Administrador   |||||||||||||||||
// \\\\\\\\\\\\\\\\\\                       \\\\\\\\\\\\\\\\\
//  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

$app->group('/administrador', function () {
    $this->get('/usuarisPendents', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $prof = $usuari->getEntitat();
            $companys = null;
            if ($usuari->teRol(40)) {
                $companys = Professor::where('validat', 0)->orderBy('llinatges', 'ASC')->orderBy('nom', 'ASC')->get();
            }
            return $this->view->render($response, 'professor/usuarisPendents.html.twig', ['professor' => $prof, 'companys' => $companys]);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->get('/usuaris/{id}', function (Request $request, Response $response, $args) {
        $this->dbEloquent;
        return $response->withJSON(Professor::find($args['id']));
    });

    $this->put('/usuaris/{idProfessor}', function ($request, $response, $args) {
        return DaoProfessor::activar($request, $response, $args, $this);
    });

    $this->get('/usuaris', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $prof = $usuari->getEntitat();
            $companys = Professor::orderBy('llinatges', 'ASC')->orderBy('nom', 'ASC')->get();
            return $this->view->render($response, 'professor/usuaris.html.twig', ['professor' => $prof, 'companys' => $companys]);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->get('/administrador', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $prof = $usuari->getEntitat();
            $companys = Professor::where('validat', 1)->where('actiu', 1)->orderBy('llinatges', 'ASC')->orderBy('nom', 'ASC')->get();
            return $this->view->render($response, 'professor/administrador.html.twig', ['professor' => $prof, 'companys' => $companys]);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->get('/logs', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION["idUsuari"]);
        if ($usuari != null) {
            $prof = $usuari->getEntitat();
            $logs=file('./sgol/borsa.log');
            return $this->view->render($response, 'professor/logs.html.twig', ['professor' => $prof, 'logs' => $logs]);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });


    $this->get('/rols/{idProfessor}', function (Request $request, Response $response, $args) {
        return DaoProfessor::rols($request, $response, $args, $this);
    });

    $this->put('/rols/{idProfessor}', function (Request $request, Response $response, $args) {
        return DaoProfessor::afegirRol($request, $response, $args, $this);
    });

    $this->delete('/rols/{idProfessor}', function (Request $request, Response $response, $args) {
        return DaoProfessor::eliminarRol($request, $response, $args, $this);
    });

    $this->get('/empreses', function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION['idUsuari']);
        if ($usuari != null) {
            $prof = $usuari->getEntitat();
            $empreses = Empresa::orderBy('nom', 'ASC')->get();
            return $this->view->render($response, 'professor/empreses.html.twig', ['professor' => $prof, 'empreses' => $empreses]);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->get('/empresa/{idEmpresa}', function ($request, $response, $args) {
        $this->dbEloquent;
        $empresa = Empresa::find($args['idEmpresa']);
        if ($empresa != null) {
            return $response->withJSON($empresa, 200);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->get("/obrirAlumnes", function ($request, $response, $args) {
        $this->dbEloquent;
        $usuari = Usuari::find($_SESSION['idUsuari']);
        if ($usuari != null) {
            $prof = $usuari->getEntitat();
            $config = Configuracio::find(1);
            return $this->view->render($response, 'professor/obrirAlumnes.html.twig', ['config' => $config, 'professor' => $prof]);
        } else {
            return $response->withJSON('Errada: ' . $_SESSION);
        }
    });

    $this->put("/obrirAlumnes/{idConfig}", function ($request, $response, $args) {
        return DaoProfessor::obrirAlumnes($request, $response, $args, $this);
    });

})->add(function ($request, $response, $next) {
    if (isset($_SESSION['rols']) && (in_array(40, $_SESSION['rols']))) {
        return $response = $next($request, $response);
    } else {
        return $this->view->render($response, '/auxiliars/noAutoritzat.html.twig')->withStatus(403);
    }
});


$app->get('/estudis', function (Request $request, Response $response) {
    $this->dbEloquent;
    return $this->view->render($response, 'estudis.html.twig', ['estudis' => Estudis::All()]);
});


//Usuaris
$app->get('/usuari/{id}', function (Request $request, Response $response, $args) {
    $this->dbEloquent;
    return $response->withJSON(Usuari::find($args['id'])->getEntitat());
});

//Final

$app->run();


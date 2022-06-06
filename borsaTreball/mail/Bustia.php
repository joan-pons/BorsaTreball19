<?php


namespace Correu;


use Borsa\Destinatari as Destinatari;
use Borsa\Email as Email;

class Bustia
{
    public static function enviar($emails, $subject, $plantilla, $dades, $container, $diferit = false)
    {
//        if ($diferit) {
//            $resultat = self::afegirEmail($emails, $subject, $plantilla, $dades, $container);
//            $resultat = $resultat . "\nDesprés d'afegirEmail";
//        } else {
//            return $container->mailer->send($plantilla, $dades, function ($message) use ($emails, $subject) {
//
//                $message->clearAll();
//                $message->from("borsa.treball@paucasesnovescifp.cat");
//                foreach ($emails as $adreca) {
//                    $message->bcc($adreca->email);
//                }
//
//                $message->subject($subject);
//            });
//        }
        return true;
    }

//TODO: Els mètodes enviar i enviarÚnic han de insertar els missatges i els destinataris a la base de dades. Així no s'haurà de tocar més codi.
    public
    static function enviarUnic($destinatari, $subject, $plantilla, $dades, $container, $diferit = false)
    {
//        if ($diferit) {
//            $destinataris = array();
//            $destinataris[] = $destinatari;
//            $resultat = self::afegirEmail($destinataris, $subject, $plantilla, $dades, $container);
////            $resultat = $resultat . "\nDesprés d'afegirEmail";
//        } else {
//            $resultat = false;
//            try {
//                $resultat = $container->mailer->send($plantilla, $dades, function ($message) use ($destinatari, $subject) {
//                    $message->clearAll();
//                    $message->from("borsa.treball@paucasesnovescifp.cat");
//                    $message->to($destinatari);
//                    $message->subject($subject);
//                });
//            } catch (phpmailerException $ex) {
//                $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
//                $resultat = $resultat . $ex->errorMessage();
//            } catch (Exception $ex) {
//                $container->logger->addError($ex->getcode() . ' ' . $ex->getMessage());
//                $resultat = $resultat . $ex->errorMessage();
//            }
//        }
//        return $resultat;
        return true;
    }

    public
    static function afegirEmail($destinataris, $assumpte, $plantilla, $dades, $container)
    {
        try {
            $text = $container->view->fetch($plantilla, $dades);
            $container->dbEloquent;
            $email = new Email();
            $email->assumpte = $assumpte;
            $email->textEmail = $text;
//        foreach ($destinataris as $dest) {
//            $email->destinataris->concat($destinataris);
//        }
            $guardat = $email->save();
            $identificador = $email->idEmail;

            foreach ($destinataris as $dest) {
//                $resultat = $resultat . $dest . ",";
                $destinatari = new Destinatari();
                $destinatari->idEmail = $identificador;
                $destinatari->adreca = $dest;
                $destinatari->idDestinataris = $destinatari->idEmail . " " . $destinatari->adreca;
                $destinatari->save();
            }
            return true;
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
                    $missatge = array("missatge" => "El missatge no s'ha pogut guardar a la base de dades.", 'info' => $ex->getcode() . ' ' . $ex->getMessage());
                    break;
            }
            return false;
        }
    }

}
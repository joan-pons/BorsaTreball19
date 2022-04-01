<?php


namespace Correu;


use Borsa\Destinatari as Destinatari;
use Borsa\Email as Email;
use Illuminate\Database\Capsule\Manager as DB;
use Mosquitto\Exception;

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
    }

    //TODO: Els mètodes enviar i enviarÚnic han de insertar els missatges i els destinataris a la base de dades. Així no s'haurà de tocar més codi.
    public static function enviarUnic($destinatari, $subject, $plantilla, $dades, $container, $diferit = true)
    {
//        if ($diferit) {
//            $destinataris = array();
//            $destinataris[] = $destinatari;
//            $resultat = self::afegirEmail($destinataris, $subject, $plantilla, $dades, $container);
//            $resultat = $resultat . "\nDesprés d'afegirEmail";
//        } else {
//            $resultat = false;
//            try {
//                $resultat = $container->mailer->send($plantilla, $dades, function ($message) use ($destinatari, $subject) {
//                    $message->clearAll();
//                    $message->from("borsa.treball@paucasesnovescifp.cat");
//                    $message->to($destinatari);
//                    $message->subject($subject);
//                });
//            } catch (phpmailerException $e) {
//                $resultat = $resultat . $e->errorMessage();
//            } catch (Exception $e) {
//                $resultat = $resultat . $e->errorMessage();
//            }
//        }
//        return $resultat;
    }

    public static function afegirEmail($destinataris, $assumpte, $plantilla, $dades, $container)
    {
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
        $resultat = "[";
        foreach ($destinataris as $dest) {
            $resultat = $resultat . $dest . ",";
            $destinatari = new Destinatari();
            $destinatari->idEmail = $identificador;
            $destinatari->adreca = $dest;
            $destinatari->idDestinataris = $destinatari->idEmail . " " . $destinatari->adreca;
            $destinatari->save();
        }
        return $resultat . "]";
//        return $identificador;
    }

}
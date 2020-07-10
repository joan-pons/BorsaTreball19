<?php


namespace Correu;


use Borsa\Professor;

class Bustia
{
    public static function enviar($emails, $subject, $plantilla, $dades, $container)
    {
        return $container->mailer->send($plantilla, $dades, function ($message) use ($emails, $subject) {
            $message->from("borsa.treball@paucasesnovescifp.cat");
            foreach ($emails as $adreca){
                $message->bcc($adreca->email);
            }

            $message->subject($subject);
        });
    }

    public static function enviarUnic($destinatari, $subject, $plantilla, $dades, $container)
    {
        $resultat=false;
        try {
            $resultat = $container->mailer->send($plantilla, $dades, function ($message) use ($destinatari, $subject) {
                $message->from("borsa.treball@paucasesnovescifp.cat");
                $message->to($destinatari);
                $message->subject($subject);
            });
        }catch (phpmailerException $e) {
            $resultat=$e->errorMessage();
        }catch(Exception $e){
            $resultat=$e->errorMessage();
        }
        return $resultat;
    }
}
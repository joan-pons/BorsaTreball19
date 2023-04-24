<?php


namespace Borsa;
use Illuminate\Database\Eloquent\Model as Model;

class Destinatari extends Model
{

    protected $table = 'Destinataris';
    protected $primaryKey = "idDestinataris";
    public $timestamps = false;

}
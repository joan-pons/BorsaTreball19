<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Borsa;

use Illuminate\Database\Eloquent\Model as Model;

//use \Borsa\EstatLaboral as EstatLaboral;

/**
 * Description of Email
 *
 * @author joan
 */
class Email extends Model
{

    protected $table = 'Emails';
    protected $primaryKey = "idEmail";
    public $timestamps = false;


    public function destinataris()
    {
        return $this->hasMany("Borsa\Destinatari", 'idEmail');
    }

}

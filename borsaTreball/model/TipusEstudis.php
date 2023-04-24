<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Borsa;

use \Illuminate\Database\Eloquent\Model as Model;

/**
 * Description of TipusEstudis
 *
 * @author joan
 */
class TipusEstudis extends Model {

    protected $table = 'TipusEstudis';
    protected $primaryKey = "idTipus";
    public $timestamps = false;
    public $incrementing = false; //Si no, tracta la clau primaria com a integer

}

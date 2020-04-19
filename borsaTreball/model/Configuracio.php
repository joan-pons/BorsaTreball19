<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Borsa;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Description of Token
 *
 * @author joan
 */
class Configuracio extends Model
{
    protected $table = 'Configuracio';
    protected $primaryKey = "idConf";
    public $timestamps = false;
}

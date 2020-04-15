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
class Token extends Model
{

    protected $table = 'Tokens';
    protected $primaryKey = "token";
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

}

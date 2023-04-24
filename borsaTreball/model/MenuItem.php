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
 * Description of Alumne
 *
 * @author joan
 */
class MenuItem extends Model
{

    protected $table = 'MenuItems';
    protected $primaryKey = "idItems";
    public $timestamps = false;


}

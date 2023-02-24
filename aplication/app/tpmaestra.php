<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tpmaestra extends Model
{
    //
    protected $table='tpmaestra';
    protected $primaryKey='barra';
    public $incrementing=false;
    public $timestamps=false;
    protected $fillable = [
    	'barra', 
    	'desprod', 
    	'iva', 
    	'bulto', 
    	'marca', 
    	'tipo', 
    	'regulado', 
    	'nomprov', 
    	'provdat', 
    	'metadata',
    	'consolidado', 
    	'actualizado', 
    	'bandera'
    ];

    protected $guarded =[
    	
    ];
}

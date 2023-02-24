<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Maelicencias extends Model
{
    protected $table='maelicencias';

    protected $primaryKey='id';

    public $timestamps=false;
    
    protected $fillable = [
        'id',
        'cod_lic', 
        'estado', 
        'fec_reg', 
        'fec_act', 
    ];

    protected $guarded =[
    	
    ];
}

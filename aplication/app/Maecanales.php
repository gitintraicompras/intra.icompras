<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Maecanales extends Model
{
    protected $table='maecanales';

    protected $primaryKey='id';

    public $timestamps=false;
    
    protected $fillable = [
        'codcanal', 
        'descrip', 
        'fecha', 
        'estado', 
        'rif', 
        'direccion', 
        'telefono', 
        'contacto', 
        'correo', 
        'zona', 
        'opc1', 
        'opc2', 
        'opc3', 
        'opc4', 
        'opc5'
    ];

    protected $guarded =[
    	
    ];
}

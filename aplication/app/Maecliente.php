<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Maecliente extends Model
{
    protected $table='maecliente';

    protected $primaryKey='codcli';

    public $incrementing=false;

    public $timestamps=false;
    
    protected $fillable = [
        'codcli', 
        'nombre', 
        'rif', 
        'direccion', 
        'telefono', 
        'contacto', 
        'usuario', 
        'clave', 
        'zona', 
        'fecha', 
        'estado', 
        'campo1', 
        'campo2', 
        'campo3', 
        'campo4',
        'campo5', 
        'campo6', 
        'campo7', 
        'campo8', 
        'campo9', 
        'campo10'
    ];

    protected $guarded =[
    	
    ];
}

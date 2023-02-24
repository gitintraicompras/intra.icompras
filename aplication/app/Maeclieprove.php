<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Maeclieprove extends Model
{
    protected $table='maeclieprove';

    protected $primaryKey = array('codcli', 'codprove');

    public $incrementing=false;

    public $timestamps=false;
    
    protected $fillable = [
        "codcli", 
        "codprove", 
        "codigo", 
        "ruta", 
        "dcredito", 
        "mcredito", 
        "corte", 
        "dcme", 
        "dcmer", 
        "dcmi", 
        "dcmir",
        "ppme", 
        "ppmi", 
        "di", 
        "dotro", 
        "origen", 
        "usuario", 
        "clave", 
        "status"
    ];

    protected $guarded =[
    	
    ];
}

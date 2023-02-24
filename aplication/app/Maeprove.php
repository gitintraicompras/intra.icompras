<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Maeprove extends Model
{
    protected $table='maeprove';

    protected $primaryKey='codprove';

    public $incrementing=false;

    public $timestamps=false;
    
    protected $fillable = [
        'codprove', 
        'descripcion', 
        'backcolor', 
        'forecolor', 
        'rutalogo1', 
        'rutalogo2', 
        'status', 
        'localfile', 
        'serverfile',
        'ftpserver', 
        'ftpuser', 
        'ftppass', 
        'ftppasv', 
        'modoconexion', 
        'fechasinc', 
        'fechacata', 
        'tipocata'
    ];

    protected $guarded =[
    	
    ];
}

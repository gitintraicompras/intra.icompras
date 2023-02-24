<?php

/**************************************************************
* Programa   : ProcPedidos.php
* Detalles   : Procesa los pedidos Automaticos y los envia  
*            : todos los dias a las 6PM
*            : - Considera los productos q esten marcados como cendis
* Proyecto   : - y deben de tener Valores de dias MIN y MAS por producto
***************************************************************
* Realizado  : Ing. Mauricio Blanco
* Empresa    : ISB SISTEMAS, C.A.
* Fecha      : 13-04-2023
* Modificado : 13-04-2023
***************************************************************/

namespace App\Console\Commands;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Console\Command;
use App\User;
use DB;


class ProcPedidos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
  
    protected $signature = 'ProcPedidos:auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void 
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        GenerarSugeridoAutomatico();
    }
}



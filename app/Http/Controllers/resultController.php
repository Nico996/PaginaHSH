<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subasta;
use App\HotSale;
use Carbon\Carbon;

class resultController extends Controller
{
    public function index(){
      $data = request()->all();
      if ($data['fecha_reserva1'] != NULL) {

        $fecha = Carbon::createFromFormat('d/m/Y', $data['fecha_reserva1'])->addMonth(2);
}
      if ($data['fecha_reserva2'] != NULL){

        $fecha2 = Carbon::createFromFormat('d/m/Y', $data['fecha_reserva2']);

      if ($data['fecha_reserva1'] > $data['fecha_reserva2']){
        return redirect()->route('home')->withErrors('La fecha de inicio debe ser menor a la de fin');
      }
}
      if ($data['fecha_reserva1'] != NULL and $data['fecha_reserva2'] != NULL and $fecha2->gte($fecha)){
          return redirect()->route('home')->withErrors('La diferencia entre fechas debe ser menor a 2 meses');

      }


      $title = "HSH - Resultados de Busqueda";
    	return view('resultView', compact('title','data'));
    }

    public function listarSubasta(){
      $title = "HSH - Listado de Subastas";
      $resultado = Subasta::all();
      return view('lisSub', compact('title','resultado'));
    }

    public function listarHotSale(){
      $title = "HSH - Listado de HotSales";
      $resultado = HotSale::all();
      return view('lisHS', compact('title','resultado'));
    }
}

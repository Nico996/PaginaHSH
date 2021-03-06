<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subasta;
use App\HotSale;
use Carbon\Carbon;
use App\Residencia;
use App\Reserva;

class resultController extends Controller
{


    public function listarSubasta(){
      $title = "HSH - Listado de Subastas";
      // $subastas_programadas = Subasta::select()->where('activa',1)->get();
      $subastas_activas = Subasta::all();
      foreach ($subastas_activas as $key => $sub) {
        if(!($sub->activa())){
          unset($subastas_activas[$key]);
        }
      }
      // $subastas_programadas = Subasta::select()->where('finalizada',0)->where('activa',0)->get();
      $subastas_programadas = Subasta::all();
      foreach ($subastas_programadas as $key => $sub) {
        if(!($sub->programada())){
          unset($subastas_programadas[$key]);
        }
      }
      // $subastas_finalizadas = Subasta::select()->where('finalizada',1)->get();
      $subastas_finalizadas = Subasta::all();
      foreach ($subastas_finalizadas as $key => $sub) {
        if(!($sub->finalizada())){
          unset($subastas_finalizadas[$key]);
        }
      }
      return view('lisSub', compact('title','subastas_activas','subastas_programadas','subastas_finalizadas'));
    }

    public function listarHotSale(){
      $title = "HSH - Listado de HotSales";
      // $hotsales_activas = HotSale::select()->where('activa',1)->get();
      // $hotsales_finalizadas = HotSale::select()->where('finalizada',1)->get();
      $hotsales_activas = HotSale::all();
      $hotsales_finalizadas = HotSale::all();
      foreach ($hotsales_activas as $key => $hotsale) {
        if(!($hotsale->activa())){
          unset($hotsales_activas[$key]);
        }
        else{
          unset($hotsales_finalizadas[$key]);
        }
      }
      return view('lisHS', compact('title','hotsales_activas','hotsales_finalizadas'));
    }
//
//DESDE ACÁ, ESTA EL BUSCADOR
//
    public function index(){
      $subastas_activas=NULL;
      $subastas_programadas=NULL;
      $resultado2=NULL;
      $resultado3=NULL;



      $data = request()->all();
      if ($data['fecha_reserva1'] != NULL) {

        $fecha = Carbon::createFromFormat('d/m/Y', $data['fecha_reserva1'])->addMonth(2);
      }
      if ($data['fecha_reserva2'] != NULL) {

        $fecha2 = Carbon::createFromFormat('d/m/Y', $data['fecha_reserva2']);

      }
      if ((($data['fecha_reserva1'] != NULL) and ($data['fecha_reserva2'] != NULL) and ($fecha2->gte($fecha))) or (($data['fecha_reserva1'] == NULL) and ($data['fecha_reserva2'] != NULL) and ($fecha2->gte(Carbon::now()->addMonth(2))))){
        return redirect()->route('home')->withErrors('La diferencia entre fechas debe ser menor a 2 meses');

      }
      if (isset($fecha2)) {$fecha2->addMonth(2);}
      if (isset($fecha) and isset($fecha2) and $fecha->gt($fecha2)){
        return redirect()->route('home')->withErrors('La fecha de inicio debe ser menor a la de fin');
      }
      $title = "HSH - Resultados de Busqueda";
        if (($data['search'] != NULL) and ($data['ubicacion'] != NULL) and ((($data['fecha_reserva1']) !=NULL) or ($data['fecha_reserva2'] != NULL))) {
          $accion =1;
        }
        else{
          if (($data['search'] != NULL) and ($data['ubicacion'] !=NULL)) {
            $accion =2;
          }
          else{
            if (($data['search'] != NULL) and ((($data['fecha_reserva1']) != NULL) or ($data['fecha_reserva2'] != NULL))){
              $accion =3;
            }
            else{
              if(($data['ubicacion'] != NULL) and ((($data['fecha_reserva1']) !=NULL) or ($data['fecha_reserva2'] != NULL))){
                $accion =4;
              }
              else{
                if ($data['search'] !=NULL){
                  $accion=5;
                }
                else{
                  if ($data['ubicacion'] != NULL){
                    $accion=6;
                  }
                  else{
                    if((($data['fecha_reserva1']) !=NULL) or ($data['fecha_reserva2'] != NULL)){
                      $accion=7;
                    }
                    else{
                      $accion=8;
                    }
                  }
                }
              }
            }
          }
        }
        if ($data['fecha_reserva1'] != NULL){
          $data['fecha_reserva1']=Carbon::createFromFormat('d/m/Y',$data['fecha_reserva1'])->format('Y-m-d');
          $dif1=Carbon::createFromFormat('Y-m-d',$data['fecha_reserva1']);
          $carb=Carbon::createFromFormat('Y-m-d',$data['fecha_reserva1'])->addMonth(2)->format('Y-m-d');
          $difweek=8;
        } else {
          $dif1=Carbon::now();
        }
        if ($data['fecha_reserva2'] != NULL){
          $carb=Carbon::create($data['fecha_reserva1'])->addMonth(2)->format('Y-m-d');
          $data['fecha_reserva2']=Carbon::createFromFormat('d/m/Y',$data['fecha_reserva2'])->format('Y-m-d');
          $dif2=Carbon::createFromFormat('Y-m-d',$data['fecha_reserva2']);
        //  $difweek=$dif1->diffInWeeks($dif2);
        }
        else {
          $dif2=Carbon::create($dif1->year,$dif1->month,$dif1->day)->addMonth(2);
        }

        if (($data['fecha_reserva1'] != NULL) or ($data['fecha_reserva2'] != NULL))
          if (((Carbon::now()->addMonth(6)) > ($dif1)) and ((Carbon::now()->addMonth(6)) > ($dif2))){
            $fechainvalida= true;
          }
          else{
            while ($dif1 < Carbon::now()->addMonth(6)) {
              $dif1->addWeek();
            }
            $difweek=$dif1->diffInWeeks($dif2);
            $dif1->format('Y-m-d');
            $dif2->format('Y-m-d');
            $fechainvalida=false;
          }
        switch ($accion) {
          case 1:{
            if (isset($data['subasta'])){

              $subastas_activas = Subasta::select()->join('residencias','residencias.id','=','subastas.residencia_id')
              ->where('residencias.descripcion','like','%'.$data['search'].'%')
              ->where('residencias.ubicacion_id',$data['ubicacion'])
              ->whereBetween('subastas.fecha_reserva', [$data['fecha_reserva1'], $carb])
              ->get();

              $subastas_programadas = Subasta::select()->join('residencias','residencias.id','=','subastas.residencia_id')
              ->where('residencias.descripcion','like','%'.$data['search'].'%')
              ->where('residencias.ubicacion_id',$data['ubicacion'])
              ->whereBetween('subastas.fecha_reserva', [$data['fecha_reserva1'], $carb])
              ->get();
            }
            if (isset($data['residencia'])){
              if (!$fechainvalida){
              $notin=Reserva::select('reservas.residencia_id')
              ->join('residencias','residencias.id','=','reservas.residencia_id')
              ->whereBetween('reservas.fecha', [$dif1, $dif2])
              ->groupBy('reservas.residencia_id')
              ->havingRaw('COUNT(*) = '.++$difweek)
              ->get();

              $resultado2 = Residencia::select('residencias.id','residencias.descripcion','residencias.ubicacion_id','residencias.dada_de_baja')
              ->where('residencias.dada_de_baja',0)
              ->where('residencias.descripcion','like','%'.$data['search'].'%')
              ->where('residencias.ubicacion_id',$data['ubicacion'])
              ->whereNotIn('residencias.id', $notin)
              ->get();
            }
            else{
              $resultado2 = Residencia::select('residencias.id')->where('residencias.id','-1')->get();
            }
            }
            if (isset($data['hot_sale'])){

              $resultado3 = HotSale::select()->join('residencias','residencias.id','=','hotsales.residencia_id')
              ->where('residencias.descripcion','like','%'.$data['search'].'%')
              ->where('residencias.ubicacion_id',$data['ubicacion'])
              ->whereBetween('hotsales.fecha_reserva', [$data['fecha_reserva1'], $carb])
              ->get();
            }
            break;
          }
          case 2:{
            if (isset($data['subasta'])){
              $subastas_activas = Subasta::select()->join('residencias','residencias.id','=','subastas.residencia_id')
              ->where('residencias.descripcion','like','%'.$data['search'].'%')
              ->where('residencias.ubicacion_id',$data['ubicacion'])->get();

              $subastas_programadas = Subasta::select()->join('residencias','residencias.id','=','subastas.residencia_id')
              ->where('residencias.descripcion','like','%'.$data['search'].'%')
              ->where('residencias.ubicacion_id',$data['ubicacion'])->get();
            }
            if (isset($data['residencia'])){
              $resultado2 = Residencia::select('residencias.id','residencias.descripcion','residencias.ubicacion_id','residencias.dada_de_baja')
              ->where('residencias.descripcion','like','%'.$data['search'].'%')
              ->where('residencias.dada_de_baja','false')
              ->where('residencias.ubicacion_id',$data['ubicacion'])->get();
            }
            if (isset($data['hot_sale'])){

                $resultado3 = HotSale::select()->join('residencias','residencias.id','=','hotsales.residencia_id')
                ->where('residencias.descripcion','like','%'.$data['search'].'%')
                ->where('residencias.ubicacion_id',$data['ubicacion'])
                ->get();
              }
              break;
            }
          case 3:{
            if (isset($data['subasta'])){

                $subastas_activas = Subasta::select()->join('residencias','residencias.id','=','subastas.residencia_id')
                ->where('residencias.descripcion','like','%'.$data['search'].'%')
                ->whereBetween('subastas.fecha_reserva', [$data['fecha_reserva1'], $carb])->get();

                $subastas_programadas = Subasta::select()->join('residencias','residencias.id','=','subastas.residencia_id')
                ->where('residencias.descripcion','like','%'.$data['search'].'%')
                ->whereBetween('subastas.fecha_reserva', [$data['fecha_reserva1'], $carb])->get();
              }
            if (isset($data['residencia'])){
              if (!$fechainvalida){
              $notin=Reserva::select('reservas.residencia_id')
              ->join('residencias','residencias.id','=','reservas.residencia_id')
              ->whereBetween('reservas.fecha', [$dif1, $dif2])
              ->groupBy('reservas.residencia_id')
              ->havingRaw('COUNT(*) = '.++$difweek)
              ->get();

              $resultado2 = Residencia::select('residencias.id','residencias.descripcion','residencias.ubicacion_id','residencias.dada_de_baja')
              ->where('residencias.dada_de_baja',0)
              ->where('residencias.descripcion','like','%'.$data['search'].'%')
              ->whereNotIn('residencias.id', $notin)
              ->get();
            }
            else{
              $resultado2 = Residencia::select('residencias.id')->where('residencias.id','-1')->get();
            }
            }
            if (isset($data['hot_sale'])){

                $resultado3 = HotSale::select()->join('residencias','residencias.id','=','hotsales.residencia_id')
                ->where('residencias.descripcion','like','%'.$data['search'].'%')
                ->whereBetween('hotsales.fecha_reserva', [$data['fecha_reserva1'], $carb])
                ->get();
            }
            break;
          }
          case 4:{
            if (isset($data['subasta'])){

                $subastas_activas = Subasta::select()->join('residencias','residencias.id','=','subastas.residencia_id')
                ->where('residencias.ubicacion_id',$data['ubicacion'])
                ->whereBetween('subastas.fecha_reserva', [$data['fecha_reserva1'], $carb])->get();

                $subastas_programadas = Subasta::select()->join('residencias','residencias.id','=','subastas.residencia_id')
                ->where('residencias.ubicacion_id',$data['ubicacion'])
                ->whereBetween('subastas.fecha_reserva', [$data['fecha_reserva1'], $carb])->get();
              }
            if (isset($data['residencia'])){
              if (!$fechainvalida){
                $notin=Reserva::select('reservas.residencia_id')
                ->join('residencias','residencias.id','=','reservas.residencia_id')
                ->whereBetween('reservas.fecha', [$dif1,$dif2])
                ->groupBy('reservas.residencia_id')
                ->havingRaw('COUNT(*) = '.++$difweek)
                ->get();

                $resultado2 = Residencia::select('residencias.id','residencias.descripcion','residencias.ubicacion_id','residencias.dada_de_baja')
                ->where('residencias.dada_de_baja',0)
                ->where('residencias.ubicacion_id',$data['ubicacion'])
                ->whereNotIn('residencias.id', $notin)
                ->get();
              }
              else{
                $resultado2 = Residencia::select('residencias.id')->where('residencias.id','-1')->get();
              }
            }
            if (isset($data['hot_sale'])){

                $resultado3 = HotSale::select()->join('residencias','residencias.id','=','hotsales.residencia_id')
                ->where('residencias.ubicacion_id',$data['ubicacion'])
                ->whereBetween('hotsales.fecha_reserva', [$data['fecha_reserva1'], $carb])
                ->get();
              }
              break;
          }
          case 5:{
            if (isset($data['subasta'])){

              $subastas_activas = Subasta::select()->join('residencias','residencias.id','=','subastas.residencia_id')
              ->where('residencias.descripcion','like','%'.$data['search'].'%')->get();

              $subastas_programadas = Subasta::select()->join('residencias','residencias.id','=','subastas.residencia_id')
              ->where('residencias.descripcion','like','%'.$data['search'].'%')->get();
            }
            if(isset($data['residencia'])){
              $resultado2 = Residencia::select('residencias.id','residencias.descripcion','residencias.ubicacion_id','residencias.dada_de_baja')
              ->where('residencias.descripcion','like','%'.$data['search'].'%')
              ->where('residencias.dada_de_baja',0)->get();
            }
            if (isset($data['hot_sale'])){

              $resultado3 = HotSale::select()->join('residencias','residencias.id','=','hotsales.residencia_id')
              ->where('residencias.descripcion','like','%'.$data['search'].'%')
              ->get();
            }
            break;
          }
          case 6:{
            if (isset($data['subasta'])){
              $subastas_activas = Subasta::select()->join('residencias','residencias.id','=','subastas.residencia_id')
              ->where('residencias.ubicacion_id',$data['ubicacion'])->distinct()->get();

              $subastas_programadas = Subasta::select()->join('residencias','residencias.id','=','subastas.residencia_id')
              ->where('residencias.ubicacion_id',$data['ubicacion'])->distinct()->get();

            }
            if(isset($data['residencia'])){
              $resultado2 = Residencia::select('residencias.id','residencias.descripcion','residencias.ubicacion_id','residencias.dada_de_baja')
              ->where('residencias.ubicacion_id',$data['ubicacion'])
              ->where('residencias.dada_de_baja','false')->get();

            }
            if (isset($data['hot_sale'])){

              $resultado3 = HotSale::select()->join('residencias','residencias.id','=','hotsales.residencia_id')
              ->where('residencias.ubicacion_id',$data['ubicacion'])
              ->get();

            }
            break;
          }
          case 7:{
            if (isset($data['subasta'])){

              $subastas_activas = Subasta::select()->join('residencias','residencias.id','=','subastas.residencia_id')
              ->whereBetween('subastas.fecha_reserva', [$data['fecha_reserva1'], $carb])->get();

              $subastas_programadas = Subasta::select()->join('residencias','residencias.id','=','subastas.residencia_id')
              ->whereBetween('subastas.fecha_reserva', [$data['fecha_reserva1'], $carb])->get();

            }
            if(isset($data['residencia'])){
              if (!$fechainvalida){
              $notin=Reserva::select('reservas.residencia_id')
              ->join('residencias','residencias.id','=','reservas.residencia_id')
              ->whereBetween('reservas.fecha', [$dif1,$dif2])
              ->groupBy('reservas.residencia_id')
              ->havingRaw('COUNT(*) = '.++$difweek)
              ->get();

              $resultado2 = Residencia::select('residencias.id','residencias.descripcion','residencias.ubicacion_id','residencias.dada_de_baja')
              ->where('residencias.dada_de_baja',0)
              ->whereNotIn('residencias.id', $notin)
              ->get();
            }
            else{

              $resultado2 = Residencia::select('residencias.id')->where('residencias.id','-1')->get();
            }
            }
            if (isset($data['hot_sale'])){

              $resultado3 = HotSale::select()->join('residencias','residencias.id','=','hotsales.residencia_id')
              ->whereBetween('hotsales.fecha_reserva', [$data['fecha_reserva1'], $carb])
              ->get();
            }
            break;
          }
          case 8:{
            if (isset($data['subasta'])){
              $subastas_activas = Subasta::select()->join('residencias','residencias.id','=','subastas.residencia_id')->get();

              $subastas_programadas = Subasta::select()->join('residencias','residencias.id','=','subastas.residencia_id')->get();
            }
            if(isset($data['residencia'])){
              $resultado2 = Residencia::select('residencias.id','residencias.descripcion','residencias.ubicacion_id','residencias.dada_de_baja')
              ->where('residencias.dada_de_baja','false')->get();
            }
            if (isset($data['hot_sale'])){

              $resultado3 = HotSale::select()->join('residencias','residencias.id','=','hotsales.residencia_id')
              ->get();
            }
            break;
          }

        }
        if (!(((isset($data['subasta'])) or (isset($data['residencia'])) or isset($data['hot_sale'])))){
          return redirect()->route('home')->withErrors('Seleccione el tipo de busqueda que desea');
        }

      if (isset($subastas_activas)){
        foreach ($subastas_activas as $key => $sub) {
          if(!($sub->activa())){
            unset($subastas_activas[$key]);
          }
        }
      }

      if(isset($subastas_programadas)){
          foreach ($subastas_programadas as $key => $sub) {
            if(!($sub->programada())){
              unset($subastas_programadas[$key]);
            }
          }
      }
      if(isset($resultado3)){
      foreach ($resultado3 as $key => $hotsale) {
        if(!($hotsale->activa())){
          unset($resultado3[$key]);
        }
      }
    }
    if(isset($resultado2)){
      foreach ($resultado2 as $key => $residencia) {
        if(!($residencia->dada_de_baja==0)){
          unset($resultado2[$key]);
        }
      }
    }


    $resultado2fechas = array();
    $i=0;
    if($resultado2 != null) {
      foreach ($resultado2 as $residencia) {
        $resultado2fechas[$i]="Semanas disponibles: <br>";
        $difaux1=Carbon::create($dif1->year,$dif1->month,$dif1->day);
        $difaux2=Carbon::create($dif2->year,$dif2->month,$dif2->day);
        while ($difaux2->gte($difaux1)){
          if ((COUNT(Reserva::select('id')->where('residencia_id',$residencia->id)->where('fecha',$difaux1)->get())) == 0){
            $resultado2fechas[$i]=$resultado2fechas[$i].$difaux1->year."-".$difaux1->month."-".$difaux1->day. "<br>";
          }
          $difaux1->addWeek();
        }
        $i++;

      }
    }

      return view('resultView', compact('title','subastas_activas','subastas_programadas','resultado2','resultado3','resultado2fechas'));
    }
}

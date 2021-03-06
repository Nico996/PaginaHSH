@extends('layout')

@section('mainContent')
<?php




use config\Session;
use App\Residencia;
use App\Ubiacion;
use Carbon\Carbon;


if(Auth::user()->tipo_de_usuario != 1){



if ($errors->any()) {
  foreach ($errors->all() as $error) {
    echo
    '<div class="alert alert-danger alert-dismissible fade show" role="alert">*'
      .$error.
      '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>';
  }
}



 ?>


<section class="jumbotron text-center">
  <div>

  <img src= "/public/imagenes/logocompleto.png" style= "width: 50%; height: 50%; position: relative; left: 140px;"></img>
</div>
    <div class="btn-group" role="group"  style="">
      <p>
        <?php if (Auth::user()->tipo_de_usuario == 0) {  ?>
        <a href= {{ route('crearResidencia') }} class="btn btn-primary my-2">Agregar residencia</a>
        <?php } ?>
        <a href={{ route('listarSubasta') }} class="btn btn-secondary my-2">Listar subastas</a>
        <a href={{ route('listarHotSale') }} class="btn btn-secondary my-2">Listar HotSales</a>
        <?php if ((Auth::user()->tipo_de_usuario == 3) or (Auth::user()->tipo_de_usuario == 0)) {?>
        <a href={{ route('listarResidencias') }} class="btn btn-secondary my-2">Listar residencias</a>
      </p>
    <?php } ?>
    </div>
        <p></p>
        <?php  if (Auth::user()->tipo_de_usuario == 0) {  ?>
          <div class="btn-group" role="group"  style="position: relative; top: -42.5px;">
          <p>
          <a href={{ route('listUsr')}} class="btn btn-primary my-2">Usuarios</a>
          <a href={{ route('listUpgUsr')}} class="btn btn-primary my-2">Solicitudes premium</a>
          <a href= {{ route('crearAdmin') }} class="btn btn-primary my-2">Agregar administrador</a>
          </p>
          </div>
        <?php } ?>
  <p class="lead text-muted">Bienvenido. Aquí abajo le mostramos algunas de nuestras mejores residencias</p>
  </section>


 <div class="album py-5 bg-light">
    <div class="container">
      <div class="row">

<?php





  $mostrar =  Residencia::all()->take(6);
  $imgnodisp = '/public/imagenes/img-nodisponible.jpg';

  foreach ($mostrar as $residencia) {

      $descripcion = $residencia->descripcion;
      $ubicacion = $residencia->ubicacion;
      $foto = $residencia->fotos()->first();
?>

        <div class="col-md-4">
          <div class="card mb-4 shadow-sm">
            <img src= <?php if ($foto != null){ echo '"'; echo $foto->src; echo '"';} else{echo '"'; echo $imgnodisp; echo '"';} ?> style="width: 348px; height: 270px;">
            <div class="card-body">
              <p class="card-text"> <?php echo $descripcion; echo "</br>"; echo $ubicacion->ubicacion; echo ", "; ?> </p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <?php if (Auth::user()->tipo_de_usuario == 3) { ?>
                  <a href="{{ route('viewRes', [$residencia]) }}"><button type="button" class="btn btn-sm btn-outline-secondary">Ver</button></a>
                  <?php } ?>
                  <?php if (Auth::user()->tipo_de_usuario == 0) { ?>
                    <a href="{{ route('editRes', [$residencia]) }}"><button type="button" class="btn btn-sm btn-outline-secondary">Editar</button></a>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>

<?php

 }  //end foreach
}
?>

      </div>
    </div>
  </div>

@endsection

@section('headerContent')

      <div class="row">
        <div class="col-sm-8 col-md-7 py-4">
          <h4 class="text-white">About</h4>
          <p class="text-muted"></p>
        </div>
        <div class="col-sm-4 offset-md-1 py-4">
          <h4 class="text-white">Contáctenos</h4>
          <ul class="list-unstyled">
            <li><a href="#" class="text-white">support@hsh.com</a></li>
            <li><a href="{{ route('sucursales')}}">Sucursales</a></li>
          </ul>
        </div>
      </div>

@endsection

@section('footer')
<?php if(Auth::user()->tipo_de_usuario != 1){
?>
<footer class="text-muted">
  <div class="container">
    <p class="float-right">
      <a href="#">Ir arriba</a>
    </p>
  </div>
</footer>

@endsection


@section('buscador')

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<!-- Datepicker Files -->
<link rel="stylesheet" href="{{'/public/datePicker/css/bootstrap-datepicker3.css'}}">
<link rel="stylesheet" href="{{'/public/datePicker/css/bootstrap-standalone.css'}}">
<script src="{{'/public/datePicker/js/bootstrap-datepicker.js'}}"></script>
<!-- Languaje -->
<script src="{{'/public/datePicker/locales/bootstrap-datepicker.es.min.js'}}"></script>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
* {box-sizing: border-box;}

body {
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
}

.topnav {
  margin: 0;
  padding: 0;
  list-style: none;
  position: relative;
  float: right;
  overflow: hidden;
  background-color: #e9ecef;
}

.topnav .search-container {
  display: inline-block;
  float: right;
  font-size: 16px;
  padding: 1px 10px;
  text-decoration: none;
}

.input {
  padding: 4px;
  margin-top: 4px;
  font-size: 14px;
  border:none;
  width: -moz-available;
}

.boton {
  width: -moz-available;
  float: none;
 }

.checkbox {
  text-align: center;
  margin-top: 10px;
}

.topnav .search-container button {
  float: right;
  padding: 6px 10px;
  margin-top: 8px;
  background: #ddd;
  font-size: 17px;
  border: none;
  cursor: pointer;
}


</style>
</head>

<!¡¡PELIGRO, DEBAJO DE ÉSTA LINEA ESTÁ EL BUSCADOR. NO SE RECOMIENDA TOCAR A NO SER QUE SEAS MANKIWI, ESTÁS BAJO AVISO!!>

<body>

<div class="topnav">
  <div class="search-container">
    <form  method="GET" action={{ route('resultados') }}>
     @csrf
     <div class="texto">
      <input class="input" type="text" id="search" placeholder="Buscar.." name="search">
    </div>
    <div class="select">

      <select class="input" name="ubicacion" id="ubicacion">
            <option value=""> {{"Seleccione una ubicacion"}} </option>
        <?php

                $ubicaciones = App\Ubicacion::all();
            foreach ($ubicaciones as $ubicacion) {
            ?>
                    <option value="{{$ubicacion->id}}">{{$ubicacion->ubicacion}}</option>
                <?php
                } //end foreach
                ?>
        </select>
      </div>
      <div class="fechas">
        <input type="text" class="form-control datepicker" placeholder="Fecha de Inicio" name="fecha_reserva1"
        style="padding: 4px; margin-top: 4px; font-size: 14px; border:none; display: inline;">
        <input type="text" class="form-control datepicker" placeholder="Fecha de Fin" name="fecha_reserva2"
        style="padding: 4px; margin-top: 4px; font-size: 14px; border:none; display: inline;">
      </div>
      <div class="checkbox">
        <label class="checkbox-inline">
          <?php if (Auth::user()->tipo_de_usuario != 2){?>
            <input type="checkbox" name="residencia" value="residencia" checked> {{"Residencias"}}
          </label>
        <?php  }  ?>
        <label class="checkbox-inline">
          <input type="checkbox" name="subasta" value="subasta" checked> {{"Subastas"}}
        </label>
        <label class="checkbox-inline">
          <input type="checkbox" name="hot_sale" value="hot_sale" checked> {{"Hot Sales"}}
        </label>
      </div>
      <div>
        <button class="boton" type="submit" name="buscar"><i class="fa fa-search"></i></button>
      </div>
    </form>
  </div>
</div>



<script>
  $('.datepicker').datepicker({
    format: "dd/mm/yyyy",
    language:"es",
    startDate: '+1d',
    // startDate: '+1d', quitar la de arriba y dejar ésta cuando tengamos las HotSales
    endDate: '+12m',
    daysOfWeekDisabled: "0,2,3,4,5,6",
    daysOfWeekHighlighted: "1",
    autoclose: true
  });
</script>
<?php
}
else{

  ?>  <center>
    <h1></h1>
        <div class="col-md-auto"><b><?php echo "Por favor, espera a que tu cuenta sea verificada. Gracias"; ?></b>
        </div>
      </center> <?php
}

 ?>


@endsection

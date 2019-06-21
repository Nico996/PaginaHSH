@extends('layout')

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

@section('mainContent')

<?php

  use App\User;
  use App\Residencia;
  use App\Ubicacion;
  use App\Foto;
  use App\Oferta;

  $usr = User::find($id);

?>
<?php
if (Auth::user()->tipo_de_usuario == 2){
  if (Auth::user()->solicito_upgrade == false){ ?>
    <form method="POST" action="{{ route('solUpgrade', [$usr]) }}">
      @csrf
      <button type="submit" class="btn btn-success btn-lg btn-block">Solicitar Upgrade</button>
    </form>
<?php }
  else {
 ?>
    <center><h3><small class="text-muted">Ya solicitó el upgrade, confirmelo realizando el pago en una de nuestras <a href="{{ route('sucursales')}}">sucursales</a></small></h3></center>
<?php
  }
}
?>
<ul class="list-group">
  <li class="list-group-item">Correo electrónico: {{ $usr->email }}</li>
  <?php if (Auth::user()->tipo_de_usuario != 0){ ?>
  <li class="list-group-item">Nombre: {{ $usr->name }}</li>
  <li class="list-group-item">Domicilio: {{ $usr->direccion }}</li>
  <li class="list-group-item">Nro. de teléfono: {{ $usr->telefono }}</li>
  <?php if (Auth::user()->tipo_de_usuario != 1){?>
  <li class="list-group-item">Semanas que tengo disponibles: {{ $usr->semanas_disp }}</li>
  <?php } }?>
  <li class="list-group-item">Tipo de usuario:
    <?php
      switch ($usr->tipo_de_usuario) {
        case '0':?><b>Admin</b>
    <?php
        break;
        case '1':?><b>Sin verificar</b>
    <?php
        break;
        case '2':?><b>Estandar</b>
    <?php
        break;
        case '3':?><b>Premium</b>
    <?php
        break;
      }?>
  </li>
</ul>
  <center>
    <div class="btn-group" role="group" aria-label="Basic example">
    <?php if ((Auth::user()->tipo_de_usuario == 2) or (Auth::user()->tipo_de_usuario == 3)){ ?><a href="{{ route('listaReservasDeUsuario', [$usr->id]) }}"><button type="button" class="btn btn-sm btn-outline-primary">Ver reservas</button></a><?php } ?>
    <?php if (Auth::user()->tipo_de_usuario != 0){ ?><a href="{{ route('editUsr', [$usr]) }}"><button type="button" class="btn btn-sm btn-outline-primary">Modificar información</button></a><?php } ?>
    <a href="{{ route('changePass', [$usr]) }}"><button type="button" class="btn btn-sm btn-outline-primary">Cambiar contraseña</button></a>
    </div>
  </center>



@endsection

@extends('layout')

<?php
use  App\Provincia;
use  App\Ubicacion;
?>

@section('mainContent')

<?php

if ($errors->any()) {
	foreach ($errors->all() as $error) {
		echo "<p class='alert alert-danger'>*".$error."</p>";
	}

}


?>
  <div style="display:block; text-align:center; margin-top:100px; ">
	<form method="post" action="{{ route('altaExitosa') }}">
	@csrf
	<div class="form-group">
		<label for="ubicacion_precisa">Descripción:</label>
		<p></p>
	  <textarea name="descripcion" rows="7" cols="30" placeholder="Ingrese una descripcion" required autofocus></textarea>
	</div>
	<div class="form-group">
	  <label for="ubicacion">Seleccione una ubicacion</label>
	  <select class="form-control" name="ubicacion" id="ubicacion">
			<?php
				$ubicaciones = Ubicacion::all();
				foreach ($ubicaciones as $ubicacion) {
			?>
	  				<option value= <?php echo '"'; echo $ubicacion->id; echo '"' ?> > <?php echo $ubicacion->ubicacion; ?> </option>
	 		<?php
	 			} //end foreach
	 		?>
	  </select>
		<p></p><label for="ubicacion_precisa">Ubicación precisa (oculta):</label><p></p>
		 <textarea name="ubicacion_precisa" rows="1" cols="40" style="height: 42px;" placeholder="Ingrese una ubicación precisa"required autofocus></textarea>
	</div>
	   <input type="submit" value="Crear Residencia" class="btn btn-primary">
	</form>
	<a href={{ route('altaUbicacion')}} class="btn btn-secondary my-2">Agregar Ubicacion</a>

   </div>




@endsection

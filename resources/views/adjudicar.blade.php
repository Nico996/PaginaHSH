@extends('layout')

@section('mainContent')
<?php
	use App\Subasta;
	use App\Residencia;
	use App\Oferta;
	use App\User;

if ($errors->any()) {
	foreach ($errors->all() as $error) {
		echo "<p class='alert alert-danger'>*".$error."</p>";
		?>
		<p><form method="POST" action="{{ route('deleteSub', [Subasta::find($id)]) }}">
			@csrf
			{{ method_field('DELETE') }}
			<button type="submit" class="btn btn-sm btn-outline-secondary">Sí</button>
		</form></p>
		<?php
	}
}

	$sub = Subasta::find($id);

	$ofertas = $sub->ofertas->sortBy('monto');

	if ($ofertas->first() != null) {

		$ofertas = $ofertas->reverse();
		$ofertaMaxima = $ofertas->first();
		$ganador = User::find($ofertaMaxima->usr_id);
		$text = "Ofertas para esta subasta";
	} 
	else $text = "No hay ninguna oferta para esta subasta";
	
?>

<ul class="list-group">
  <li class="list-group-item"><h3>{{$text}}</h3></li>

  <?php
  $mostrar = true;

  foreach ($ofertas as $oferta) { 
  	$mail = User::find($oferta->usr_id)->email;
 

  ?> 

  	<li class="list-group-item">Mail: {{ $mail }} | Monto: {{ $oferta->monto }} <?php  if(($oferta->usr_id == $ganador->id)&&($mostrar)) {
  		$mostrar = false;
  		?>
  		<form method="POST" action="{{ route('saveAdj', [$id]) }}">
  		 	@csrf
		<input type="hidden" name="oferta" value="{{ $ofertaMaxima->id }}">
  		<button type="submit" class="btn btn-sm btn-outline-secondary">¡Ganador! - Adjudicar</button>
  		</form> 
  		<?php } ?>
  	</li>

<?php

	} //end foreach
	echo "</ul>"; 

/* } //endif
  else {
	echo '<p class="list-group-item" >Ninguna oferta ha alcanzado el monto mínimo para esta subasta</p>';
 	}
*/
?>
@endsection
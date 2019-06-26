<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hotsale extends Model
{
  protected $table = 'hotsales';

  protected $fillable = [
      'residencia_id','monto','fecha_reserva','finalizada'
  ];

  public function residencia(){
    return $this->belongsTo(Residencia::class);
  }

  public function usuario(){
    return $this->belongsTo(User::class);
  }
}

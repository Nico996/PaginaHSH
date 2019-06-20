<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Hash;

class UserController extends Controller
{


    public function listado(){
      return view('UserList', ['title' => 'Listado de Usuarios']);

    }

    public function check($id){
      $user=User::Find($id);
      $user->tipo_de_usuario=2;
      $user->update();
      return redirect()->route('listUsr');
    }


    public function ViewUsr($id){
      return view('viewUsr', [
        'title' => 'Detalles del usuario',
        'id' => $id,
        ]);
    }

    public function EditUsr($id){
      return view('editUsr', [
        'title' => 'Modificar la información',
        'id' => $id,
        ]);
    }

    public function update(User $user){
        $data = request()->validate([
            'name' => ['required','string','max:255'],
            'direccion' => 'required',
            'telefono' => 'required',
            'dni' => ['required','numeric'],
            'fecha_nac' => 'required'
            ], [
            'name.required' => 'El campo nombre es obligatorio',
            'direccion.required' => 'El campo domicilio es obligatorio',
            'telefono.required' => 'El campo teléfono es obligatorio',
            'dni.required' => 'El campo DNI es obligatorio',
            'dni.numeric' => 'El DNI ingresado es inválido',
            'fecha_nac.required' => 'El campo fecha de nacimiento es obligatorio'
            ]);

        $user->update($data);
        return redirect()->route('viewUsr', [$user]);
    }

    public function ChangePass($id){
      return view('changePass', [
        'title' => 'Cambiar contraseña',
        'id' => $id,
        ]);
    }

     public function updatePass(int $user){

      $user = User::find($user);
     	$data = request()->validate([
     		'actual' => 'required',
     		'password' => ['required', 'string', 'min:8', 'confirmed'],
			], [
			'actual.required' => 'Todos los campos son obligatorios',
			'password.required' => 'Todos los campos son obligatorios',
			'password.min' => 'La contraseña debe tener al menos 8 caracteres',
			'password.confirmed' => 'La confirmación de la nueva contraseña no es válida',
			]);

		if (!(Hash::check($data['actual'], $user->password))) {
      		// La contraseña actual no es correcta
            return redirect()->back()->withErrors('La contraseña actual no es correcta');
        }

    $data['password'] = bcrypt($data['password']);
		$user->update($data);

        return redirect()->route('viewUsr', [$user]);
     	}

}
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUser extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules() {
    return [
      'name' => 'required',
      'email' => 'required|unique:users,email,'.$this->id,
      'password' => 'required_if:isUpdatePassword,1',
      'repeatPassword' => 'required_if:isUpdatePassword,1'
    ];
  }

  public function attributes() {
    return [
      'name' => 'nombres',
      'email' => 'correo electrónico',
      'password' => 'Contraseña',
      'repeatPassword' => 'Repita contraseña',
      'isUpdatePassword' => 'Cambiar contraseña'
    ];
  }

  public function messages() {
    return [
      'password.required_if' => 'El campo :attribute es obligatorio cuando el campo Cambiar contraseña es activado.',
      'repeatPassword.required_if' => 'El campo :attribute es obligatorio cuando el campo Cambiar contraseña es activado.'
    ];
  }
}

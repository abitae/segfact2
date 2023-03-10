<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfile extends FormRequest
{

    public function authorize()
    {
      return true;
    }


    public function rules() {
      return [
        'nickName' => 'required',
        'email' => 'required|unique:users,email,'.$this->id,
        'currentPassword' => 'required_if:hasChangePassword,on',
        'newPassword' => 'required_if:hasChangePassword,on'
      ];
    }

    public function messages()
    {
      return [
        'currentPassword.required_if' => 'El campo :attribute es obligatorio cuando el campo Cambiar contraseña es activado.',
        'newPassword.required_if' => 'El campo :attribute es obligatorio cuando el campo Cambiar contraseña es activado.',
      ];
    }

    public function attributes() {
      return [
        'nickName' => 'Nombres',
        'email' => 'Correo electrónico',
        'currentPassword' => 'Contraseña actual',
        'newPassword' => 'Nueva contraseña',
        'hasChangePassword' => 'Cambiar contraseña',
      ];
    }


}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUser extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'name' => 'required',
      'email' => 'required|unique:users,email'
    ];
  }

  public function attributes() {
    return [
      'name' => 'Nombres',
      'email' => 'Correo Electrónico'
    ];
  }

}

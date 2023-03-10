<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSynchronizedShopping extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
      return [
        'idUser' => 'required'
      ];
  }

  public function attributes()
  {
    return [
      'idUser' => 'Comprador'
    ];
  }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLicenses extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'description' => 'required',
      'quantity' => 'required|min:1',
      'installationDate' => 'required|date',
      // 'expirationDate' => 'required|date',
      'idCustomer' => 'required',
      'idContact' => 'required',
    ];
  }

  public function attributes()
  {
    return [
      'description' => 'Descripción',
      'quantity' => 'Cantidad',
      'installationDate' => 'Fecha de instalación',
      'expirationDate' => 'Fecha de expiración',
      'idCustomer' => 'Cliente',
      'idContact' => 'Contacto',
    ];
  }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomer extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'typeDocument' => 'required',
      'nroDocument' => 'required|unique:customers,nroDocument,'.$this->id,
      'name' => "exclude_if:typeDocument,06|required",
      'lastName' => "exclude_if:typeDocument,06|required",
      'fullName' => "exclude_if:typeDocument,01|required",
      'address' => 'required',
      'email' => 'required|email',
      'nroPhone' => 'required',
    ];
  }

  public function attributes() {
    return [
      'typeDocument' => 'Tipo Documento',
      'nroDocument' => 'Nro de DNI ó RUC',
      'name' => 'Nombres',
      'lastName' => 'Apellidos',
      'fullName' => 'Razón Social/Nombres',
      'address' => 'Dirección',
      'email' => 'Correo Electrónico',
      'nroPhone' => 'Nro de Teléfono/Celular',
    ];
  }
}

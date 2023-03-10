<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSale extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'codigoFactura' => 'required',
      'fechaEmision' => 'required',
      'attachPurchaseOrderDocument' => 'required|mimes:pdf|max:10000',
      'attachRemissionGuideDocument' => 'required|mimes:pdf|max:10000',
      'contactNroPhone' => "required_if:contactRegistered,false|unique:contacts,nroPhone,".$this->idContact."|numeric|digits:9",
      'contactFullName' => 'required_if:contactRegistered,false',
    ];
  }

  public function attributes()
  {
    return [
      'codigoFactura' => 'Código de factura',
      'fechaEmision' => 'Fecha emisión',
      'attachPurchaseOrderDocument' => 'Adjuntar orden de compra',
      'attachRemissionGuideDocument' => 'Adjuntar guía de remisión',
      'contactNroPhone' => 'CONTACTO: Número celular',
      'contactFullName' => 'CONTACTO: Nombres',
    ];
  }

  public function messages() {
    return [
      'contactNroPhone.required_if' => 'El campo :attribute es obligatorio',
      'contactFullName.required_if' => 'El campo :attribute es obligatorio',
    ];
  }
}

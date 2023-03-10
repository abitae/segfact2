<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSeries extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'nroSerie' => 'required|unique:series,nroSerie'
    ];
  }

  public function attributes()
  {
    return ['nroSerie' => 'M° Serie'];
  }
}

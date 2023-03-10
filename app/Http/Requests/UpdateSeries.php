<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSeries extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'nroSerie' => 'required|unique:series,nroSerie,'.$this->id
    ];
  }

  public function attributes()
  {
    return ['nroSerie' => 'N° Serie'];
  }
}

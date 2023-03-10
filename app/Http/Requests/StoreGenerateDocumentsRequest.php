<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGenerateDocumentsRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'warrantyPeriodQuantity' => 'required|numeric|min:0'
    ];
  }

  public function attributes() {
    return [
      'warrantyPeriodQuantity' => 'Cantidad en garantía'
    ];
  }
}

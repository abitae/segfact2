<?php

namespace App\Http\Controllers;

use App\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BankController extends Controller
{
  public function list(Request $request) {

    $banks = Bank::where(function ($query) use ($request) {
      if($request->name) {
        $query->where('name', 'LIKE', '%'.$request->name.'%');
      }
    })->get();
    return $banks;
  }

  public function store(Request $request) {
    $rules = ['name' => 'required|unique:banks,name'];
    $attributes = ['name' => 'Nombre'];

    Validator::make($request->all(), $rules,[], $attributes)->validate();

    $bank = Bank::create($request->all());
    return $bank;
  }

  public function update(Request $request) {
    $rules = ['name' => 'required|unique:banks,name,'.$request->id];
    $attributes = ['name' => 'Nombre'];

    Validator::make($request->all(), $rules,[], $attributes)->validate();
    $bank = Bank::find($request->id);
    $bank->update($request->all());
    return $bank;
  }


}

<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Http\Requests\StoreCustomer;
use App\Http\Requests\UpdateCustomer;
use Exception;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
  public function listCustomers(Request $request) {
    if(!$request->ajax()) { return false;}

    $filters = [];
    if($request->nroDocumentSearch) {
      $filters[] = ['nroDocument','LIKE',"%$request->nroDocumentSearch%"];
    }
    if($request->fullNameSearch) {
      $filters[] = ['fullName','LIKE',"%$request->fullNameSearch%"];
    }
    if($request->addressSearch) {
      $filters[] = ['address','LIKE',"%$request->addressSearch%"];
    }
    if($request->emailSearch) {
      $filters[] = ['email','LIKE',"%$request->emailSearch%"];
    }

    $listCustomers = Customer::where($filters)->orderByDesc('id')->paginate(10);
    return $listCustomers;
  }

  public function customerData(Request $request) {
    $customer = Customer::where('nroDocument',$request->nroDocument)->first();
    if(!$customer) throw new Exception("El Cliente con el N° DNI/RUC $request->nroDocument no esta registrado.");
    return $customer;
  }

  public function store(StoreCustomer $request) {
    if(!$request->ajax()) { return false;}

    $customer = new Customer();
    $customer->typeDocument = $request->typeDocument;
    $customer->nroDocument = $request->nroDocument;
    $customer->name = $request->name;
    $customer->lastName = $request->lastName;
    $customer->fullName = $request->fullName;
    $customer->address = $request->address;
    $customer->email = $request->email;
    $customer->nroPhone = $request->nroPhone;
    $customer->save();

    return $customer;
  }

  public function save($data)
  {
    $customer = new Customer;
    $customer->typeDocument = $data->typeDocument;
    $customer->nroDocument = $data->nroDocument;
    $customer->name = $data->name;
    $customer->lastName = $data->lastName;
    $customer->fullName = $data->fullName;
    $customer->address = $data->address;
    $customer->email = $data->email;
    $customer->nroPhone = $data->nroPhone;
    $customer->save();
    return $customer->id;
  }

  public function update(UpdateCustomer $request) {
    if(!$request->ajax()) { return false;}

    $customer = Customer::findOrFail($request->id);
    $customer->typeDocument = $request->typeDocument;
    $customer->nroDocument = $request->nroDocument;
    $customer->name = $request->name;
    $customer->lastName = $request->lastName;
    $customer->fullName = $request->fullName;
    $customer->address = $request->address;
    $customer->email = $request->email;
    $customer->nroPhone = $request->nroPhone;
    $customer->save();

    return $customer;
  }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplier;
use App\Http\Requests\UpdateSupplier;
use Illuminate\Http\Request;
use App\Supplier;
use Exception;

class SupplierController extends Controller
{
  public function listSuppliers(Request $request) {
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

    $listSuppliers = Supplier::where($filters)->orderByDesc('id')->paginate(10);
    return $listSuppliers;
  }

  public function supplierData(Request $request) {
    $supplier = Supplier::where('nroDocument',$request->nroDocument)->first();
    if(!$supplier) throw new Exception("El proveedor con el N° Doc $request->nroDocument no esta registrado.");
    return $supplier;
  }


  public function store(StoreSupplier $request) {
    if(!$request->ajax()) { return false;}

    $supplier = new Supplier();
    $supplier->typeDocument = $request->typeDocument;
    $supplier->nroDocument = $request->nroDocument;
    $supplier->name = $request->name;
    $supplier->lastName = $request->lastName;
    $supplier->fullName = $request->fullName;
    $supplier->address = $request->address;
    $supplier->email = $request->email;
    $supplier->nroPhone = $request->nroPhone;
    $supplier->save();
    return $supplier;
  }

  public function update(UpdateSupplier $request) {
    if(!$request->ajax()) { return false;}

    $supplier = Supplier::findOrFail($request->id);
    $supplier->typeDocument = $request->typeDocument;
    $supplier->nroDocument = $request->nroDocument;
    $supplier->name = $request->name;
    $supplier->lastName = $request->lastName;
    $supplier->fullName = $request->fullName;
    $supplier->address = $request->address;
    $supplier->email = $request->email;
    $supplier->nroPhone = $request->nroPhone;
    $supplier->save();

    return $supplier;
  }
}

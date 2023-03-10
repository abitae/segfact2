<?php

namespace App\Http\Controllers;

use App\BranchOffice;
use App\Enterprise;
use App\Http\Requests\StoreEnterprise;
use App\Http\Requests\UpdateEnterprise;
use Illuminate\Http\Request;

class EnterpriseController extends Controller
{
  public function listEnterprises(Request $request) {
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

    $listEnterprises = Enterprise::where($filters)->orderByDesc('id')->paginate(10);
    return $listEnterprises;
  }

  public function getBranches(Request $request) {
    if(!$request->ajax()) { return false;}
    $list = BranchOffice::where('idEnterprise', $request->idMycompany)->get();
    return $list;
  }

  public function store(StoreEnterprise $request) {
    if(!$request->ajax()) { return false;}

    $enterprise = new Enterprise();
    $enterprise->typeDocument = $request->typeDocument;
    $enterprise->nroDocument = $request->nroDocument;
    $enterprise->name = $request->name;
    $enterprise->lastName = $request->lastName;
    $enterprise->fullName = $request->fullName;
    $enterprise->address = $request->address;
    $enterprise->email = $request->email;
    $enterprise->nroPhone = $request->nroPhone;
    $enterprise->idEnterpriseMyCompany = $request->idEnterpriseMyCompany;

    $enterprise->representative_name = $request->representative_name;
    $enterprise->representative_dni = $request->representative_dni;
    $enterprise->nro_cuenta_interbancaria = $request->nro_cuenta_interbancaria;
    $enterprise->nro_cuenta_detraction = $request->nro_cuenta_detraction;

    $enterprise->save();

    return $enterprise;
  }

  public function update(UpdateEnterprise $request) {
    if(!$request->ajax()) { return false;}

    $enterprise = Enterprise::findOrFail($request->id);
    $enterprise->typeDocument = $request->typeDocument;
    $enterprise->nroDocument = $request->nroDocument;
    $enterprise->name = $request->name;
    $enterprise->lastName = $request->lastName;
    $enterprise->fullName = $request->fullName;
    $enterprise->address = $request->address;
    $enterprise->email = $request->email;
    $enterprise->nroPhone = $request->nroPhone;
    $enterprise->idEnterpriseMyCompany = $request->idEnterpriseMyCompany;

    $enterprise->representative_name = $request->representative_name;
    $enterprise->representative_dni = $request->representative_dni;
    $enterprise->nro_cuenta_interbancaria = $request->nro_cuenta_interbancaria;
    $enterprise->nro_cuenta_detraction = $request->nro_cuenta_detraction;

    $enterprise->save();

    return $enterprise;
  }
}

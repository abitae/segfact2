<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Http\Requests\StoreContact;
use App\Http\Requests\UpdateContact;
use Exception;
use Illuminate\Http\Request;

class ContactController extends Controller
{
  public function listContacts(Request $request) {
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

    $listCustomers = Contact::where($filters)->orderByDesc('id')->paginate(10);
    return $listCustomers;
  }

  public function contactData(Request $request) {
    $customer = Contact::where('nroDocument',$request->nroDocument)->first();
    if(!$customer) throw new Exception("El Contacto con el N° DNI/RUC $request->nroDocument no esta registrado.");
    return $customer;
  }

  public function store(StoreContact $request) {
    if(!$request->ajax()) { return false;}

    $customer = new Contact();
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

  public function update(UpdateContact $request) {
    if(!$request->ajax()) { return false;}

    $customer = Contact::findOrFail($request->id);
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

  public function getContactByNroNPhone(Request $request) {
    return Contact::where('nroPhone',$request->nroPhone)->firstOrFail();
  }
}

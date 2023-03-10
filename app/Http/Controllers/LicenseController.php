<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLicenses;
use App\Http\Requests\UpdateLicenses;
use App\License;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LicenseController extends Controller
{

  public function listLicenses(Request $request) {
    if(!$request->ajax()) { return false;}

    $listLicenses = License::with(['client','contact'])
    ->whereHas('client', function ($client) use ($request) {
      $client->where('fullName', 'LIKE', "%$request->nameClientSearch%");
    })
    ->whereHas('contact', function ($contact) use ($request) {
      $contact->where('fullName', 'LIKE', "%$request->nameContactSearch%");
    })
    ->where('product','LIKE',"%$request->nameProductSearch%")
    ->where(function($license) use ($request) {
      if($request->searchByDate) {
        $license->whereBetween($request->searchByDate,[$request->dateStartSearch, $request->dateEndSearch]);
      }
    })
    ->orderByDesc('id')
    ->paginate(10);
    return $listLicenses;
  }

  public function store(StoreLicenses $request) {
    $license = new License;
    $license->product = $request->product;
    $license->description = $request->description;
    $license->quantity = $request->quantity;
    $license->installationDate = $request->installationDate;
    $license->expirationDate = $request->expirationDate;
    $license->idClient = $request->idClient;
    $license->idContact = $request->idContact;
    $license->save();

    return $license;
  }

  public function update(UpdateLicenses $request) {
    $license = License::find($request->id);
    $license->product = $request->product;
    $license->description = $request->description;
    $license->quantity = $request->quantity;
    $license->installationDate = $request->installationDate;
    $license->expirationDate = $request->expirationDate;
    $license->idClient = $request->idClient;
    $license->idContact = $request->idContact;
    $license->save();

    return $license;
  }

  public function save($data) {
    $license = new License();
    $license->idSaleDetail = $data->idSaleDetail;
    $license->product = $data->product;
    $license->description = $data->description;
    $license->quantity = $data->quantity;
    $license->installationDate = $data->installationDate;
    $license->idClient = $data->idClient;
    $license->idContact = $data->idContact;
    $license->save();
    return $license->id;
  }
}

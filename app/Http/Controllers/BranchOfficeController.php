<?php

namespace App\Http\Controllers;

use App\BranchOffice;
use Illuminate\Http\Request;

class BranchOfficeController extends Controller
{

  public function store(Request $request) {
    $this->save($request->input());
  }

  public function save($data) {
    $currentBranch = BranchOffice::where('idMycompany', $data->idMycompany)->first();
    if($currentBranch) return $currentBranch->id;

    $branchOffice = new BranchOffice();
    $branchOffice->name = $data->name;
    $branchOffice->address = $data->address;
    $branchOffice->idUbigeo = $data->idUbigeo;
    $branchOffice->nroPhone = $data->nroPhone;
    $branchOffice->email = $data->email;
    $branchOffice->idMycompany = $data->idMycompany;
    $branchOffice->idEnterprise = $data->idEnterprise;
    $branchOffice->save();

    return $branchOffice->id;
  }

}

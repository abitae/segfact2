<?php

namespace App\Http\Controllers;

use App\OperationVoucherTracking;
use Illuminate\Http\Request;

class OperationVoucherTrackingController extends Controller
{

  public function save($data, $idSale) {
    $operation = new OperationVoucherTracking();
    $operation->idSale = $idSale;
    $operation->nroComprobante = $data['nroComprobante'];
    $operation->state = $data['state'];
    $operation->idUser = $data['idUser'];

    if(isset($data['observation'])) {
      $operation->observation = $data['observation'];
    }
    if(isset($data['refactoringCode'])) {
      $operation->refactoringCode = $data['refactoringCode'];
    }
    $operation->save();
    return $operation->id;
  }

    public function history($nroComprobante) {
    $tracking = OperationVoucherTracking::where('nroComprobante', $nroComprobante)->first();
    $listHistory = OperationVoucherTracking::with(['estado','user'])->where('idSale',$tracking->idSale)->get();
    return view('backend.tracking-history', compact('listHistory', 'nroComprobante'));
  }
}

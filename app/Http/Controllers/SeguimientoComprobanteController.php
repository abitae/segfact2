<?php

namespace App\Http\Controllers;

use App\Exports\SeguimientoComprobanteExport;
use App\Http\Requests\StoreSeguimientoComprobante;
use App\Http\Requests\UpdateSeguimientoComprobante;
use App\OperationVoucherTracking;
use Illuminate\Http\Request;
use App\SeguimientoComprobante;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class SeguimientoComprobanteController extends Controller
{
  public function getUserRoleNames() {
    $roles = User::find(auth()->user()->id)->getRoleNames();
    return $roles;
  }

  public function filterDocumentStates() {
    $roleUsers = $this->getUserRoleNames();
    $filterDocumentState = [
      'despacho' => [1],
      'seguimiento' => [6, 2, 3]
    ];

    $listStateDocuments = array_reduce((array) $roleUsers, function ($acc, $roles) use ($filterDocumentState) {
      $items = [];
      for ($i=0; $i < count($roles); $i++) {
        if(array_key_exists($roles[$i], $filterDocumentState)) {
          array_push($items, ...$filterDocumentState[$roles[$i]]);
        }
      }
      return $items;
    }, []);

    return array_unique($listStateDocuments);
  }

  public function listTrackingReceipts(Request $request) {
    $filters = [];

    $filters[] = ['isActiveTracking','=',1];
    if($request->codigoFacturaSearch) {
      $filters[] = ['nroFacturaVenta','LIKE',"%$request->codigoFacturaSearch%"];
    }

    if($request->estadoDocumentoSearch) {
      $filters[] = ['estadoDocumento','=',"$request->estadoDocumentoSearch"];
    }

    if($request->bankIdSearch) {
      $filters[] = ['bank_id','=',$request->bankIdSearch];
    }
    if($request->nameUserSearch) {
      $filters[] = ['idUsuario','=',$request->nameUserSearch];
    }

    $filterDocumentStates = $this->filterDocumentStates();
    $listSale = SeguimientoComprobante::with(['bank', 'usuario','cliente','estadoDeDocumento','empresa'])
    ->whereHas('cliente', function ($model) use ($request){
      if($request->nroDocumentCustomerSearch) {
        $model->where('nroDocument','LIKE',"%$request->nroDocumentCustomerSearch%");
      }
    })
    ->whereHas('empresa',function ($model) use ($request) {
      if($request->enterpriseSearch) {
        $model->whereId($request->enterpriseSearch);
      }
    })
    ->when(count($filterDocumentStates), function ($query) use ($filterDocumentStates) {
      $query->whereIn('estadoDocumento',$filterDocumentStates);
    })
    ->where(function($query) use ($request) {
      if($request->searchByDate) {
        if($request->searchByDate == 'fechaVencimiento') {
          $query->whereNotIn('estadoDocumento', [5,6])
          ->whereBetween($request->searchByDate,[$request->dateStartSearch,$request->dateEndSearch]);
        } else {
          $query->whereBetween($request->searchByDate,[$request->dateStartSearch,$request->dateEndSearch]);
        }
      }
    })
    ->where($filters)
    ->orderByDesc('nroFacturaVenta')
    ->paginate($request->paginate);

    return $listSale;
  }

  public function calculateStatistics(Request $request) {
    $user = User::find($request->user_id);
    return $user;
  }

  public function save($voucherTracking) {
    $voucher = new SeguimientoComprobante();
    $voucher->nroFacturaVenta = $voucherTracking->nroFacturaVenta;
    $voucher->idCustomer = $voucherTracking->idCustomer;
    $voucher->idEnterprise = $voucherTracking->idEnterprise;
    $voucher->idSale = $voucherTracking->idSale;
    $voucher->idUsuario = $voucherTracking->idUsuario;
    $voucher->idBranchOffice = $voucherTracking->idBranchOffice;
    $voucher->monto = $voucherTracking->montoTotal;
    $voucher->detraccion = 0;
    $voucher->retencion = 0;
    $voucher->arranged = $voucherTracking->arranged;
    $voucher->monto = $voucherTracking->montoTotal;
    $voucher->montoTotal = $voucherTracking->montoTotal;
    $voucher->descripcionBienServicio = $voucherTracking->descripcionBienServicio;
    $voucher->fechaEmision = Carbon::parse($voucherTracking->fechaEmision)->format('Y-m-d');
    $voucher->idContact = $voucherTracking->idContact;
    $voucher->estadoDocumento = 1;
    $voucher->actionesObservaciones = 'Venta realizada con éxito';
    $voucher->save();

    return $voucher->id;
  }

  public function update(UpdateSeguimientoComprobante $request) {
    $sale = SeguimientoComprobante::findOrFail($request->id);
    $sale->codigoUnidadEjecutora = $request->codigoUnidadEjecutora;
    $sale->nroSiaf = $request->nroSiaf;
    $sale->monto = $request->monto;
    $sale->detraccion = $request->detraccion;
    $sale->retencion = $request->retencion;
    $sale->montoTotal = $request->montoTotal;
    $sale->bank_id = $request->bank_id;
    $sale->fechaPago = $request->fechaPago;
    $sale->save();

    return $sale;
  }

  public function exportExcel(Request $request) {
    $filters = [];
    if($request->codigoFacturaSearch) {
      $filters[] = ['codigoFactura','LIKE',"%$request->codigoFacturaSearch%"];
    }
    if($request->estadoDocumentoSearch) {
      $filters[] = ['estadoDocumento','=',"$request->estadoDocumentoSearch"];
    }
    if($request->enterpriseSearch) {
      $filters[] = ['idEnterprise','=',"$request->enterpriseSearch"];
    }
    $listTrackingReceipts = SeguimientoComprobante::with([
      'usuario','cliente','estadoDeDocumento','facturaVenta','empresa'
    ])->whereHas('cliente', function ($model) use ($request){
      if($request->nroDocumentCustomerSearch) {
        $model->where('nroDocument','LIKE',"%$request->nroDocumentCustomerSearch%");
      }
    })->whereHas('usuario',function ($model) use ($request){
      if($request->nameUserSearch) {
        $model->where('name','LIKE',"%$request->nameUserSearch%");
      }
    })->where($filters)
    ->where(function($query) use ($request) {
      if($request->searchByDate) {
        $query->whereBetween($request->searchByDate,[$request->dateStartSearch,$request->dateEndSearch]);
      }
    })
    ->orderByDesc('nroFacturaVenta')
    ->get()
    ->groupBy(function ($item, $key) {
      // dd($item);
      return strval($item->empresa->fullName);
    });
    // dd($listTrackingReceipts);
    // dd($listTrackingReceipts);
    // return view('backend.exports.excel.tracking-receipts-list', [
    //   'listTrackingReceipts' => $listTrackingReceipts
    // ]);

    return Excel::download(new SeguimientoComprobanteExport($listTrackingReceipts),'Seguimiento.xlsx');
  }

  public function exportPdf(Request $request) {
    $filters = [];
    if($request->codigoFacturaSearch) {
      $filters[] = ['codigoFactura','LIKE',"%$request->codigoFacturaSearch%"];
    }
    if($request->estadoDocumentoSearch) {
      $filters[] = ['estadoDocumento','=',"$request->estadoDocumentoSearch"];
    }
    $listTrackingReceipts = SeguimientoComprobante::with([
      'usuario','cliente','estadoDeDocumento','facturaVenta','empresa'
    ])->whereHas('cliente', function ($model) use ($request){
      if($request->nroDocumentCustomerSearch) {
        $model->where('nroDocument','LIKE',"%$request->nroDocumentCustomerSearch%");
      }
    })->whereHas('usuario',function ($model) use ($request){
      if($request->nameUserSearch) {
        $model->where('name','LIKE',"%$request->nameUserSearch%");
      }
    })->where($filters)
    ->where(function($query) use ($request) {
      if($request->searchByDate) {
        $query->whereBetween($request->searchByDate,[$request->dateStartSearch,$request->dateEndSearch]);
      }
    })
    ->orderByDesc('nroFacturaVenta')
    ->get();
    $pdf = PDF::loadView('backend.exports.pdf.tracking-receipts-list',compact('listTrackingReceipts'));
    $pdf->setPaper('a4','landscape');
    return $pdf->download('Seguimiento.pdf') ;
  }

  public function annulmentVoucher(Request $request) {
    $rules = [
      'observation' => 'required',
      'newVoucher' => 'required_if:isRebilling,true|unique:seguimiento_comprobantes,nroFacturaVenta',
    ];

    $messages = [
      'newVoucher.required_if' => 'El campo :attribute es obligatoria cuando la Refacturación está activo',
    ];

    $attributes = [
      'observation' => 'Motivo',
      'newVoucher' => 'Nuevo comprobante'
    ];
    $this->validate($request, $rules, $messages,$attributes);

    $voucherTracking = SeguimientoComprobante::find($request->id);
    $voucherTracking->estadoDocumento = $request->state;
    $voucherTracking->save();

    $operation = new OperationVoucherTracking();
    $operation->idSale = $request->id;
    $operation->observation = $request->observation;
    $operation->idUser = auth()->user()->id;
    $operation->nroComprobante = $request->nroComprobante;
    $operation->state = $request->state;
    $operation->save();

    if($request->isRebilling) {
      $copyVoucherTracking = $voucherTracking->replicate();
      $copyVoucherTracking->fechaEmision = now()->format('Y-m-d');
      $copyVoucherTracking->fechaVencimiento = NULL;
      $copyVoucherTracking->nroFacturaVenta = $request->newVoucher;
      $copyVoucherTracking->refactoringCode = $request->nroComprobante;
      $copyVoucherTracking->estadoDocumento = 1;
      $copyVoucherTracking->save();

      $copyOperation = $operation->replicate();
      $copyOperation->nroComprobante = $request->newVoucher;
      $copyOperation->observation = 'Sin observaciones';
      $copyOperation->state = 1;
      $copyOperation->refactoringCode = $request->nroComprobante;
      $copyOperation->save();
    }

    return $voucherTracking;
  }

  public function updateStatus(Request $request) {
    $voucherTracking = SeguimientoComprobante::find($request->id);
    $voucherTracking->estadoDocumento = $request->state;
    if($request->state == 3) {
      $voucherTracking->fechaVencimiento = $request->fechaVencimiento;
    }
    $voucherTracking->save();
    $idVoucher = $voucherTracking->idSale;
    if($voucherTracking->refactoringCode) {
      $newVoucher = SeguimientoComprobante::where('nroFacturaVenta', $voucherTracking->refactoringCode)->first();
      $idVoucher = $newVoucher->idSale;
    }

    $operation = new OperationVoucherTracking();
    $operation->idSale = $idVoucher;
    $operation->nroComprobante = $request->nroComprobante;
    $operation->state = $request->state;
    $operation->idUser = auth()->user()->id;
    if($voucherTracking->refactoringCode) {
      $operation->refactoringCode = $voucherTracking->refactoringCode;
    }
    if($request->observation) {
      $operation->observation = $request->observation;
    }
    $operation->save();

    return $voucherTracking;
  }

  public function markAsArranged(Request $request) {
    $markAsArranged = SeguimientoComprobante::whereId($request->sale_id)->update([
      'arranged' => $request->stateArranged
    ]);

    return $markAsArranged;
  }
}

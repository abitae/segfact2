<?php

namespace App\Http\Controllers;

use App\Exports\SaleExport;
use App\Http\Requests\StoreGenerateDocumentsRequest;
use App\Http\Requests\StoreSale;
use App\Http\Requests\UpdateSale;
use App\Sale;
use App\SaleSeries;
use App\SeguimientoComprobante;
use App\Series;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SaleController extends Controller
{

  public function URLSearchParams(Request $request) {
    $filters = [];
    if($request->codigoFacturaSearch) {
      $filters[] = ['codigoFactura','LIKE',"%$request->codigoFacturaSearch%"];
    }

    if($request->idTipoComprobanteSearch) {
      $filters[] = ['idTipoComprobante','LIKE',"%$request->idTipoComprobanteSearch%"];
    }

    if($request->compraSolesDolaresSearch) {
      $filters[] = ['compraSolesDolares','=',"$request->compraSolesDolaresSearch"];
    }

    if($request->idBranchOfficeSearch) {
      $filters[] = ['idBranchOffice','=',$request->idBranchOfficeSearch];
    }

    if($request->idEnterpriseSearch) {
      $filters[] = ['idEnterprise','=',$request->idEnterpriseSearch];
    }

    $user = User::find(Auth::user()->id);
    if(!$user->hasRole(['administrador', 'contabilidad'])) {
      $filters[] = ['idUsuario', '=', auth()->user()->id];
      // $filters[] = ['stateDelivery', '=', 1];
      // $filters[] = ['created_at', 'LIKE', now()->format('Y-m-d').'%']; // comment this line code for show all registers
    }
    return $filters;
  }

  public function listSale(Request $request) {
    $filters = $this->URLSearchParams($request);
    // $user = User::find(Auth::user()->id);

    $listShoppings = Sale::with([
      'cliente', 'tipoComprobante', 'enterprise', 'branchOffice', 'vendedor', 'ventaDetalle', 'ventaDetalle.listSeries', 'ventaDetalle.listSeries.serie'
    ])
    ->whereHas('cliente', function ($model) use ($request) {
      if($request->nroDocumentCustomerSearch) {
        $model->where('nroDocument','LIKE',"%$request->nroDocumentCustomerSearch%");
      }
    })
    ->where($filters)
    ->when($request->searchByDate, function ($query) use ($request) {
      $startDate = Carbon::parse($request->dateEmissionStart)->startOfDay();
      $endDate = Carbon::parse($request->dateEmissionEnd)->endOfDay();
      $query->whereBetween('fechaEmision', [$startDate, $endDate]);
    })
    ->latest()
    ->paginate(10);

    return $listShoppings;
  }

  public function data(Request $request) {
    $sale = Sale::where('codigoFactura',$request->nroFactura)->first();
    if(!$sale) throw new Exception("La venta con el N° Factura $request->nroFactura no esta registrado.");
    return $sale;
  }

  public function deleteSerie(Request $request) {
    Series::whereId($request->idSerie)->update(['is_active' => true]);
    SaleSeries::whereId($request->id)->delete();
    return response()->json('Deleted');
  }

  public function store(StoreSale $request) {
    $sale = new Sale;
    $sale->codigoFactura = $request->codigoFactura;
    $sale->idCustomer = $request->idCustomer;
    $sale->idUsuario = $request->idUsuario;
    $sale->descripcionBienServicio = $request->descripcionBienServicio;
    $sale->fechaEmision = $request->fechaEmision;
    $sale->idTipoComprobante = $request->idTipoComprobante;
    $sale->compraSolesDolares = $request->compraSolesDolares;
    $sale->monto = $request->monto;
    $sale->tipoDeCambio = $request->tipoDeCambio;
    $sale->montoVentaSoles = $request->montoVentaSoles;
    $sale->montoVentaDolares = $request->montoVentaDolares;
    $sale->igv = $request->igv;
    $sale->montoTotal = $request->montoTotal;
    $sale->saleCommission = $request->saleCommission;
    $sale->contactCommission = $request->contactCommission;
    if($request->has('attachDocument')) {
      $currentDate = Carbon::now()->format('d-m-Y-h-i-s');
      $clientOriginalName = $request->attachDocument->getClientOriginalName();
      $nameFile = $currentDate.'__'.$clientOriginalName;
      $request->file('attachDocument')->storeAs('public/pdf', $nameFile);
      $sale->attachDocument = $nameFile;
    }
    $sale->save();
    $this->saveListSeries($sale->id, $request->listSeries);
    Series::whereIn('id',$request->listSeries)->update(['is_active' => false]);
    return $sale;
  }

  public function update(UpdateSale $request) {
    $sale = Sale::find($request->id);
    $sale->codigoFactura = $request->codigoFactura;
    $sale->idCustomer = $request->idCustomer;
    $sale->idUsuario = $request->idUsuario;
    $sale->descripcionBienServicio = $request->descripcionBienServicio;
    $sale->fechaEmision = $request->fechaEmision;
    $sale->idTipoComprobante = $request->idTipoComprobante;
    $sale->compraSolesDolares = $request->compraSolesDolares;
    $sale->monto = $request->monto;
    $sale->tipoDeCambio = $request->tipoDeCambio;
    $sale->montoVentaSoles = $request->montoVentaSoles;
    $sale->montoVentaDolares = $request->montoVentaDolares;
    $sale->igv = $request->igv;
    $sale->montoTotal = $request->montoTotal;
    $sale->saleCommission = $request->saleCommission;
    $sale->contactCommission = $request->contactCommission;
    if($request->has('attachDocument')) {
      $currentDate = Carbon::now()->format('YmdHis');
      $clientOriginalName = $request->attachDocument->getClientOriginalName();
      $nameFile = $currentDate.'__'.$clientOriginalName;
      $request->file('attachDocument')->storeAs('public/pdf', $nameFile);
      $sale->attachDocument = $nameFile;
    }

    $sale->save();
    if($request->listSeriesAdded) {
      $this->saveListSeries($sale->id, $request->listSeriesAdded);
    }
    return $sale;
  }

  public function downloadAttachDocument(Request $request) {
    return Storage::download("public\sales\pdf\\".$request->type.'/'.$request->nameFile,$request->nameFile);
  }

  public function downloadDocument(Request $request) {
    $sale = Sale::whereId($request->idSale)->first();
    $path = $request->path;
    $name = $request->path == 'purchase_order_document' ? $sale->attachDocument : $sale->attachRemissionGuideDocument;
    return Storage::download("public\sales\pdf\\".$path.'/'.$name,$name);
  }

  public function saveListSeries($idShopping, $listSeries) {
    for ($i=0; $i < count($listSeries); $i++) {
      $serie = new SaleSeries();
      $serie->idSale = $idShopping;
      $serie->idSerie = $listSeries[$i];
      $serie->save();
    }
  }

  public function exportExcel(Request $request) {
    $filters = $this->URLSearchParams($request);

    $listShoppings = Sale::with(['cliente','tipoComprobante','vendedor','branchOffice'])->whereHas('cliente', function ($model) use ($request) {
      if($request->nroDocumentCustomerSearch) {
        $model->where('nroDocument','LIKE',"%$request->nroDocumentCustomerSearch%");
      }
    })
    ->where($filters)
    ->where(function ($query) use ($request) {
      if($request->searchByDate) {
        $startDate = Carbon::parse($request->dateEmissionStart)->startOfDay();
        $endDate = Carbon::parse($request->dateEmissionEnd)->endOfDay();
        $query->whereBetween('fechaEmision', [$startDate, $endDate]);
      }
    })
    ->orderByDesc('codigoFactura')
    ->get();

    return Excel::download(new SaleExport($listShoppings), 'Lista de ventas.xlsx');
  }

  public function exportPdf(Request $request) {
    $filters = $this->URLSearchParams($request);

    $saleList = Sale::with(['cliente','tipoComprobante','vendedor','branchOffice'])->whereHas('cliente', function ($model) use ($request) {
      if($request->nroDocumentCustomerSearch) {
        $model->where('nroDocument','LIKE',"%$request->nroDocumentCustomerSearch%");
      }
    })
    ->where($filters)
    ->where(function ($query) use ($request) {
      if($request->searchByDate) {
        $startDate = Carbon::parse($request->dateEmissionStart)->startOfDay();
        $endDate = Carbon::parse($request->dateEmissionEnd)->endOfDay();
        $query->whereBetween('fechaEmision', [$startDate, $endDate]);
      }
    })
    ->orderByDesc('codigoFactura')
    ->get();

    $pdf = PDF::loadView('backend.exports.pdf.sale-list',compact('saleList'));
    $pdf->setPaper('a4','landscape');
    return $pdf->download('Lista de venta.pdf');
  }

  public function searchToSynchronize(Request $request) {
    if($request->synchronizeBy === 'document' && $request->serieComprobante && $request->nroComprobante) {
      return Sale::where('codigoFactura', "$request->serieComprobante-$request->nroComprobante")->first();
    }
    return response()->json([]);
  }

  public function generateDocuments(StoreGenerateDocumentsRequest $request) {
    $sale = Sale::find($request->id);
    $sale->update($request->only([
      'stateDelivery',
      'warrantyStartDate',
      'warrantyPeriod',
      'warrantyPeriodQuantity',
    ]));

    SeguimientoComprobante::where('idSale', $request->id)->update([
      'isActiveTracking' => 1,
      'codigoUnidadEjecutora' => $sale->codigoUnidadEjecutora ?? NULL,
      'nroSiaf' => $sale->nroSiaf ?? NULL,
      'actionesObservaciones' => $sale->descripcionBienServicio ?? 'Sin observación'
    ]);

    return $sale;
  }

}

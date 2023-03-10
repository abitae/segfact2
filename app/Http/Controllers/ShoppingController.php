<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\StoreShopping;
use App\Http\Requests\UpdateShopping;
use App\Shopping;
use Exception;
use Illuminate\Http\Request;
use App\Exports\ShoppingExport;
use App\ShoppingSeries;
use Barryvdh\DomPDF\Facade as PDF;

class ShoppingController extends Controller
{
  public function listShopping(Request $request)
  {
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

    if($request->fechaEmisionSearch) {
      $filters[] = ['fechaEmision','=',"$request->fechaEmisionSearch"];
    }

    $listShoppings = Shopping::with(['proveedor','tipoComprobante','detail','detail.detailSeries','detail.detailSeries.Serie'])->whereHas('proveedor', function ($model) use ($request) {
      if($request->nroDocumentSupplierSearch) {
        $model->where('nroDocument','LIKE',"%$request->nroDocumentSupplierSearch%");
      }
    })->where($filters)->orderByDesc('codigoFactura')->paginate(10);

    return $listShoppings;
  }

  public function data(Request $request)
  {
    $sale = Shopping::with(['proveedor','tipoComprobante'])->where('codigoFactura',$request->nroFactura)->first();
    if(!$sale) throw new Exception("La venta con el N° Factura $request->nroFactura no esta registrado.");
    return $sale;
  }

  public function searchToSynchronize(Request $request)
  {
    $filters = [];
    if($request->synchronizeBy == 'document' && $request->serieComprobante && $request->nroComprobante) {
      $filters[] = ['codigoFactura','LIKE',$request->serieComprobante.'-'.$request->nroComprobante];
    }

    $listShopping = Shopping::where($filters)
    ->where(function($model) use ($request) {
      if($request->synchronizeBy == 'oneDay' && $request->dateStart) {
        $model->where('fechaEmision',$request->dateStart);
      }
      if($request->synchronizeBy == 'oneToMoreDays' && $request->dateStart && $request->dateEnd) {
        $model->whereBetween('fechaEmision',[$request->dateStart,$request->dateEnd]);
      }
    })->get('codigoFactura');

    return $listShopping;
  }

  public function store(StoreShopping $request)
  {
    $shopping = new Shopping;
    $shopping->codigoFactura = $request->codigoFactura;
    $shopping->idSupplier = $request->idSupplier;
    $shopping->idUsuario = $request->idUsuario;
    $shopping->descripcionBienServicio = $request->descripcionBienServicio;
    $shopping->fechaEmision = $request->fechaEmision;
    $shopping->idTipoComprobante = $request->idTipoComprobante;
    $shopping->compraSolesDolares = $request->compraSolesDolares;
    $shopping->monto = $request->monto;
    $shopping->tipoDeCambio = $request->tipoDeCambio;
    $shopping->montoVentaSoles = $request->montoVentaSoles;
    $shopping->montoVentaDolares = $request->montoVentaDolares;
    $shopping->igv = $request->igv;
    $shopping->montoTotal = $request->montoTotal;
    $shopping->save();
    $this->saveListSeries($shopping->id, $request->listSeries);
    return $shopping;
  }

  public function update(UpdateShopping $request)
  {
    $shopping = Shopping::find($request->id);
    $shopping->codigoFactura = $request->codigoFactura;
    $shopping->idSupplier = $request->idSupplier;
    $shopping->idUsuario = $request->idUsuario;
    $shopping->descripcionBienServicio = $request->descripcionBienServicio;
    $shopping->fechaEmision = $request->fechaEmision;
    $shopping->idTipoComprobante = $request->idTipoComprobante;
    $shopping->compraSolesDolares = $request->compraSolesDolares;
    $shopping->monto = $request->monto;
    $shopping->tipoDeCambio = $request->tipoDeCambio;
    $shopping->montoVentaSoles = $request->montoVentaSoles;
    $shopping->montoVentaDolares = $request->montoVentaDolares;
    $shopping->igv = $request->igv;
    $shopping->montoTotal = $request->montoTotal;
    $shopping->save();

    if($request->listSeriesAdded) {
      $this->saveListSeries($shopping->id, $request->listSeriesAdded);
    }
    return $shopping;
  }

  public function saveListSeries($idShopping, $listSeries) {
    for ($i=0; $i < count($listSeries); $i++) {
      $serie = new ShoppingSeries();
      $serie->idSale = $idShopping;
      $serie->idSerie = $listSeries[$i];
      $serie->save();
    }
  }

  public function deleteSerie(Request $request)
  {
    ShoppingSeries::whereId($request->id)->delete();
    return response()->json('Deleted');
  }

  public function exportExcel(Request $request)
  {
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
    if($request->fechaEmisionSearch) {
      $filters[] = ['fechaEmision','=',"$request->fechaEmisionSearch"];
    }

    $listShoppings = Shopping::with(['proveedor','tipoComprobante'])->whereHas('proveedor', function ($model) use ($request) {
      if($request->nroDocumentSupplierSearch) {
        $model->where('nroDocument','LIKE',"%$request->nroDocumentSupplierSearch%");
      }
    })->where($filters)->orderByDesc('id')->get();

    return Excel::download(new ShoppingExport($listShoppings), 'Lista de compras.xlsx');
  }

  public function exportPdf(Request $request)
  {
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
    if($request->fechaEmisionSearch) {
      $filters[] = ['fechaEmision','=',"$request->fechaEmisionSearch"];
    }

    $shoppingList = Shopping::with(['proveedor','tipoComprobante'])->whereHas('proveedor', function ($model) use ($request) {
      if($request->nroDocumentSupplierSearch) {
        $model->where('nroDocument','LIKE',"%$request->nroDocumentSupplierSearch%");
      }
    })->where($filters)->orderByDesc('id')->get();

    $pdf = PDF::loadView('backend.exports.pdf.shopping-list',compact('shoppingList'));
    $pdf->setPaper('a4','landscape');
    return $pdf->download('Lista de compra.pdf');
  }

}

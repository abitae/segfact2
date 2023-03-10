<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSeries;
use App\Http\Requests\UpdateSeries;
use App\Sale;
use App\SaleDetail;
use App\SaleSeries;
use App\Series;
use App\Shopping;
use App\ShoppingDetail;
use App\ShoppingSeries;
use Exception;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
  public function listSeries(Request $request) {
    $filters = [];
    if($request->nroSerie) {
      $filters[] = ['nroSerie','LIKE',"%$request->nroSerie%"];
    }
    $listSeries = Series::where($filters)->orderByDesc('id')->paginate(10);
    return $listSeries;
  }

  public function searchByNameSerie(Request $request) {
    $serie = Series::where('nroSerie',$request->nroSerie)->first();
    if(!$serie) throw new Exception("No existe una serie $request->nroSerie.");
    return $serie;
  }

  public function searchAdvanced(Request $request)
  {
    $serie = Series::where('nroSerie',$request->nroSerie)->first();
    if(!$serie) throw new Exception("No existe una serie $request->nroSerie.");

    $data = [];
    $data['serie'] = $serie;

    if($serie->idShopping) {
      $shopping = Shopping::whereId($serie->idShopping)->first();
      $shopping->load(['proveedor','tipoComprobante','detail','detail.detailSeries','detail.detailSeries.Serie']);
      $data['shopping'] = $shopping;
    } else {
      $shoppingSeries = ShoppingSeries::where('idSerie',$serie->id)->first();
      if($shoppingSeries) {
        $shoppingDetail = ShoppingDetail::where('id', $shoppingSeries->idSale)->first();
        $shopping = Shopping::whereId($shoppingDetail->idShopping)->first();
        $shopping->load(['proveedor','tipoComprobante','detail','detail.detailSeries','detail.detailSeries.Serie']);
        $data['shopping'] = $shopping;
      }
    }

    if($serie->idSale) {
      $sale = Sale::whereId($serie->idSale)->first();
      $sale->load(['cliente','tipoComprobante','vendedor','ventaDetalle', 'ventaDetalle.listSeries','ventaDetalle.listSeries.serie']);
      $data['sale'] = $sale;
    } else {
      $saleSerie = SaleSeries::where('idSerie',$serie->id)->first();
      if($saleSerie) {
        $saleDetail = SaleDetail::whereId($saleSerie->idSaleDetail)->first();
        if($saleDetail) {
          $sale = Sale::whereId($saleDetail->idSale)->first();
          $sale->load(['cliente','tipoComprobante','vendedor','ventaDetalle', 'ventaDetalle.listSeries','ventaDetalle.listSeries.serie']);
          $data['sale'] = $sale;
        }
      }
    }


    return response()->json($data);
  }

  public function store(StoreSeries $request) {
    return $this->save($request->input());
  }

  public function save($data, $idShopping = null, $idSale = null) {
    $serie = new Series();
    $serie->nroSerie = $data->nroSerie;
    $serie->idShopping = $idShopping;
    $serie->idSale = $idSale;
    $serie->save();
    return $serie->id;
  }

  public function update(UpdateSeries $request)
  {
    $serie = Series::find($request->id);
    $serie->nroSerie = $request->nroSerie;
    $serie->save();
    return $serie;
  }

  public function updateColumnIdSale($idSerie, $idSale) {
    $serie = Series::find($idSerie);
    $serie->idSale = $idSale;
    $serie->save();
  }
}

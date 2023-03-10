<?php

namespace App\Http\Controllers;

use App\SaleDetail;
use Illuminate\Http\Request;

class SaleDetailController extends Controller
{
  public function save($data, $idSale) {
    $saleDetail = new SaleDetail();
    $saleDetail->idSale = $idSale;
    $saleDetail->cantidad = $data->cantidad;
    $saleDetail->descripcion = $data->descripcion;
    $saleDetail->precioUnitario = $data->precio;
    $saleDetail->importe = $data->importe + $data->igv;
    $saleDetail->save();
    return $saleDetail->id;
  }

}

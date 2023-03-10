<?php

namespace App\Http\Controllers;

use App\SaleSeries;
use Illuminate\Http\Request;

class SaleSeriesController extends Controller
{
  public $SERIESCONTROLLER;

  public function __construct() {
    $this->SERIESCONTROLLER = new SeriesController();
  }

  public function save($data, $idSaleDetail = null, $idSale = null) {
    $idSerie = $data->id;
    if($idSerie) {
      $this->SERIESCONTROLLER->updateColumnIdSale($idSerie, $idSale);
    } else {
      $idSerie = $this->SERIESCONTROLLER->save($data,null,$idSale);
    }
    $saleSerie = new SaleSeries();
    $saleSerie->idSaleDetail = $idSaleDetail;
    $saleSerie->idSerie = $idSerie;
    $saleSerie->save();

    return $saleSerie->id;
  }
}

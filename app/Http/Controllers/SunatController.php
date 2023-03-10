<?php

namespace App\Http\Controllers;

use App\Sunat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class SunatController extends Controller
{
  public function tipoCambio() {
    $sunat = Sunat::first();
    $today = Carbon::now()->format('Y-m-d');
    if($sunat && Carbon::parse($sunat->today)->format('Y-m-d') == Carbon::now()->format('Y-m-d')) return $sunat;
    $response = Http::withoutVerifying()->get('https://api.apis.net.pe/v1/tipo-cambio-sunat?fecha=2021-11-11');

    if($sunat) {
      $registerUpdated = Sunat::first();
      $registerUpdated->tipoCambioCompra = $response['compra'];
      $registerUpdated->tipoCambioVenta = $response['venta'];
      $registerUpdated->today = $response['fecha'];
      $registerUpdated->save();
      return $registerUpdated;
    } else {
      $registerCreated = new Sunat;
      $registerCreated->tipoCambioCompra = $response['compra'];
      $registerCreated->tipoCambioVenta = $response['venta'];
      $registerCreated->today = $response['fecha'];
      $registerCreated->save();
      return $registerCreated;
    }
  }
}

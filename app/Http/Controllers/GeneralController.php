<?php

namespace App\Http\Controllers;

use App\DocumentState;
use App\Enterprise;
use App\Sale;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;

class GeneralController extends Controller
{
  public function updateStatus(Request $request) {
    if(!$request->ajax()) throw new Exception("You'ave not permission");
    DB::table($request->table)
      ->where('id', $request->id)
      ->update(['is_active' => $request->is_active ]);
    return true;
  }

  public function isUserCheck() {
    return Auth::check();
  }

  public function listAll(Request $request) {
    if(!$request->ajax()) throw new Exception("You'ave not permission");
    return DB::table($request->table)->get();
  }

  public function listAllActives(Request $request) {
    if(!$request->ajax()) throw new Exception("You'ave not permission");
    return DB::table($request->table)->where('is_active',1)->get();
  }

  public function generateDocumentCci(Request $request) {
    $sale = Sale::with(['enterprise','cliente'])->whereId($request->idSale)->first();
    $nroComprobante = $sale->codigoFactura;
    $nameFile = "Documento_CCI_$nroComprobante.pdf";
    return PDF::loadView('backend.pdf-templates.document-cci', compact('nameFile','sale'))->stream($nameFile);
  }

  public function generateDocumentLetterWarranty(Request $request) {
    $sale = Sale::with(['enterprise','cliente','ventaDetalle', 'ventaDetalle.listSeries', 'ventaDetalle.listSeries.serie'])->whereId($request->idSale)->first();
    $nroComprobante = $sale->codigoFactura;
    $nameFile = "Carta_de_garantía_$nroComprobante.pdf";
    return PDF::loadView('backend.pdf-templates.document-letter-warranty', compact('nameFile','sale'))->stream($nameFile);
  }

  public function getDocumentStates(Request $request) {
    $list = DocumentState::orderBy('order')->get();
    return $list;
  }
}

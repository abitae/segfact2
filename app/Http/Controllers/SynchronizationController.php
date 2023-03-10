<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Enterprise;
use App\Http\Requests\StoreSale;
use App\Http\Requests\StoreSynchronizedShopping;
use App\Sale;
use App\Series;
use App\Shopping;
use App\ShoppingDetail;
use App\ShoppingSeries;
use App\Supplier;
use Carbon\Carbon;
use stdClass;

class SynchronizationController extends Controller
{
  public $SALEDETAILCONTROLLER,
  $SALESERIESCONTROLLER,
  $BRANCHOFFICECONTROLLER,
  $CUSTOMERCONTROLLER,
  $SEGUIMIENTOCOMPROBANTECONTROLLER,
  $OPERATIONVOUCHERTRACKINGCONTROLLER,
  $LICENSECONTROLLER;

  public function __construct() {
    $this->SALEDETAILCONTROLLER = new SaleDetailController();
    $this->SALESERIESCONTROLLER = new SaleSeriesController();
    $this->BRANCHOFFICECONTROLLER = new BranchOfficeController();
    $this->CUSTOMERCONTROLLER = new CustomerController();
    $this->SEGUIMIENTOCOMPROBANTECONTROLLER = new SeguimientoComprobanteController();
    $this->OPERATIONVOUCHERTRACKINGCONTROLLER = new OperationVoucherTrackingController();
    $this->LICENSECONTROLLER = new LicenseController();
  }

  public function index() {
    return view('backend.synchronization');
  }

  public function synchronizePurchases(StoreSynchronizedShopping $request) {
    foreach ($request->listPucharse as $pucharse) {
      $shopping = new Shopping;
      $shopping->codigoFactura = $pucharse['serie_comprobante'] . "-" . $pucharse['numero_comprobante'];
      $idSupplier = 0;
      if (isset($pucharse['proveedor']['id'])) {
        $idSupplier = $pucharse['proveedor']['id'];
      } else {
        $idSupplier = $this->storeSupplier($pucharse['proveedor']);
      }
      $shopping->idSupplier = $idSupplier;
      $shopping->idUsuario = $request->idUser;
      $shopping->idEnterprise = $request->idEnterprise;
      $shopping->descripcionBienServicio = 'Compra';
      $shopping->fechaEmision = Carbon::parse($pucharse['fecha_registro']);
      $shopping->idTipoComprobante = $pucharse['id_tipodoc_electronico'];
      $shopping->compraSolesDolares = $pucharse['id_codigomoneda'];
      $shopping->monto = 0.00;
      $shopping->tipoDeCambio = $pucharse['tipo_cambio_sunat'];
      $shopping->montoVentaSoles = $pucharse['sub_total'];
      $shopping->montoVentaDolares = 0.00;
      $shopping->igv = $pucharse['total_igv'];
      $shopping->montoTotal = $pucharse['total'];
      $shopping->save();

      $this->saveDetailShopping($shopping->id, $pucharse['detalle_compra']);
    }
  }

  public function storeSupplier($supplier) {
    $hasSupplier = Supplier::where('nroDocument', $supplier['num_doc'])->first();
    if ($hasSupplier) return $hasSupplier->id;
    $newSupplier = new Supplier();
    $newSupplier->idShoppingMyCompany = $supplier['id_proveedor'];
    $newSupplier->typeDocument = str_pad($supplier['id_tipodocidentidad'], 2, "0", STR_PAD_LEFT);
    $newSupplier->nroDocument = $supplier['num_doc'];
    $newSupplier->fullName = $supplier['razon_social'];
    $newSupplier->address = $supplier['direccion_fiscal'];
    $newSupplier->email = '';
    $newSupplier->nroPhone = '';
    $newSupplier->save();

    return $newSupplier->id;
  }

  public function saveDetailShopping($idShopping, $detailShopping) {
    foreach ($detailShopping as $detail) {
      $shoppingDetail = new ShoppingDetail();
      $shoppingDetail->idShopping = $idShopping;
      $shoppingDetail->cantidad = intval($detail['cantidad']);
      $shoppingDetail->descripcion = $detail['descripcion'];
      $shoppingDetail->preciounitario = $detail['precio'];
      $shoppingDetail->importe = floatval($detail['precio']) + floatval($detail['igv']);
      $shoppingDetail->save();

      if (isset($detail['listSeries']) && count($detail['listSeries'])) {
        $this->saveListSeries($shoppingDetail->id, $detail['listSeries'], $idShopping);
      }
    }
  }

  public function saveListSeries($idDetail, $listSeries, $idShopping) {
    foreach ($listSeries as $serie) {
      $newSerie = new Series();
      $newSerie->idShopping = $idShopping;
      $newSerie->nroSerie = $serie['name'];
      $newSerie->save();

      $newShoppingSerie = new ShoppingSeries();
      $newShoppingSerie->idSale = $idDetail;
      $newShoppingSerie->idSerie = $newSerie->id;
      $newShoppingSerie->save();
    }
  }

  public function synchronizeSales(StoreSale $request) {
    $idEnterprise = Enterprise::where('idEnterpriseMyCompany',$request->idEnterprise)->first()->id;
    $idBranchOffice = $request->idBranchOffice ?? $this->BRANCHOFFICECONTROLLER->save(json_decode($request->branchOffice));
    $idCustomer = $request->idCustomer ?? $this->CUSTOMERCONTROLLER->save(json_decode($request->customer));

    $sale = new Sale();
    $sale->codigoFactura = $request->codigoFactura;
    $sale->idEnterprise = $idEnterprise;
    $sale->idBranchOffice = $idBranchOffice;
    $sale->idCustomer = $idCustomer;
    $sale->idUsuario = $request->idUsuario;
    $sale->descripcionBienServicio = $request->descripcionBienServicio ?? '-';
    $sale->fechaEmision = Carbon::parse($request->fechaEmision)->format('Y-m-d');
    $sale->idTipoComprobante = $request->idTipoComprobante;
    $sale->compraSolesDolares = $request->compraSolesDolares;
    $sale->monto = $request->monto;
    $sale->igv = $request->igv;
    $sale->montoTotal = $request->montoTotal;
    $sale->saleCommission = $request->saleCommission;
    $sale->contactCommission = $request->contactCommission;
    $sale->stateDelivery = 1;
    $currentDate = now()->format('dmYHis');
    if($request->file('attachPurchaseOrderDocument')) {
      $extesion = $request->file('attachPurchaseOrderDocument')->getClientOriginalExtension();
      $nameFile = $request->codigoFactura."_orden_".$currentDate.".".$extesion;
      $sale->attachDocument = $nameFile;
      $request->file('attachPurchaseOrderDocument')->storeAs('/public/sales/pdf/purchase_order_document',$nameFile);
    }
    if($request->file('attachRemissionGuideDocument')) {
      $extesion = $request->file('attachRemissionGuideDocument')->getClientOriginalExtension();
      $nameFile = $request->codigoFactura."_guia_remisión_".$currentDate.".".$extesion;
      $sale->attachRemissionGuideDocument = $nameFile;
      $request->file('attachRemissionGuideDocument')->storeAs('/public/sales/pdf/remission_guide_document',$nameFile);
    }
    // if($request->descripcionBienServicio){
    //   $sale->arranged = 0;
    // }
    $sale->condicionPago = $request->condicionPago;
    $sale->nroGuiaRemision = $request->nroGuiaRemision;
    $sale->nroPucharseOrder = $request->nroPucharseOrder;
    $sale->estadoEnvioSunat = $request->estadoEnvioSunat;
    $sale->codigoUnidadEjecutora = $request->codigoUnidadEjecutora;
    $sale->nroSiaf = $request->nroSiaf;
    $sale->save();

    $idContact = $request->idContact;
    if(!$idContact) {
      $contact = new Contact();
      $contact->typeDocument = '06';
      $contact->fullName = $request->contactFullName;
      $contact->nroPhone = $request->contactNroPhone;
      $contact->idCustomer = $idCustomer;
      $contact->save();
      $idContact = $contact->id;
    }

    $datails = json_decode($request->saleDetail);
    $this->storeSaleDetail($sale->id, $datails, $idCustomer, $idContact);

    $this->SEGUIMIENTOCOMPROBANTECONTROLLER->save(json_decode(json_encode([
      'nroFacturaVenta' => $request->codigoFactura,
      'idCustomer' => $idCustomer,
      'idEnterprise' => $idEnterprise,
      'idSale' => $sale->id,
      'idUsuario' => $request->idUsuario,
      'idBranchOffice' => $idBranchOffice,
      'detraccion' => 0,
      'retencion' => 0,
      'arranged' => $request->descripcionBienServicio ? 0 : 1,
      'monto' => $request->montoTotal,
      'montoTotal' => $request->montoTotal,
      'descripcionBienServicio' => $request->descripcionBienServicio ?? '-',
      'fechaEmision' => Carbon::parse($request->fechaEmision)->format('Y-m-d'),
      'estadoDocumento' => 1,
      'idContact' => $idContact,
      'actionesObservaciones' => $request->descripcionBienServicio ?? '-'
    ])));


    $this->OPERATIONVOUCHERTRACKINGCONTROLLER->save([
      'nroComprobante' => $request->codigoFactura,
      'state' => 1,
      'idUser' => $request->idUsuario
    ], $sale->id);

    return $sale;
  }

  public function storeSaleDetail($idSale, $listDetails, $idCustomer, $idContact) {
    foreach ($listDetails as $detail) {
      $idSaleDetail = $this->SALEDETAILCONTROLLER->save($detail, $idSale);
      if(isset($detail->licenses)) {
        $license = new stdClass;
        $license->idSaleDetail = $idSaleDetail;
        $license->product = $detail->descripcion;
        $license->description = $detail->licenses->description;
        $license->quantity = $detail->licenses->quantityLicenses;
        $license->installationDate = $detail->licenses->installationDate;
        $license->idClient = $idCustomer;
        $license->idContact = $idContact;
        $this->LICENSECONTROLLER->save($license);
      }
      $this->storeSeriesToSaleDetail($idSale, $idSaleDetail, $detail->listSeries);
    }
  }

  public function storeSeriesToSaleDetail($idSale, $idSaleDetail, $series) {
    foreach ($series as $serie) {
      $this->SALESERIESCONTROLLER->save($serie, $idSaleDetail, $idSale);
    }
  }
}

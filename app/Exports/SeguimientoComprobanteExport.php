<?php

namespace App\Exports;

use App\SeguimientoComprobante;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SeguimientoComprobanteExport implements FromView
{
    private $listTrackingReceipts;

    public function __construct($listTrackingReceipts) {
      $this->listTrackingReceipts = $listTrackingReceipts;
    }

    public function view(): View
    {
      return view('backend.exports.excel.tracking-receipts-list', [
        'listTrackingReceipts' => $this->listTrackingReceipts
      ]);
    }
}

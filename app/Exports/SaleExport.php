<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SaleExport implements FromView
{

    private $saleList;

    public function __construct($saleList)
    {
      $this->saleList = $saleList;
    }

    public function view(): View
    {
      return view('backend.exports.excel.sale-list', [
        'saleList' => $this->saleList
      ]);
    }
}

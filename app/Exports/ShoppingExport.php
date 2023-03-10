<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class ShoppingExport implements FromView
{
  private $shoppingList;

  public function __construct($shoppingList)
  {
    $this->shoppingList = $shoppingList;
  }

  public function view(): View
  {

    return view('backend.exports.excel.shopping-list', [
      'shoppingList' => $this->shoppingList
    ]);
  }
}

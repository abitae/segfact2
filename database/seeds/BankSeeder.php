<?php

use App\Bank;
use Illuminate\Database\Seeder;

use function GuzzleHttp\Promise\each;

class BankSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {

    if(Bank::all()->count()) {
      Bank::truncate();
    }
    $banks = [
      'Banco de Crédito del Perú'
      // 'Banco de Comercio',
      // 'Interbank',
      // 'BBVA',
      // 'Banco Interamericano de Finanzas (BanBif)',
      // 'Banco Pichincha',
      // 'Citibank Perú',
      // 'MiBanco',
      // 'Scotiabank Perú',
      // 'Banco GNB Perú',
      // 'Banco Falabella',
      // 'Banco Ripley',
      // 'Banco Santander Perú',
      // 'Alfin Banco',
      // 'Bank of China',
      // 'ICBC PERU BANK',
      // 'Amérika',
      // 'Crediscotia',
      // 'Confianza',
      // 'Compartamos',
      // 'Credinka',
      // 'Efectiva',
      // 'Proempresa',
      // 'Mitsui Auto Finance',
      // 'Oh!',
      // 'Qapaq',
      // 'TFC'
    ];

    foreach ($banks as $bankName) {
      Bank::create(['name' => $bankName]);
    }

  }
}

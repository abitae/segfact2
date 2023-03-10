<?php

use App\TypeDocument;
use Illuminate\Database\Seeder;

class TypeDocumentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      TypeDocument::truncate();

      $typeDocumentOne = new TypeDocument;
      $typeDocumentOne->codigo = '01';
      $typeDocumentOne->nombre = 'Factura';
      $typeDocumentOne->save();

      $typeDocumentTwo = new TypeDocument;
      $typeDocumentTwo->codigo = '03';
      $typeDocumentTwo->nombre = 'Boleta de venta';
      $typeDocumentTwo->save();

      // $typeDocumentTree = new TypeDocument;
      // $typeDocumentTree->codigo = '07';
      // $typeDocumentTree->nombre = 'Nota de credito';
      // $typeDocumentTree->save();

      // $typeDocumentFour = new TypeDocument;
      // $typeDocumentFour->codigo = '08';
      // $typeDocumentFour->nombre = 'Nota de debito';
      // $typeDocumentFour->save();

    }
}

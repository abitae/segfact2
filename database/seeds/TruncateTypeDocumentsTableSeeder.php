<?php

use App\DocumentState;
use Illuminate\Database\Seeder;

class TruncateTypeDocumentsTableSeeder extends Seeder
{
  public function run()
  {
    DocumentState::truncate();

    $documentStateOne = new DocumentState;
    $documentStateOne->nombre = 'Emitido';
    $documentStateOne->descripcion = 'Comprobante emitido';
    $documentStateOne->save();

    $documentStateTwo = new DocumentState;
    $documentStateTwo->nombre = 'Comprometido';
    $documentStateTwo->descripcion = 'días por vencer';
    $documentStateTwo->save();

    $documentStateTree = new DocumentState;
    $documentStateTree->nombre = 'Devengado';
    $documentStateTree->descripcion = 'días por vencer';
    $documentStateTree->save();

    $documentStateOne = new DocumentState;
    $documentStateOne->nombre = 'Girado';
    $documentStateOne->descripcion = 'Comprobante pagado';
    $documentStateOne->save();

    $documentStateFour = new DocumentState;
    $documentStateFour->nombre = 'Anulado';
    $documentStateFour->descripcion = 'Comprobante anulado';
    $documentStateFour->save();
  }
}

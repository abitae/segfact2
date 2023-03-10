<?php

use App\DocumentState;
use Illuminate\Database\Seeder;

class DocumentStateTableSeeder extends Seeder
{
  public function run()
  {
    // $documentStateOne = new DocumentState;
    // $documentStateOne->nombre = 'Emitido';
    // $documentStateOne->descripcion = 'Comprobante emitido';
    // $documentStateOne->order = 1;
    // $documentStateOne->save();

    // $documentStateTwo = new DocumentState;
    // $documentStateTwo->nombre = 'Comprometido';
    // $documentStateTwo->descripcion = 'días por vencer';
    // $documentStateTwo->order = 3;
    // $documentStateTwo->save();

    // $documentStateTree = new DocumentState;
    // $documentStateTree->nombre = 'Devengado';
    // $documentStateTree->descripcion = 'días por vencer';
    // $documentStateTree->order = 4;
    // $documentStateTree->save();

    // $documentStateFour = new DocumentState;
    // $documentStateFour->nombre = 'Girado';
    // $documentStateFour->descripcion = 'Comprobante pagado';
    // $documentStateFour->order = 5;
    // $documentStateFour->save();

    // $documentStateFive = new DocumentState;
    // $documentStateFive->nombre = 'Anulado';
    // $documentStateFive->descripcion = 'Comprobante anulado';
    // $documentStateFive->order = 6;
    // $documentStateFive->save();

    $documentStateSix = new DocumentState();
    $documentStateSix->nombre = 'Verificado';
    $documentStateSix->descripcion = 'Comprobante verificado';
    $documentStateSix->order = 2;
    $documentStateSix->save();
  }
}

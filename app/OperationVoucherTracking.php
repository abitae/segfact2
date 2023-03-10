<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OperationVoucherTracking extends Model
{
  public function estado(): HasOne
  {
    return $this->hasOne(DocumentState::class, 'id', 'state');
  }

  public function user(): HasOne
  {
    return $this->hasOne(User::class, 'id', 'idUser');
  }

}

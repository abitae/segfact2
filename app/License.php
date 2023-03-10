<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class License extends Model
{
  public function client(): BelongsTo
  {
      return $this->belongsTo(Customer::class, 'idClient', 'id');
  }

  public function contact(): BelongsTo
  {
      return $this->belongsTo(Contact::class, 'idContact', 'id');
  }

}

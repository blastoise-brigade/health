<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncModel extends Model {

  protected $table = 'sync';

  protected $fillable = array(
    "totalvisits"
  );

}

?>

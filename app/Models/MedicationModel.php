<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicationModel extends Model {

  protected $table = 'sync_medication';

  protected $fillable = array(
    "generic_name",
    "brand_name",
    "total_quantity",
    "city",
    "province",
    "region"
  );

}

?>

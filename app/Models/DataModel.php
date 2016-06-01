<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataModel extends Model {

  protected $table = 'sync_data';

  protected $fillable = array(
    "patient_id",
    "healthcareservice_id",
    "encounter_datetime",
    "city",
    "province",
    "region"
  );

}

?>

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Newss extends Model
{
     protected $table = 'newss';
    protected $primaryKey = 'new_id';
    public $timestamps = false;
    protected $guarded = [];//黑名单

}

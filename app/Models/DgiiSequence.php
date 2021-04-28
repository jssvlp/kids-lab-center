<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DgiiSequence extends Model
{
    use HasFactory;

    protected $fillable = ['sequence','full_sequence','invoice_id','dgii_numbering_config_id'];
}

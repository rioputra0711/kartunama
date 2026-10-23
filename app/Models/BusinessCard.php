<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_filename',
        'file_path',
        'thumbnail_path',
        'name',
        'company',
        'title',
        'phone',
        'office_phone',
        'email',
        'website',
        'address',
        'linkedin',
        'raw_ocr_json',
        'confidence_score',
    ];
}

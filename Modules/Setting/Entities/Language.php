<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{
    use SoftDeletes;
    protected $table    = 'language';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'language_name',
        'language_slug'
    ];
}

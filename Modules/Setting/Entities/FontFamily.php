<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class FontFamily extends Model
{

    use SoftDeletes;
    protected $table    = 'font_family_setting';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'id',
        'font_name',
        'font_link',
        'font_css_code',
    ];



}

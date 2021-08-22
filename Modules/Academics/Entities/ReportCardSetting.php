<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportCardSetting extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'report_card_settings';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'is_table_color',
        'tbl_header_tr_bg_color',
        'tbl_header_tr_font_color',
        'tbl_even_tr_bg_color',
        'tbl_even_tr_font_color',
        'tbl_odd_tr_bg_color',
        'tbl_odd_tr_font_color',
        'tbl_opacity',
        'is_border_color',
        'border_color',
        'border_type',
        'border_width',
        'is_label_color',
        'is_image',
        'label_bg_color',
        'label_font_color',
        'is_watermark',
        'wm_url',
        'wm_opacity',
        'parent_sign',
        'teacher_sign',
        'auth_sign',
        'auth_name',
        'campus',
        'institute',
    ];
}

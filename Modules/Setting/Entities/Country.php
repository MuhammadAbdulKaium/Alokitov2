<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;
    protected $table      = 'setting_country';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [ 'name', 'nationality'];

    // all states
    public function allState()
    {
        return $this->hasMany('Modules\Setting\Entities\State', 'country_id', 'id')->get();
    }
}

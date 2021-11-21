<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    use SoftDeletes;

    public static function adminBoot()
    {
        if(Auth::check()) {
            $person_id = Auth::user()->id;
            $institute_id = session()->get('institute');
            $campus_id = session()->get('campus');
            self::bootAction($person_id, $institute_id, $campus_id);
        }
    }


    public static function bootAction($person_id, $institute_id, $campus_id)
    {
        parent::boot();

        static::creating(function($model) use ($person_id, $institute_id, $campus_id)
        {
            $model->institute_id = $institute_id;
            $model->campus_id = $campus_id;
            $model->created_by = $person_id;
        });

        static::updating(function($model) use ($person_id)
        {
            $model->updated_by = $person_id;
        });

        static::deleting(function($model) use ($person_id)
        {
            $model->deleted_by = $person_id;
            $model->update();
        });
    }
}

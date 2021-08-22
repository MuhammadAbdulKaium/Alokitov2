<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\InventoryHelper;

class CadetInventoryVoucher extends Model
{
	use InventoryHelper;
    protected $fillable = ['voucher_name'];
    protected $table='cadet_voucher_config';

    public function scopeModule($query)
    {
        return $query->where('cadet_voucher_config.campus_id', self::getCampusId())->where('cadet_voucher_config.institute_id', self::getInstituteId());

    }

}

<?php

namespace Modules\Inventory\Entities\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class StockMasterImport implements ToCollection, WithStartRow
{

    public $data;
    public function collection(Collection $rows)
    {
        $this->data =[];
        foreach ($rows as $row) 
        {
            if(!empty($row['0'])){
                $this->data[] = [
                    'product_name'=>trim($row[1]),
                    'alias'=>trim($row[2]),
                    'product_description'=>(!empty($row[3]))?trim($row[3]):'N/D',
                    'sku'=>trim($row[4]),
                    'unit'=>(!empty($row[5]))?strtolower(trim($row[5])):'',
                    'code_type_id'=>(!empty($row[6]))?trim($row[6]):'',
                    'stock_group'=>(!empty($row[7]))?strtolower(trim($row[7])):'',
                    'category_id'=>(!empty($row[8]))?strtolower(trim($row[8])):'',
                    'warrenty_month'=>trim($row[9]),
                    'min_stock'=>(!empty($row[10]))?trim($row[10]):0,
                    'reorder_qty'=>trim($row[11]),
                    'store_tagging'=>trim($row[12]),
                    'additional_remarks'=>trim($row[13]),
                    'has_fraction'=>(!empty($row[14]))?trim($row[14]):'No',
                    'decimal_point_place'=>trim($row[15]),
                    'round_of'=>trim($row[16]),
                    //'item_type'=>$row[1],
                ];
            }
            
        }

    }
    public function startRow(): int
    {
        return 2;
    }
}
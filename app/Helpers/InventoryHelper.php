<?php 
	namespace App\Helpers;
	use Modules\Inventory\Entities\NewRequisitionInfoModel;
	use Modules\Inventory\Entities\StockInModel;
	use Modules\Inventory\Entities\StockOutModel;
	use Modules\Inventory\Entities\CadetInventoryVoucher;
	use Modules\Inventory\Entities\StoreWiseItemModel;
	use Modules\Inventory\Entities\CadetInventoryProduct;
	use Modules\Inventory\Entities\UserVoucherApprovalLayerModel;
	use Modules\Inventory\Entities\AllStockInModel;
	use Modules\Inventory\Entities\IssueFromInventoryModel;
	use Modules\Inventory\Entities\PurchaseRequisitionInfoModel;
	use Modules\Inventory\Entities\ComparativeStatementInfoModel;
	use Modules\Inventory\Entities\PurchaseOrderInfoModel;
	use Modules\Inventory\Entities\PurchaseReceiveInfoModel;
	use Modules\Inventory\Entities\PurchaseInvoiceModel;
	use DB;
	use App\User;
	use App\UserInfo;
	use App\UserStorePermissionModel;
	use App\UserVoucherApprovalModel;
	use Auth;
	use Illuminate\Support\Str;
	trait InventoryHelper {
		public static function getInstituteId(){
			return session()->get('institute');
		}
		public static function getCampusId(){
			return session()->get('campus');
		}
		public static function isFloat($number){ // check float and decimal places is greater then 0
			if(gettype($number)=='string'){
				if(Str::contains($number,'.')){
					$explode = explode('.', $number);
					return (@$explode[1]>0)?true:false;
				}else{
					return false;
				}
			}else{
				if(is_float($number)){
					$explode = explode('.', $number);
					return (@$explode[1]>0)?true:false;
				}else{
					return false;
				}
			}
		}

		public static function UserAccessStore($user_id){
			$AccessStore = UserStorePermissionModel::module()->where('user_id', $user_id)->get()->pluck('store_id')->all(); 
			return $AccessStore; 
		}

		public static function getItemList($that){
			$itemList = StoreWiseItemModel::access($that)->valid()
				->select('inventory_store_wise_item.item_id', 'cadet_stock_products.product_name', 'cadet_inventory_uom.symbol_name as uom', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'))
				->join('cadet_stock_products', 'cadet_stock_products.id', 'inventory_store_wise_item.item_id')
				->join('cadet_inventory_uom', 'cadet_inventory_uom.id', 'cadet_stock_products.unit')
				->where('cadet_stock_products.item_type',1)
				->orderBy('product_name', 'asc')
				->groupBy(['inventory_store_wise_item.item_id', 'cadet_stock_products.product_name', 'uom','decimal_point_place'])
				->get();
			return $itemList;

   		}

		public static function itemList($that){
			$itemList = StoreWiseItemModel::access($that)->valid()
				->select('inventory_store_wise_item.item_id', 'cadet_stock_products.product_name', 'cadet_inventory_uom.symbol_name as uom','cadet_stock_products.sku', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place, ifnull(cadet_stock_products.round_of, 0) as round_of'), 'has_fraction')
				->join('cadet_stock_products', 'cadet_stock_products.id', 'inventory_store_wise_item.item_id')
				->join('cadet_inventory_uom', 'cadet_inventory_uom.id', 'cadet_stock_products.unit')
				->where('cadet_stock_products.item_type',1)
				->orderBy('product_name', 'asc')
				->groupBy(['inventory_store_wise_item.item_id', 'cadet_stock_products.product_name', 'uom','sku','decimal_point_place','round_of','has_fraction'])
				->get();
			return $itemList;

   		}

   		public static function storeWiseItemList($store_id){
			$itemList = StoreWiseItemModel::module()->valid()
	            ->select('inventory_store_wise_item.item_id', 'cadet_stock_products.product_name', 'cadet_inventory_uom.symbol_name as uom','cadet_stock_products.sku', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place, ifnull(cadet_stock_products.round_of, 0) as round_of, ifnull(inventory_store_wise_item.current_stock, 0) as current_stock, ifnull(inventory_store_wise_item.avg_cost_price, 0) as avg_cost_price'), 'has_fraction','use_serial')
	            ->join('cadet_stock_products', 'cadet_stock_products.id', 'inventory_store_wise_item.item_id')
	            ->join('cadet_inventory_uom', 'cadet_inventory_uom.id', 'cadet_stock_products.unit')
	            ->where('inventory_store_wise_item.store_id', $store_id)
	            ->where('cadet_stock_products.item_type',1)
	            ->orderBy('product_name', 'asc')
	            ->get();
			return $itemList;

   		}

   		public static function getUserList(){
   			$userList = UserInfo::module()
   				->select('user_institution_campus.user_id as id', 'users.name')
   				->join('users', 'user_institution_campus.user_id', 'users.id')
   				->get();
	        $superAdminId = DB::table('roles')->where('name', 'super-admin')->first()->id;
	        if(!empty($superAdminId)){
	          $superAdminList = DB::table('role_user')->where('role_id', $superAdminId)->get();
	          $superAdminIds = collect($superAdminList)->pluck('user_id')->all();
	          $superAdminUser = User::select('id', 'name')->whereIn('id', $superAdminIds)->get();
	          if(count($superAdminUser)>0){
	            $mergedUserList = $superAdminUser->merge($userList);
	          }else{
	            $mergedUserList = $userList; 
	          }
	        }else{
	          $mergedUserList = $userList; 
	        }
   			return $mergedUserList;
   		}

   		public static function itemWiseStock($that, $item_ids){
   			$stockItemList = StoreWiseItemModel::access($that)->valid()
   				->select('inventory_store_wise_item.item_id', 'cadet_stock_products.product_name', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place, ifnull(cadet_stock_products.round_of, 0) as round_of, ifnull(inventory_store_wise_item.current_stock, 0) as current_stock, ifnull(inventory_store_wise_item.avg_cost_price, 0) as avg_cost_price'), 'cadet_stock_products.has_fraction')
   				->join('cadet_stock_products', 'cadet_stock_products.id', 'inventory_store_wise_item.item_id')
   				->whereIn('inventory_store_wise_item.item_id', $item_ids)
   				->get();
   			$stockItemGroupBy = $stockItemList->groupBy('item_id')->all();
   			$stockItemKeyBy = $stockItemList->keyBy('item_id')->all();

   			$stocItemData = [];
   			foreach($stockItemGroupBy as $item_id => $itemList){
   				$itemInfo = $stockItemKeyBy[$item_id];
   				if($itemInfo->has_fraction==1){
   					$fractionQty = 0;
   					foreach($itemList as $v){
   						if(self::isFloat($v->current_stock)){
   							$current_stock = round($v->current_stock, $v->decimal_point_place);
   							$explodeStockQty = explode('.', $current_stock);
	                        $roundStockQty = (int) $explodeStockQty[0]; 
	                        $fractionStockQty = (int) $explodeStockQty[1];
	                        
	                        $fractionQty+= $roundStockQty*$v->round_of;
	                    	$fractionQty+=$fractionStockQty; 

   						}else{
   							$fractionQty+=$v->current_stock*$v->round_of;
   						}
   					}
   					$roundQty      = $fractionQty/$itemInfo->round_of;
   					if(self::isFloat($roundQty)){
   						$explodeRoundQty = explode('.', $roundQty);
   						$roundQty = $explodeRoundQty[0];
   					}
		            $remainder     = $fractionQty%$itemInfo->round_of;	
		            $remainder =  str_pad($remainder,$v->decimal_point_place,"0");	            
                    $current_stock = $roundQty.'.'.$remainder;
   				}else{
   					$current_stock = collect($itemList)->sum('current_stock');
   				}
   				$avg_cost_price = collect($itemList)->sum('avg_cost_price');
   				$itemData = ['item_id'=>$item_id, 'current_stock'=>$current_stock,'avg_cost_price'=>$avg_cost_price];
   				$stocItemData[$item_id] = $itemData;
   			}
   			return $stocItemData;
   		}


   		public static function centralItemWiseStock($that, $item_ids){
   			$stockItemList = StoreWiseItemModel::valid()
   				->select('inventory_store_wise_item.item_id', DB::raw('ifnull(sum(avg_cost_price*current_stock), 0) as totalCostPrice, ifnull(sum(current_stock), 0) as totalCurrentStock'))
   				->whereIn('inventory_store_wise_item.item_id', $item_ids)
   				->where(function($query)use($that){
   					if(count($that->AccessStore)>0){
		        		$query->whereIn('inventory_store_wise_item.store_id', $that->AccessStore);
		        	}
   				})
   				->groupBy('item_id')
   				->get();
   			return $stockItemList;
   		}


   		public static function storeStockIncrementAndCostPrice($itemInfo, $approvalData){
   			$flag=true; $msg = '';
   			$decimal_point_place = (!empty($itemInfo->decimal_point_place))?$itemInfo->decimal_point_place:0;
            $storeWiseItemInfo = StoreWiseItemModel::module()->where('item_id', $approvalData->item_id)->where('store_id', $approvalData->store_id)->first();
            $item_store_current_stock = (!empty($storeWiseItemInfo->current_stock))?round($storeWiseItemInfo->current_stock,$decimal_point_place):0;
            $avg_cost_price_db = (!empty($storeWiseItemInfo->avg_cost_price))?$storeWiseItemInfo->avg_cost_price:0;
            $db_qty = round($approvalData->qty, $decimal_point_place);
            if($itemInfo->has_fraction==1){
                if(self::isFloat($db_qty) && self::isFloat($item_store_current_stock)){
                    $explodeQty = explode('.', $db_qty);
                    $roundQty = (int) $explodeQty[0]; 
                    $fractionQty = str_pad($explodeQty[1],$decimal_point_place,"0");
                    $fractionQty = (int) $fractionQty;
                    $explodeStockQty = explode('.', $item_store_current_stock);
                    $roundStockQty = (int) $explodeStockQty[0]; 
                    $fractionStockQty = str_pad($explodeStockQty[1],$decimal_point_place,"0");
                    $fractionStockQty = (int) $fractionStockQty;
                    // avg cost price
                    $privoiusAmount = $roundStockQty*$avg_cost_price_db;
                    $franctionStockPricePer = $avg_cost_price_db/$itemInfo->round_of; 
                    $fractionStockPrice = $fractionStockQty*$franctionStockPricePer; 
                    $privoiusAmount+= $fractionStockPrice;
                    // avg cost price end

                    $roundQtySum = $roundQty+$roundStockQty;
                    $fractionQtySum = $fractionQty+$fractionStockQty;
                    if($fractionQtySum>=$itemInfo->round_of){
                        $roundQtySum+=1;
                        $fractionQtySum = $fractionQtySum-$itemInfo->round_of;
                    }
                    $fractionQtySum = str_pad($fractionQtySum,$decimal_point_place,"0");
                    $current_stock = $roundQtySum.'.'.$fractionQtySum;

                }else{
                    // avg price
                    if(self::isFloat($item_store_current_stock)){
                        $explodeStockQty = explode('.', $item_store_current_stock);
                        $roundStockQty = (int) $explodeStockQty[0]; 
                        $fractionStockQty = str_pad($explodeStockQty[1],$decimal_point_place,"0");
                        $fractionStockQty = (int) $fractionStockQty;
                        // avg cost price
                        $privoiusAmount = $roundStockQty*$avg_cost_price_db;
                        $franctionStockPricePer = $avg_cost_price_db/$itemInfo->round_of; 
                        $franctionStockPrice = $fractionStockQty*$franctionStockPricePer; 
                        $privoiusAmount+= $franctionStockPrice;
                    }else{
                        $privoiusAmount = $item_store_current_stock*$avg_cost_price_db;
                    }
                    // avg cost price end
                    if(self::isFloat($db_qty) || self::isFloat($item_store_current_stock)){
                    	$current_stock = $item_store_current_stock+$approvalData->qty;
                    	if(self::isFloat($current_stock)){
                    		$explodeCurrentStock = explode('.', $current_stock);
                    		$currentStockRoundQty = $explodeCurrentStock[0];
                    		$currentStockFractionQty = str_pad($explodeCurrentStock[1],$decimal_point_place,"0");
                    		$current_stock = $currentStockRoundQty.'.'.$currentStockFractionQty;
                    	}
                    }else{
                    	$current_stock = $item_store_current_stock+$approvalData->qty;
                    }
                }
                $totalAmount = $privoiusAmount+$approvalData->amount;
                $avg_price = round($totalAmount/$current_stock,6);
            }else{
                if(self::isFloat($approvalData->qty)){
                    $flag = false;
                    $msg = $itemInfo->product_name.' has no decimal places'; 
                }else{
                    $privoiusAmount = $item_store_current_stock*$avg_cost_price_db;
                    $totalAmount = $privoiusAmount+$approvalData->amount;
                    $current_stock = $item_store_current_stock+$approvalData->qty;
                    $avg_price = round($totalAmount/$current_stock,6);
                }
            }

            return[
            	'flag'=>$flag,
            	'msg'=>$msg,
            	'avg_price'=>$avg_price,
            	'current_stock'=>$current_stock
            ];
   		}

   		public static function storeStockIncrement($itemInfo, $approvalData){
   			$flag=true; $msg = '';
   			$decimal_point_place = (!empty($itemInfo->decimal_point_place))?$itemInfo->decimal_point_place:0;
            $storeWiseItemInfo = StoreWiseItemModel::module()->where('item_id', $approvalData->item_id)->where('store_id', $approvalData->store_id)->first();
            $item_store_current_stock = (!empty($storeWiseItemInfo->current_stock))?round($storeWiseItemInfo->current_stock,$decimal_point_place):0;
            $db_qty = round($approvalData->qty, $decimal_point_place);
            if($itemInfo->has_fraction==1){
                if(self::isFloat($db_qty) && self::isFloat($item_store_current_stock)){
                    $explodeQty = explode('.', $db_qty);
                    $roundQty = (int) $explodeQty[0]; 
                    $fractionQty = str_pad($explodeQty[1],$decimal_point_place,"0");
                    $fractionQty = (int) $fractionQty;
                    $explodeStockQty = explode('.', $item_store_current_stock);
                    $roundStockQty = (int) $explodeStockQty[0]; 
                    $fractionStockQty = str_pad($explodeStockQty[1],$decimal_point_place,"0");
                    $fractionStockQty = (int) $fractionStockQty;
                    
                    $roundQtySum = $roundQty+$roundStockQty;
                    $fractionQtySum = $fractionQty+$fractionStockQty;
                    if($fractionQtySum>=$itemInfo->round_of){
                        $roundQtySum+=1;
                        $fractionQtySum = $fractionQtySum-$itemInfo->round_of;
                    }
                    $fractionQtySum = str_pad($fractionQtySum,$decimal_point_place,"0");
                    $current_stock = $roundQtySum.'.'.$fractionQtySum;

                }else{
                    if(self::isFloat($db_qty) || self::isFloat($item_store_current_stock)){
                    	$current_stock = $item_store_current_stock+$approvalData->qty;
                    	if(self::isFloat($current_stock)){
                    		$explodeCurrentStock = explode('.', $current_stock);
                    		$currentStockRoundQty = $explodeCurrentStock[0];
                    		$currentStockFractionQty = str_pad($explodeCurrentStock[1],$decimal_point_place,"0");
                    		$current_stock = $currentStockRoundQty.'.'.$currentStockFractionQty;
                    	}
                    }else{
                    	$current_stock = $item_store_current_stock+$approvalData->qty;
                    }
                }
            }else{
                if(self::isFloat($approvalData->qty)){
                    $flag = false;
                    $msg = $itemInfo->product_name.' has no decimal places'; 
                }else{
                    $current_stock = $item_store_current_stock+$approvalData->qty;
                }
            }
            return[
            	'flag'=>$flag,
            	'msg'=>$msg,
            	'current_stock'=>$current_stock
            ];

   		}

   		public static function storeStockDecrement($itemInfo, $approvalData){

   			$flag=true; $msg = '';
   			$decimal_point_place = (!empty($itemInfo->decimal_point_place))?$itemInfo->decimal_point_place:0;
            $storeWiseItemInfo = StoreWiseItemModel::module()->where('item_id', $approvalData->item_id)->where('store_id', $approvalData->store_id)->first();
            $item_store_current_stock = (!empty($storeWiseItemInfo->current_stock))?round($storeWiseItemInfo->current_stock,$decimal_point_place):0;
            $db_qty = round($approvalData->qty, $decimal_point_place);
            if($item_store_current_stock>=$db_qty){
	            if($itemInfo->has_fraction==1){
	                if(self::isFloat($db_qty) && self::isFloat($item_store_current_stock)){
	                    $explodeQty = explode('.', $db_qty);
	                    $roundQty = (int) $explodeQty[0]; 
	                    $fractionQty = str_pad($explodeQty[1],$decimal_point_place,"0");
	                    $fractionQty = (int) $fractionQty;
	                    $db_franction_qty = $roundQty*$itemInfo->round_of;
	                    $db_franction_qty+=$fractionQty;  

	                    $explodeStockQty = explode('.', $item_store_current_stock);
	                    $roundStockQty = (int) $explodeStockQty[0]; 
	                    $fractionStockQty = str_pad($explodeStockQty[1],$decimal_point_place,"0");
	                    $fractionStockQty = (int) $fractionStockQty;

	                    $stock_franction_qty = $roundStockQty*$itemInfo->round_of;
	                    $stock_franction_qty+=$fractionStockQty;  

	                }else{
	                    if(self::isFloat($item_store_current_stock)){
	                        $explodeStockQty = explode('.', $item_store_current_stock);
	                        $roundStockQty = (int) $explodeStockQty[0]; 
	                        $fractionStockQty = str_pad($explodeStockQty[1],$decimal_point_place,"0");
	                        $fractionStockQty = (int) $fractionStockQty;

	                        $stock_franction_qty = $roundStockQty*$itemInfo->round_of;
	                    	$stock_franction_qty+=$fractionStockQty; 
	                    }else{
	                    	$stock_franction_qty = $item_store_current_stock*$itemInfo->round_of;
	                    }

	                    if(self::isFloat($db_qty)){
	                    	$explodeQty = explode('.', $db_qty);
		                    $roundQty = (int) $explodeQty[0]; 
		                    $fractionQty = str_pad($explodeQty[1],$decimal_point_place,"0");
		                    $fractionQty = (int) $fractionQty;
		                    $db_franction_qty = $roundQty*$itemInfo->round_of;
		                    $db_franction_qty+=$fractionQty;  
	                    }else{
	                    	$db_franction_qty = $db_qty*$itemInfo->round_of;	
	                    }
	                }

	                $diffValue     = $stock_franction_qty - $db_franction_qty;
				    $roundQty      = $diffValue/$itemInfo->round_of;
				    if(self::isFloat($roundQty)){
   						$explodeRoundQty = explode('.', $roundQty);
   						$roundQty = $explodeRoundQty[0];
   					}
		            $remainder     = $diffValue%$itemInfo->round_of;
                    $remainder = str_pad($remainder,$decimal_point_place,"0");
                    $current_stock = $roundQty.'.'.$remainder;
	                    
	            }else{
	                if(self::isFloat($approvalData->qty)){
	                    $flag = false;
	                    $msg = $itemInfo->product_name.' has no decimal places'; 
	                }else{
	                    $current_stock = $item_store_current_stock-$approvalData->qty;
	                }
	            }
	        }else{
	        	$flag = false;
	            $msg = $itemInfo->product_name.' has not sufficient stock'; 
	        }

            return[
            	'flag'=>$flag,
            	'msg'=>$msg,
            	'current_stock'=>$current_stock
            ];


   		}

   		public function itemQtySubtraction($itemInfo, $from_qty, $to_qty){
   			$decimal_point_place = (!empty($itemInfo->decimal_point_place))?$itemInfo->decimal_point_place:0;
   			$from_qty = round($from_qty, $decimal_point_place);
   			$to_qty = round($to_qty, $decimal_point_place);

   			if($itemInfo->has_fraction==1){
                if(self::isFloat($from_qty) && self::isFloat($to_qty)){
                    $explodeFromQty = explode('.', $from_qty);
                    $roundFromQty = (int) $explodeFromQty[0]; 
                    $fractionFromQty = str_pad($explodeFromQty[1],$decimal_point_place,"0");
                    $fractionFromQty = (int) $fractionFromQty;
                    $from_franction_qty = $roundFromQty*$itemInfo->round_of;
                    $from_franction_qty+=$fractionFromQty; 


                    $explodeToQty = explode('.', $to_qty);
                    $roundToQty = (int) $explodeToQty[0]; 
                    $fractionToQty = str_pad($explodeToQty[1],$decimal_point_place,"0");
                    $fractionToQty = (int) $fractionToQty;
                    $to_franction_qty = $roundToQty*$itemInfo->round_of;
                    $to_franction_qty+=$fractionToQty; 

                }else{
                    if(self::isFloat($from_qty)){
                        $explodeFromQty = explode('.', $from_qty);
	                    $roundFromQty = (int) $explodeFromQty[0]; 
	                    $fractionFromQty = str_pad($explodeFromQty[1],$decimal_point_place,"0");
	                    $fractionFromQty = (int) $fractionFromQty;
	                    $from_franction_qty = $roundFromQty*$itemInfo->round_of;
	                    $from_franction_qty+=$fractionFromQty; 
                    }else{
                    	$from_franction_qty = $from_qty*$itemInfo->round_of;
                    }

                    if(self::isFloat($to_qty)){
                    	$explodeToQty = explode('.', $to_qty);
	                    $roundToQty = (int) $explodeToQty[0]; 
	                    $fractionToQty = str_pad($explodeToQty[1],$decimal_point_place,"0");
	                    $fractionToQty = (int) $fractionToQty;
	                    $to_franction_qty = $roundToQty*$itemInfo->round_of;
	                    $to_franction_qty+=$fractionToQty; 
                    }else{
                    	$to_franction_qty = $to_qty*$itemInfo->round_of;	
                    }
                }

                $diffValue     = $from_franction_qty - $to_franction_qty;
			    $roundQty      = $diffValue/$itemInfo->round_of;
			    if(self::isFloat($roundQty)){
					$explodeRoundQty = explode('.', $roundQty);
					$roundQty = $explodeRoundQty[0];
				}
	            $remainder     = $diffValue%$itemInfo->round_of;
                $remainder = str_pad($remainder,$decimal_point_place,"0");
                $current_stock = $roundQty.'.'.$remainder;
                    
            }else{
                $current_stock = $from_qty-$to_qty;
            }

            return $current_stock; 


   		}

   		public function rateQtyMultiply($itemInfo){
   			$round_of = $itemInfo->round_of;
   			$decimal_point_place = $itemInfo->decimal_point_place;
   			$qty = $itemInfo->qty;
   			$rate = $itemInfo->rate;
   			$qtyUnitPrice = $rate/$round_of; 

   			$explodeQty = explode('.', $qty);
   			$roundQty = $explodeQty[0]; 
   			$fractionQty = str_pad($explodeQty[1],$decimal_point_place,"0");
   			$amount = $roundQty*$rate;
   			$fractionPrice  = $fractionQty*$qtyUnitPrice;
   			$amount+= round($fractionPrice,6);
   			return $amount;
   		}


   		public function mergeEmtyArryObj($collection, $emptyArray=[]){
   			$newCollection = $collection->all();
            array_unshift($newCollection,$emptyArray);   			
   			return $newCollection;
   		}


   		public function stockOutFifo($itemInfo, $approvalData, $qty){
   			$stockDetails = AllStockInModel::module()->valid()->where('store_id', $approvalData->store_id)->where('item_id', $approvalData->item_id)->where('hand_qty','>',0)->orderBy('id', 'asc')->first();

   			if($stockDetails->hand_qty>=$qty){
   				$remainQty 	= 0;
   				$newHandQty = self::itemQtySubtraction($itemInfo,$stockDetails->hand_qty,$qty);
   			}else{
   				$remainQty  = self::itemQtySubtraction($itemInfo,$qty,$stockDetails->hand_qty); 
   				$newHandQty = 0;
   			}

   			AllStockInModel::module()->valid()->find($stockDetails->id)->update([
   				'hand_qty'=>$newHandQty
   			]);

   			if($remainQty > 0){
   				return self::stockOutFifo($itemInfo,$approvalData,$remainQty);
   			}else{
   				return;
   			}

   		}

   		public static function getApprovalInfo($approval_name){
   			$auth_user_id = Auth::user()->id;
            $UserVoucherApprovalLayer = UserVoucherApprovalLayerModel::module()->valid()->where('approval_name', $approval_name)->get();
            $step=1; $approval_access=true; $last_step = 1; // step and approval access
            if(count($UserVoucherApprovalLayer)>0){
                $UserVoucherApprovalKeyBy = $UserVoucherApprovalLayer->keyBy('approval_id')->all();
                if(array_key_exists($auth_user_id, $UserVoucherApprovalKeyBy)){
                    $step = $UserVoucherApprovalKeyBy[$auth_user_id]->step;
                }else{
                   $approval_access=false; 
                }
                $UserVoucherApprovalLastLayer = UserVoucherApprovalLayerModel::module()->valid()->where('approval_name', $approval_name)->orderBy('step', 'desc')->first();
                $last_step = $UserVoucherApprovalLastLayer->step;
            }
            return [
            	'step'=>$step,
            	'approval_access'=>$approval_access,
            	'last_step'=>$last_step
            ];
   		}

   		public function checkInvVoucher($type){
   			$voucherConfig = CadetInventoryVoucher::module()->where('type_of_voucher', $type)->where('status',1)->first();
   			if(!empty($voucherConfig)){
   				$voucher_config_id = $voucherConfig->id;
   				$voucher_conf = true;
   				if($voucherConfig->numbering=='auto'){
   					$voucher_no='Auto';
   					$auto_voucher=true;
   				}else{
   					$voucher_no='';
   					$auto_voucher=false;
   				}
   			}else{
   				$voucher_conf = false;
   				$voucher_no = '';
   				$auto_voucher = false;
   				$voucher_config_id = 0;
   			}
   			return [
				'voucher_no' => $voucher_no,
				'auto_voucher' =>$auto_voucher,
				'voucher_conf' =>$voucher_conf,
				'voucher_config_id'=>$voucher_config_id
			];
   		}

		public static function getVoucherNo($type){
			if($type=='newreq'){
				$voucherConfig = CadetInventoryVoucher::module()->where('type_of_voucher', 1)->where('status', 1)->where('numbering', 'auto')->first();
				if(!empty($voucherConfig)){
					$checkVoucher = NewRequisitionInfoModel::module()->valid()->where('voucher_config_id', $voucherConfig->id)->orderBy('voucher_int', 'desc')->first();
					if(!empty($checkVoucher)){
						$voucher_int = $checkVoucher->voucher_int+1;
					}else{
						$voucher_int = $voucherConfig->starting_number;
					}
					$voucher_int_fill = str_pad($voucher_int,$voucherConfig->numeric_part,"0", STR_PAD_LEFT);
					$voucher_no = $voucherConfig->prefix.$voucher_int_fill;
					if(!empty($voucherConfig->suffix)) $voucher_no .= $voucherConfig->suffix;
					return [
						'voucher_no' => $voucher_no,
						'voucher_int' =>$voucher_int,
						'voucher_config_id'=>$voucherConfig->id
					];
				}else{
					return [
						'voucher_no' => false,
						'voucher_int' =>false,
						'voucher_config_id'=>0
					];
				}
			}else if($type=='issueFromInventory'){
				$voucherConfig = CadetInventoryVoucher::module()->where('type_of_voucher', 2)->where('status', 1)->where('numbering', 'auto')->first();
				if(!empty($voucherConfig)){
					$checkVoucher = IssueFromInventoryModel::module()->valid()->where('voucher_config_id', $voucherConfig->id)->orderBy('voucher_int', 'desc')->first();
					if(!empty($checkVoucher)){
						$voucher_int = $checkVoucher->voucher_int+1;
					}else{
						$voucher_int = $voucherConfig->starting_number;
					}
					$voucher_int_fill = str_pad($voucher_int,$voucherConfig->numeric_part,"0", STR_PAD_LEFT);
					$voucher_no = $voucherConfig->prefix.$voucher_int_fill;
					if(!empty($voucherConfig->suffix)) $voucher_no .= $voucherConfig->suffix;
					return [
						'voucher_no' => $voucher_no,
						'voucher_int' =>$voucher_int,
						'voucher_config_id'=>$voucherConfig->id
					];
				}else{
					return [
						'voucher_no' => false,
						'voucher_int' =>false,
						'voucher_config_id' =>0
					];
				}
			}else if($type=='stockIn'){
				$voucherConfig = CadetInventoryVoucher::module()->where('type_of_voucher', 12)->where('status', 1)->where('numbering', 'auto')->first();
				if(!empty($voucherConfig)){
					$checkVoucher = StockInModel::module()->valid()->where('voucher_config_id', $voucherConfig->id)->orderBy('voucher_int', 'desc')->first();
					if(!empty($checkVoucher)){
						$voucher_int = $checkVoucher->voucher_int+1;
					}else{
						$voucher_int = $voucherConfig->starting_number;
					}
					$voucher_int_fill = str_pad($voucher_int,$voucherConfig->numeric_part,"0", STR_PAD_LEFT);
					$voucher_no = $voucherConfig->prefix.$voucher_int_fill;
					if(!empty($voucherConfig->suffix)) $voucher_no .= $voucherConfig->suffix;
					return [
						'voucher_no' => $voucher_no,
						'voucher_int' =>$voucher_int,
						'voucher_config_id'=>$voucherConfig->id
					];
				}else{
					return [
						'voucher_no' => false,
						'voucher_int' =>false,
						'voucher_config_id' =>0
					];
				}

			}else if($type=='stockOut'){
				$voucherConfig = CadetInventoryVoucher::module()->where('type_of_voucher', 13)->where('status', 1)->where('numbering', 'auto')->first();
				if(!empty($voucherConfig)){
					$checkVoucher = StockOutModel::module()->valid()->where('voucher_config_id', $voucherConfig->id)->orderBy('voucher_int', 'desc')->first();
					if(!empty($checkVoucher)){
						$voucher_int = $checkVoucher->voucher_int+1;
					}else{
						$voucher_int = $voucherConfig->starting_number;
					}
					$voucher_int_fill = str_pad($voucher_int,$voucherConfig->numeric_part,"0", STR_PAD_LEFT);
					$voucher_no = $voucherConfig->prefix.$voucher_int_fill;
					if(!empty($voucherConfig->suffix)) $voucher_no .= $voucherConfig->suffix;
					return [
						'voucher_no' => $voucher_no,
						'voucher_int' =>$voucher_int,
						'voucher_config_id'=>$voucherConfig->id
					];
				}else{
					return [
						'voucher_no' => false,
						'voucher_int' =>false,
						'voucher_config_id' =>0
					];
				}

			}else if($type=='purReq'){
				$voucherConfig = CadetInventoryVoucher::module()->where('type_of_voucher', 5)->where('status', 1)->where('numbering', 'auto')->first();
				if(!empty($voucherConfig)){
					$checkVoucher = PurchaseRequisitionInfoModel::module()->valid()->where('voucher_config_id', $voucherConfig->id)->orderBy('voucher_int', 'desc')->first();
					if(!empty($checkVoucher)){
						$voucher_int = $checkVoucher->voucher_int+1;
					}else{
						$voucher_int = $voucherConfig->starting_number;
					}
					$voucher_int_fill = str_pad($voucher_int,$voucherConfig->numeric_part,"0", STR_PAD_LEFT);
					$voucher_no = $voucherConfig->prefix.$voucher_int_fill;
					if(!empty($voucherConfig->suffix)) $voucher_no .= $voucherConfig->suffix;
					return [
						'voucher_no' => $voucher_no,
						'voucher_int' =>$voucher_int,
						'voucher_config_id'=>$voucherConfig->id
					];
				}else{
					return [
						'voucher_no' => false,
						'voucher_int' =>false,
						'voucher_config_id' =>0
					];
				}
			}else if($type=='cs'){
				$voucherConfig = CadetInventoryVoucher::module()->where('type_of_voucher', 14)->where('status', 1)->where('numbering', 'auto')->first();
				if(!empty($voucherConfig)){
					$checkVoucher = ComparativeStatementInfoModel::module()->valid()->where('voucher_config_id', $voucherConfig->id)->orderBy('voucher_int', 'desc')->first();
					if(!empty($checkVoucher)){
						$voucher_int = $checkVoucher->voucher_int+1;
					}else{
						$voucher_int = $voucherConfig->starting_number;
					}
					$voucher_int_fill = str_pad($voucher_int,$voucherConfig->numeric_part,"0", STR_PAD_LEFT);
					$voucher_no = $voucherConfig->prefix.$voucher_int_fill;
					if(!empty($voucherConfig->suffix)) $voucher_no .= $voucherConfig->suffix;
					return [
						'voucher_no' => $voucher_no,
						'voucher_int' =>$voucher_int,
						'voucher_config_id'=>$voucherConfig->id
					];
				}else{
					return [
						'voucher_no' => false,
						'voucher_int' =>false,
						'voucher_config_id' =>0
					];
				}
			}else if($type=='general' || $type=='lc'){
				if($type=='general'){
		            $type_of_voucher = 15;
		        }else if($type=='lc'){
		            $type_of_voucher = 16; 
		        }else{
		            $type_of_voucher = 0;
		        }
				$voucherConfig = CadetInventoryVoucher::module()->where('type_of_voucher', $type_of_voucher)->where('status', 1)->where('numbering', 'auto')->first();
				if(!empty($voucherConfig)){
					$checkVoucher = PurchaseOrderInfoModel::module()->where('purchase_category', $type)->valid()->where('voucher_config_id', $voucherConfig->id)->orderBy('voucher_int', 'desc')->first();
					if(!empty($checkVoucher)){
						$voucher_int = $checkVoucher->voucher_int+1;
					}else{
						$voucher_int = $voucherConfig->starting_number;
					}
					$voucher_int_fill = str_pad($voucher_int,$voucherConfig->numeric_part,"0", STR_PAD_LEFT);
					$voucher_no = $voucherConfig->prefix.$voucher_int_fill;
					if(!empty($voucherConfig->suffix)) $voucher_no .= $voucherConfig->suffix;
					return [
						'voucher_no' => $voucher_no,
						'voucher_int' =>$voucher_int,
						'voucher_config_id'=>$voucherConfig->id
					];
				}else{
					return [
						'voucher_no' => false,
						'voucher_int' =>false,
						'voucher_config_id' =>0
					];
				}

			}else if($type=='purReceive'){
				$voucherConfig = CadetInventoryVoucher::module()->where('type_of_voucher', 7)->where('status', 1)->where('numbering', 'auto')->first();
				if(!empty($voucherConfig)){
					$checkVoucher = PurchaseReceiveInfoModel::module()->valid()->where('voucher_config_id', $voucherConfig->id)->orderBy('voucher_int', 'desc')->first();
					if(!empty($checkVoucher)){
						$voucher_int = $checkVoucher->voucher_int+1;
					}else{
						$voucher_int = $voucherConfig->starting_number;
					}
					$voucher_int_fill = str_pad($voucher_int,$voucherConfig->numeric_part,"0", STR_PAD_LEFT);
					$voucher_no = $voucherConfig->prefix.$voucher_int_fill;
					if(!empty($voucherConfig->suffix)) $voucher_no .= $voucherConfig->suffix;
					return [
						'voucher_no' => $voucher_no,
						'voucher_int' =>$voucher_int,
						'voucher_config_id'=>$voucherConfig->id
					];
				}else{
					return [
						'voucher_no' => false,
						'voucher_int' =>false,
						'voucher_config_id' =>0
					];
				}

			}else if($type=='purInvoice'){
				$voucherConfig = CadetInventoryVoucher::module()->where('type_of_voucher', 17)->where('status', 1)->where('numbering', 'auto')->first();
				if(!empty($voucherConfig)){
					$checkVoucher = PurchaseInvoiceModel::module()->where('voucher_config_id', $voucherConfig->id)->orderBy('voucher_int', 'desc')->first();
					if(!empty($checkVoucher)){
						$voucher_int = $checkVoucher->voucher_int+1;
					}else{
						$voucher_int = $voucherConfig->starting_number;
					}
					$voucher_int_fill = str_pad($voucher_int,$voucherConfig->numeric_part,"0", STR_PAD_LEFT);
					$voucher_no = $voucherConfig->prefix.$voucher_int_fill;
					if(!empty($voucherConfig->suffix)) $voucher_no .= $voucherConfig->suffix;
					return [
						'voucher_no' => $voucher_no,
						'voucher_int' =>$voucher_int,
						'voucher_config_id'=>$voucherConfig->id
					];
				}else{
					return [
						'voucher_no' => false,
						'voucher_int' =>false,
						'voucher_config_id' =>0
					];
				}

			}
			return [
				'voucher_no' => false,
				'voucher_int' =>false,
				'voucher_config_id' =>0
			];

		}

		public static function itemUpdateDependencyCheck($item_id, $delStore){
			$update_check_status = true; $msg='Sorry item has data dependency';

			$inventoryStockIn = DB::table('inventory_all_stock_item_in')->where('item_id', $item_id)->where('valid', 1)->first();
			if(!empty($inventoryStockIn)){
				$update_check_status=false;
				return ['status'=>$update_check_status, 'msg'=>$msg];
			}

			$inventoryDirrectStockIn = DB::table('inventory_direct_stock_in_details')->where('item_id', $item_id)->where('valid', 1)->first();
			if(!empty($inventoryDirrectStockIn)){
				$update_check_status=false;
				return ['status'=>$update_check_status, 'msg'=>$msg];
			}


			$newRequisitionDetails = DB::table('inventory_new_requisition_details')->where('item_id', $item_id)->where('valid', 1)->first();
			if(!empty($newRequisitionDetails)){
				$update_check_status=false;
				return ['status'=>$update_check_status, 'msg'=>$msg];
			}

			$issueFromInventoryDetails = DB::table('inventory_issue_details')->where('item_id', $item_id)->where('valid', 1)->first();
			if(!empty($issueFromInventoryDetails)){
				$update_check_status=false;
				return ['status'=>$update_check_status, 'msg'=>$msg];
			}


			$puchaseRequisitionDetails = DB::table('inventory_purchase_requisition_details')->where('item_id', $item_id)->where('valid', 1)->first();
			if(!empty($puchaseRequisitionDetails)){
				$update_check_status=false;
				return ['status'=>$update_check_status, 'msg'=>$msg];
			}

			$puchaseOrderDetails = DB::table('inventory_purchase_order_details')->where('item_id', $item_id)->where('valid', 1)->first();
			if(!empty($puchaseOrderDetails)){
				$update_check_status=false;
				return ['status'=>$update_check_status, 'msg'=>$msg];
			}

			$puchaseReceiveDetails = DB::table('inventory_purchase_receive_details')->where('item_id', $item_id)->where('valid', 1)->first();
			if(!empty($puchaseReceiveDetails)){
				$update_check_status=false;
				return ['status'=>$update_check_status, 'msg'=>$msg];
			}
			
			$comparativeStatementDetails = DB::table('inventory_comparative_statement_details_info')->where('item_id', $item_id)->where('valid', 1)->first();
			if(!empty($comparativeStatementDetails)){
				$update_check_status=false;
				return ['status'=>$update_check_status, 'msg'=>$msg];
			}

			// store checking
			if(count($delStore)>0){
				$checkItemStock = StoreWiseItemModel::valid()->select(DB::raw('ifnull(sum(current_stock), 0) as totalCurrentStock'))->where('item_id',$item_id)->whereIn('store_id', $delStore)->first();
				if(!empty($inventoryStockIn) && $checkItemStock->totalCurrentStock>0){
					$update_check_status=false;
					return ['status'=>$update_check_status, 'msg'=>$msg];
				}

				$inventoryStockIn = DB::table('inventory_all_stock_item_in')->where('item_id', $item_id)->whereIn('store_id', $delStore)->where('valid', 1)->first();
				if(!empty($inventoryStockIn)){
					$update_check_status=false;
					return ['status'=>$update_check_status, 'msg'=>$msg];
				}

				$inventoryDirrectStockIn = DB::table('inventory_direct_stock_in_details')
					->join('inventory_direct_stock_in', 'inventory_direct_stock_in_details.stock_in_id', 'inventory_direct_stock_in.id')
					->select('inventory_direct_stock_in_details.*')
					->where('inventory_direct_stock_in_details.item_id', $item_id)->whereIn('inventory_direct_stock_in.store_id', $delStore)->where('inventory_direct_stock_in_details.valid', 1)->first();
				if(!empty($inventoryDirrectStockIn)){
					$update_check_status=false;
					return ['status'=>$update_check_status, 'msg'=>$msg];
				}

				$issueFromInventoryDetails = DB::table('inventory_issue_details')
					->join('inventory_issue_from', 'inventory_issue_details.issue_id', 'inventory_issue_from.id')
					->select('inventory_issue_details.*')
					->where('inventory_issue_details.item_id', $item_id)->whereIn('inventory_issue_from.store_id', $delStore)->where('inventory_issue_details.valid', 1)->first();
				if(!empty($issueFromInventoryDetails)){
					$update_check_status=false;
					return ['status'=>$update_check_status, 'msg'=>$msg];
				}
				
				
				$purchaseReceiveDetails = DB::table('inventory_purchase_receive_details')
					->join('inventory_purchase_receive_info', 'inventory_purchase_receive_details.pur_receive_id', 'inventory_purchase_receive_info.id')
					->select('inventory_purchase_receive_details.*')
					->where('inventory_purchase_receive_details.item_id', $item_id)->whereIn('inventory_purchase_receive_info.store_id', $delStore)->where('inventory_purchase_receive_details.valid', 1)->first();
				if(!empty($purchaseReceiveDetails)){
					$update_check_status=false;
					return ['status'=>$update_check_status, 'msg'=>$msg];
				}
			}

			return ['status'=>$update_check_status, 'msg'=>$msg];

		}

		public static function vendorDeleteDependencyCheck($vendor_id){
			$delete_check_status = true; $msg='Sorry! vendor has data dependency';
			$checkPurchaseOrderInfo = DB::table('inventory_purchase_order_info')->where('vendor_id', $vendor_id)->where('valid', 1)->first();
			if(!empty($checkPurchaseOrderInfo)){
				$delete_check_status = false;
				return ['status'=>$delete_check_status, 'msg'=>$msg];
			}
			$checkPurchaseReceiveInfo = DB::table('inventory_purchase_receive_info')->where('vendor_id', $vendor_id)->where('valid', 1)->first();
			if(!empty($checkPurchaseReceiveInfo)){
				$delete_check_status = false;
				return ['status'=>$delete_check_status, 'msg'=>$msg];
			}
			$checkPurchaseInvoiceInfo = PurchaseInvoiceModel::where('vendor_id', $vendor_id)->first();
			if(!empty($checkPurchaseInvoiceInfo)){
				$delete_check_status = false;
				return ['status'=>$delete_check_status, 'msg'=>$msg];
			}
			$checkComparativeStatemetnInfo = DB::table('inventory_comparative_statement_info')->where('vendor_id', $vendor_id)->where('valid', 1)->first();
			if(!empty($checkComparativeStatemetnInfo)){
				$delete_check_status = false;
				return ['status'=>$delete_check_status, 'msg'=>$msg];
			}
			return ['status'=>$delete_check_status, 'msg'=>""];
		}
	}
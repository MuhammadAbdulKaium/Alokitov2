<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Inventory\Entities\PriceCatelogueInfoModel;
use Modules\Inventory\Entities\VendorModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Helpers\InventoryHelper;
use DateTime;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    use InventoryHelper;

    public function __construct(Request $request)
    {
        $this->middleware(function ($request, $next) {
            $user_id = Auth::user()->id;
            $this->campus_id = self::getCampusId();
            $this->institute_id = self::getInstituteId();
            return $next($request);
        });
        
    }

    public function index(Request $request)
    {
        $listPerPage = $request->input('listPerPage');
        $search_key = $request->input('search_key');
        $order = $request->input('order');
        $sort = $request->input('sort');
        $paginate_data_query = VendorModel::module()->valid()
            ->when($search_key, function($query, $search_key){
                $query->where('name','LIKE','%'.$search_key.'%');
            })
            ->orderBy($sort,$order);     
        $data['paginate_data']  = ($listPerPage=='All')? $paginate_data_query->get():$paginate_data_query->paginate($listPerPage);
        return response()->json($data);
    }

    public function page(Request $request){
        return view('inventory::purchase.vendor.vendor');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('inventory::purchase.vendor.vendor-add-form');
    }

    public function vendorCreateData(Request $request){
        $add_row=['0' =>['id'=>0,'term_condition'=>'']];
        $data['formData'] = ['category_id'=>1,'type'=>1,'price_cate_id'=>0,'price_cate_id_model'=>'','bill_tracking'=>"yes", 'maintaining_cost_center'=>'yes','commission_type'=>'fixed', 'bill_by_bill'=>'yes','opening_balance_type'=>'dr', 'add_row'=>$add_row];
        $data['price_label_list'] = PriceCatelogueInfoModel::module()->valid()
            ->select('catalogue_uniq_id', 'price_label')
            ->orderBy('price_label', 'asc')
            ->groupBy(['catalogue_uniq_id', 'price_label'])
            ->get();
        return response()->json($data);
    }

    public function vendorEditData($id){
        $data['price_label_list'] = PriceCatelogueInfoModel::module()->valid()
            ->select('catalogue_uniq_id', 'price_label')
            ->orderBy('price_label', 'asc')
            ->groupBy(['catalogue_uniq_id', 'price_label'])
            ->get();
        $vendorInfo  =  VendorModel::module()->valid()->find($id);
        if(!empty($vendorInfo->price_cate_id)){
            $price_cate_id_model = PriceCatelogueInfoModel::select('catalogue_uniq_id', 'price_label')->module()->valid()->where('catalogue_uniq_id', $vendorInfo->price_cate_id)->first();
            $vendorInfo->price_cate_id_model = $price_cate_id_model;
        }else{
           $vendorInfo->price_cate_id = 0; 
        }
        $inventory_vendor_terms_condition = DB::table('inventory_vendor_terms_condition')->where('vendor_id', $id)->get();
        if(count($inventory_vendor_terms_condition)>0){
            $vendorInfo->add_row = $inventory_vendor_terms_condition;
        }else{
            $vendorInfo->add_row = ['0' =>['id'=>0,'term_condition'=>'']]; 
        }
        $vendorInfo->image = '';
        $vendorInfo->birth_date_show = $vendorInfo->birth_date;
        $vendorInfo->anniversary_show = $vendorInfo->anniversary;
        $data['formData'] = $vendorInfo; 
        return response()->json($data);

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'category_id' => 'required',
            'type' => 'required',
            'name' => 'required|max:255'
        ]);
        DB::beginTransaction();
        try {
            $id = $request->id;
            $add_row = json_decode($request->add_row);
            $data = $request->except(['vendor_image','image','add_row','price_cate_id_model','id','anniversary_show','birth_date','anniversary']);
            if(!empty($request->birth_date)){
                $data['birth_date'] = DateTime::createFromFormat('d/m/Y', $request->birth_date)->format('Y-m-d');
            }else{
                $data['birth_date'] = null;
            }
            if(!empty($request->anniversary)){
                $data['anniversary'] = DateTime::createFromFormat('d/m/Y', $request->anniversary)->format('Y-m-d');
            }else{
                $data['anniversary'] =  null; 
            }

            if ($request->hasFile('vendor_image')) {
                $vendor_image = $request->vendor_image;
                $file_extension = $vendor_image->getClientOriginalExtension();
                $imageName  = time().'.'.$file_extension;
                $upload_dir = public_path().'/assets/inventory/vendor_image';
                request()->vendor_image->move($upload_dir, $imageName);            
                $data['image']    = $imageName;
            }
            if(!empty($id)){
                $vendor_id =  $id;
                $vendorInfo = VendorModel::module()->valid()->find($vendor_id); 
                if(!empty(@$imageName)){
                    if(!empty($vendorInfo->image)){
                        $file_path = public_path().'/assets/inventory/vendor_image' .'/'.$vendorInfo->image;
                        if(file_exists($file_path)) unlink($file_path);
                    }
                }
                $vendorInfo->update($data);
                DB::table('inventory_vendor_terms_condition')->where('vendor_id', $vendor_id)->delete();
            }else{
                $save = VendorModel::create($data);
                $vendor_id = $save->id; 
            }
            // terms and condition 
            $terms_condition_data = [];
            foreach($add_row as $v){
                if(!empty($v->term_condition)){
                    $terms_condition_data[] = [
                        'vendor_id'=>$vendor_id,
                        'term_condition'=>$v->term_condition
                    ];
                }
            }
            DB::table('inventory_vendor_terms_condition')->insert($terms_condition_data);
            $output = ['status'=>1,'message'=>'Vendor successfully saved'];
            DB::commit();
        } catch (Throwable $e){
            DB::rollback();
            throw $e;
        } 
        return response()->json($output); 
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data['vendorInfo'] = VendorModel::select('*', DB::raw("DATE_FORMAT(birth_date,'%d/%m/%Y') AS birth_date_show, DATE_FORMAT(anniversary,'%d/%m/%Y') AS anniversary_date"))->module()->valid()->find($id);
        if(!empty($data['vendorInfo']->price_cate_id)){
            $price_cate_info = PriceCatelogueInfoModel::select('catalogue_uniq_id', 'price_label')->module()->valid()->first();
            $data['vendorInfo']->price_cate_name = $price_cate_info->price_label;
        }else{
           $data['vendorInfo']->price_cate_name = ''; 
        }
        $data['inventory_vendor_terms_condition'] = DB::table('inventory_vendor_terms_condition')->where('vendor_id', $id)->get();

        return view('inventory::purchase.vendor.vendor-details-form', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['id'] = $id;
        return view('inventory::purchase.vendor.vendor-edit-form', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {

        DB::beginTransaction();
        try {
            $checkPurchaseOrderInfo = DB::table('inventory_purchase_order_info')->where('vendor_id', $id)->first();
            if(empty($checkPurchaseOrderInfo)){
                $vendorInfo = VendorModel::module()->valid()->find($id);
                if(!empty($vendorInfo->image)){
                    $file_path = public_path().'/assets/inventory/vendor_image' .'/'.$vendorInfo->image;
                    if(file_exists($file_path)) unlink($file_path);
                }
                $vendorInfo->delete();
                DB::table('inventory_vendor_terms_condition')->where('vendor_id', $id)->delete();

                $output = ['status'=>1,'message'=>'Vendor successfully deleted'];
                DB::commit();
            }else{
                $output = ['status'=>0,'message'=>'Sorry! Vendor has purchase order'];
            }
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        } 
        return response()->json($output); 
    }
}

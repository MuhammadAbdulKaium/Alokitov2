<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Inventory\Entities\PriceCatelogueInfoModel;
use Modules\Inventory\Entities\CustomerModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Helpers\InventoryHelper;
use DateTime;

class CustomerController extends Controller
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
        $paginate_data_query = CustomerModel::module()->valid()
            ->when($search_key, function($query, $search_key){
                $query->where('name','LIKE','%'.$search_key.'%');
            })
            ->orderBy($sort,$order);     
        $data['paginate_data']  = ($listPerPage=='All')? $paginate_data_query->get():$paginate_data_query->paginate($listPerPage);
        return response()->json($data);
    }

    public function page(Request $request){
        return view('inventory::sales.customer.customer');
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('inventory::sales.customer.customer-add-form');
    }

    public function customerCreateData(Request $request){
        $add_row=['0' =>['id'=>0,'term_condition'=>'']];
        $data['formData'] = ['category_id'=>1,'type'=>1,'price_cate_id'=>0,'price_cate_id_model'=>'','bill_tracking'=>"yes", 'maintaining_cost_center'=>'yes','commission_type'=>'fixed', 'bill_by_bill'=>'yes','opening_balance_type'=>'dr', 'add_row'=>$add_row];
        $data['price_label_list'] = PriceCatelogueInfoModel::module()->valid()
            ->select('catalogue_uniq_id', 'price_label')
            ->orderBy('price_label', 'asc')
            ->groupBy(['catalogue_uniq_id', 'price_label'])
            ->get();
        return response()->json($data);
    }

    public function customerEditData($id){
        $data['price_label_list'] = PriceCatelogueInfoModel::module()->valid()
            ->select('catalogue_uniq_id', 'price_label')
            ->orderBy('price_label', 'asc')
            ->groupBy(['catalogue_uniq_id', 'price_label'])
            ->get();
        $customerInfo  =  CustomerModel::module()->valid()->find($id);
        if(!empty($customerInfo->price_cate_id)){
            $price_cate_id_model = PriceCatelogueInfoModel::select('catalogue_uniq_id', 'price_label')->module()->valid()->where('catalogue_uniq_id', $customerInfo->price_cate_id)->first();
            $customerInfo->price_cate_id_model = $price_cate_id_model;
        }else{
           $customerInfo->price_cate_id = 0; 
        }
        $inventory_customer_terms_condition = DB::table('inventory_customer_terms_condition')->where('customer_id', $id)->get();
        if(count($inventory_customer_terms_condition)>0){
            $customerInfo->add_row = $inventory_customer_terms_condition;
        }else{
            $customerInfo->add_row = ['0' =>['id'=>0,'term_condition'=>'']]; 
        }
        $customerInfo->image = '';
        $customerInfo->birth_date_show = $customerInfo->birth_date;
        $customerInfo->anniversary_show = $customerInfo->anniversary;
        $data['formData'] = $customerInfo; 
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
            $data = $request->except(['customer_image','image','add_row','price_cate_id_model','id','anniversary_show','birth_date','anniversary']);
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

            if ($request->hasFile('customer_image')) {
                $customer_image = $request->customer_image;
                $file_extension = $customer_image->getClientOriginalExtension();
                $imageName  = time().'.'.$file_extension;
                $upload_dir = public_path().'/assets/inventory/customer_image';
                request()->customer_image->move($upload_dir, $imageName);            
                $data['image']    = $imageName;
            }
            if(!empty($id)){
                $customer_id =  $id;
                $customerInfo = CustomerModel::module()->valid()->find($customer_id); 
                if(!empty(@$imageName)){
                    if(!empty($customerInfo->image)){
                        $file_path = public_path().'/assets/inventory/customer_image' .'/'.$customerInfo->image;
                        if(file_exists($file_path)) unlink($file_path);
                    }
                }
                $customerInfo->update($data);
                DB::table('inventory_customer_terms_condition')->where('customer_id', $customer_id)->delete();
            }else{
                $save = CustomerModel::create($data);
                $customer_id = $save->id; 
            }
            // terms and condition 
            $terms_condition_data = [];
            foreach($add_row as $v){
                if(!empty($v->term_condition)){
                    $terms_condition_data[] = [
                        'customer_id'=>$customer_id,
                        'term_condition'=>$v->term_condition
                    ];
                }
            }
            DB::table('inventory_customer_terms_condition')->insert($terms_condition_data);
            $output = ['status'=>1,'message'=>'Customer successfully saved'];
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
        $data['customerInfo'] = CustomerModel::select('*', DB::raw("DATE_FORMAT(birth_date,'%d/%m/%Y') AS birth_date_show, DATE_FORMAT(anniversary,'%d/%m/%Y') AS anniversary_date"))->module()->valid()->find($id);
        if(!empty($data['customerInfo']->price_cate_id)){
            $price_cate_info = PriceCatelogueInfoModel::select('catalogue_uniq_id', 'price_label')->module()->valid()->first();
            $data['customerInfo']->price_cate_name = $price_cate_info->price_label;
        }else{
           $data['customerInfo']->price_cate_name = ''; 
        }
        $data['inventory_customer_terms_condition'] = DB::table('inventory_customer_terms_condition')->where('customer_id', $id)->get();

        return view('inventory::sales.customer.customer-details-form', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['id'] = $id;
        return view('inventory::sales.customer.customer-edit-form', $data);
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
            $checkSalesOrderInfo = DB::table('inventory_sales_order_info')->where('customer_id', $id)->first();
            if(empty($checkSalesOrderInfo)){
                $customerInfo = CustomerModel::module()->valid()->find($id);
                if(!empty($customerInfo->image)){
                    $file_path = public_path().'/assets/inventory/customer_image' .'/'.$customerInfo->image;
                    if(file_exists($file_path)) unlink($file_path);
                }
                $customerInfo->delete();
                DB::table('inventory_customer_terms_condition')->where('customer_id', $id)->delete();

                $output = ['status'=>1,'message'=>'Customer successfully deleted'];
                DB::commit();
            }else{
                $output = ['status'=>0,'message'=>'Sorry! Customer has sales order'];
            }
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        } 
        return response()->json($output); 
    }
}

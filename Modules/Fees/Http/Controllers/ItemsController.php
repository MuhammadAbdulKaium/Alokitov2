<?php

namespace Modules\Fees\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Fees\Entities\Items;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Accounting\Entities\AccCharts;

class ItemsController extends Controller
{

    private  $items;
    private  $academicHelper;
    private  $accCharts;


    public function __construct( Items $items,AccCharts $accCharts, AcademicHelper $academicHelper)
    {
        $this->items             = $items;
        $this->academicHelper             = $academicHelper;
        $this->accCharts             = $accCharts;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('fees::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('fees::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {

//        return $request->all();
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $item_id=$request->input('item_id');
        if(empty($item_id)) {
            $itemProfile = new $this->items();
            $itemProfile->institution_id =$instituteId;
            $itemProfile->campus_id =$campus_id;
            $itemProfile->item_name = $request->input('item_name');
            $itemProfile->acc_chart_id = $request->input('acc_chart_id');
            $itemProfile->save();
            return redirect()->back();
        } else {
            $itemProfile = $this->items->find($item_id);
            $itemProfile->institution_id =$instituteId;
            $itemProfile->campus_id =$campus_id;
            $itemProfile->item_name = $request->input('item_name');
            $itemProfile->acc_chart_id = $request->input('acc_chart_id');
            $itemProfile->save();
            return redirect()->back();
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('fees::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($item_id)
    {

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        // select all accounting leger by fee group
        $accChartList=$this->accCharts->where('company_id',$instituteId)->where('brunch_id',$campus_id)->where('chart_parent',7)->get();


        $itemProfile = $this->items->find($item_id);
        $itemList=$this->items->where('institution_id',$instituteId)->where('campus_id',$campus_id)->orderBy('id','desc')->get();
        // return veiw with variables
        return view('fees::pages.items', compact('itemList','itemProfile','accChartList'))->with('page', 'items');

    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function findItems(Request $request)
    {
        $searchTerm = $request->input('term');

        $instituteId = $this->academicHelper->getInstitute();
        $campus_id = $this->academicHelper->getCampus();

        $allItems = $this->items->where('institution_id', $instituteId)->where('campus_id', $campus_id)->where('item_name', 'like', "%" . $searchTerm . "%")->get();

        // checking
        if ($allItems) {
            $data = array();
            foreach ($allItems as $item) {
                // store into data set
                $data[] = array(
                    'id' => $item->id,
                    'item_name' => $item->item_name,
                );
            }

            return json_encode($data);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function delete($id)
    {
        $itemProfile=$this->items->find($id);
        $result=$itemProfile->delete();
        if($result) {
            return 'success';
        } else {
            return 'error';
        }
    }
}

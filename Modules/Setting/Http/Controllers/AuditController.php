<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use OwenIt\Auditing\Models\Audit;
//use App\User;

class AuditController extends Controller
{


    private  $audit;


    public function __construct(Audit $audit)
    {
        $this->audit             = $audit;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getAuditList()
    {
        // get all audits list here......
        $audits=$this->audit->orderBy("id","desc")->paginate(9);
        //check audit search
        $searchAudit=0;
        return view('setting::audit.index',compact('audits','searchAudit'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */

    // invoice search

    public function auditSearch(Request $request)
    {

//        return $request->all();



        $user_name = $request->input('search_user');
        $user_id = $request->input('user_id');
        $audit_event = $request->input('audit_event');
        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');


        // check user_id
        if ($user_id) {
            $allSearchInputs['user_id'] = $user_id;
        }
        // check event
        if ($audit_event) {

            $allSearchInputs['event'] = $audit_event;
        }

        if (!empty($start_time) && !empty($end_time)) {

                $allAuditList = $this->audit->where($allSearchInputs)->whereBetween('created_at', [$start_time, $end_time])->paginate(10);
        } else {
            $allAuditList = $this->audit->paginate(10);

        }


        if ($allAuditList) {
            // all inputs
            $allInputs =[
                'user_id'=>$user_id,
                'user_name'=>$user_name,
                'audit_event' => $audit_event,
                'start_time'=>$start_time,
                'end_time'=>$end_time
            ];
            // return view
            $allInputs=(Object)$allInputs;
            $searchAudit=1;
            return view('setting::audit.index', compact('searchAudit','allAuditList','allInputs'));
        }
    }


    // get all audit List user

    public  function getAuditUserList(){
//        return User::all();
    }


    public function create()
    {
        return view('setting::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('setting::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('setting::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}

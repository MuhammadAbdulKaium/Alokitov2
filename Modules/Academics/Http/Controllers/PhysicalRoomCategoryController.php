<?php

namespace Modules\Academics\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Academics\Entities\PhysicalRoomCategory;
use Modules\Academics\Entities\PhysicalRoomCatType;
use Modules\Academics\Entities\RoomCategory;
use Modules\Academics\Http\Requests\PhysicalRoomCategoryRequest;

class PhysicalRoomCategoryController extends Controller
{
    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }


    public function index()
    {
    }

    
    
    public function create()
    {
        return view('academics::create');
    }

    
    
    public function store(PhysicalRoomCategoryRequest $request)
    {
        $sameNameCategory = PhysicalRoomCategory::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
            'name' => $request->category_name
        ])->get();

        if (sizeOf($sameNameCategory) > 0) {
            Session::flash('errorMessage', 'Sorry! There is already a physical room category in this name.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $roomCategoryInsert = PhysicalRoomCategory::insert([
                'cat_type' => $request->cat_type,
                'name' => $request->category_name,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
            ]);

            if ($roomCategoryInsert) {
                DB::commit();
                Session::flash('message', 'Success! New physical room category created successfully.');
                return redirect()->back();
            }else{
                Session::flash('errorMessage', 'Sorry! Physical room category has not created.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Sorry! Physical room category has not created.');
            return redirect()->back();
        }
    }

    
    
    public function show($id)
    {
        return view('academics::show');
    }

    
    
    public function edit($id)
    {
        $roomCategories = PhysicalRoomCategory::where([
            ['id', '!=', $id], 
            ['campus', $this->academicHelper->getCampus()],
            ['institute', $this->academicHelper->getInstitute()]
        ])->get();

        $category = PhysicalRoomCategory::findOrFail($id);

        return view('academics::physical-rooms.modal.edit-category', compact('category'));
    }

    
    
    public function update(PhysicalRoomCategoryRequest $request, $id)
    {  
        $category = PhysicalRoomCategory::findOrFail($id);

        $sameNameCategory = PhysicalRoomCategory::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
            'name' => $request->category_name
        ])->first();

        if ($sameNameCategory) {
            if ($sameNameCategory->id != $category->id) {
                Session::flash('errorMessage', 'Sorry! There is already a physical room category in this name.');
                return redirect()->back();
            }
        }


        DB::beginTransaction();
        try {      
            $roomCategoryUpdate = $category->update([
                'name' => $request->category_name,
                'cat_type' => $request->cat_type
            ]);
        
            if ($roomCategoryUpdate) {
                DB::commit();
                Session::flash('message', 'Success! Physical room category updated successfully.');
                return redirect()->back();
            }else{
                Session::flash('errorMessage', 'Could not update Physical Room.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();       
            Session::flash('errorMessage', 'Could not update Physical Room.');
            return redirect()->back();
        }
    }

    
    
    public function destroy($id)
    {
        $room_category = PhysicalRoomCategory::findOrFail($id);
        if (sizeof($room_category->rooms()) > 0) {
            Session::flash('errorMessage', 'Sorry! Dependencies found.');
            return redirect()->back();
        } else {
            $room_category->deleted_by = Auth::id();
            $room_category->save();
            $room_category->delete();

            Session::flash('message', 'Deleted Successfully.');
            return redirect()->back();
        }
    }
}

<?php

namespace Modules\Website\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Website\Entities\WebsiteExtra;

class ExtraInformationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */



 /*

                              *************IMPORTANT DOCUMENTATION*************


    This controller is used for Facility, Publications, Circular and Extra Curricular Activities menu.
    For controlling $type variable has been used which. Here,
    type 1 = facilities
    type 2 = publications
    type 3 = circular
    type 4 = extra_curricular
    type 5 = books_syllabus

    ***type is also an attribute of the website_extras table***

    And to pass this information $typeArray hase been used, where
    $typeArray = array('name of the type', 'numeric value of the type')
*/


    public function index($type)
    {
        $campus = session()->get('campus');
        $institute = session()->get('institute');
        $user = Auth::user();

        if($type == 'facilities')
        {
            $photos = array();
            $typeArray = array('facilities', '1');
            $facilities = WebsiteExtra::where('campus_id', '=', $campus)
                ->where('institute_id', '=', $institute)
                ->where('type', '=', $typeArray[1])->get();

            foreach ($facilities as $facility)
            {
                $album = explode('|', $facility->file);
                $photos[] = $album[0];
            }

            return view('website::facilities.index', compact('facilities', 'photos', 'user', 'typeArray'));
        }
        if($type == 'publications')
        {
            $typeArray = array('publications', '2');
            $publications = WebsiteExtra::where('campus_id', '=', $campus)
                ->where('institute_id', '=', $institute)
                ->where('type', '=', $typeArray[1])->get();

            return view('website::publications.index', compact('publications', 'user', 'typeArray'));
        }
        if($type == 'circular')
        {
            $typeArray = array('circular', '3', 'circularResult');
            $circulars = WebsiteExtra::where('campus_id', '=', $campus)
                ->where('institute_id', '=', $institute)
                ->where('type', '=', $typeArray[1])->get();

            return view('website::circular.index', compact('circulars', 'user', 'typeArray'));
        }
        if($type == 'extra_curricular')
        {
            $typeArray = array('extra_curricular', '4');
            $extraCurriculars = WebsiteExtra::where('campus_id', '=', $campus)
                ->where('institute_id', '=', $institute)
                ->where('type', '=', $typeArray[1])->get();

            return view('website::extra-curricular.index', compact('extraCurriculars', 'user', 'typeArray'));
        }
        if($type == 'books_syllabus')
        {
            $typeArray = array('books_syllabus', '5', 'syllabus');
            $booksSyllabuses = WebsiteExtra::where('campus_id', '=', $campus)
                ->where('institute_id', '=', $institute)
                ->where('type', '=', $typeArray[1])->get();

            return view('website::books-syllabus.index', compact('booksSyllabuses', 'user', 'typeArray'));
        }
        else{
            echo 'Page type is wrong!';
        }

    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create($type)
    {
        $user = Auth::user();
        if($type == 'facilities')
        {
            $typeArray = array('facilities', '1');
            return view('website::facilities.create', compact('user', 'typeArray'));
        }
        if($type == 'publications')
        {
            $typeArray = array('publications', '2');
            return view('website::publications.create', compact('user', 'typeArray'));
        }
        if($type == 'circular')
        {
            $typeArray = array('circular', '3');
            return view('website::circular.create', compact('user', 'typeArray'));
        }
        if($type == 'extra_curricular')
        {
            $typeArray = array('extra_curricular', '4');
            return view('website::extra-curricular.create', compact('user', 'typeArray'));
        }
        if($type == 'books_syllabus')
        {
            $typeArray = array('books_syllabus', '5');
            return view('website::books-syllabus.create', compact('user', 'typeArray'));
        }
        else{
            echo 'Page type is wrong!';
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $images = array();

        if($input['type'] == 1)
        {
            if($files = $request->file('file'))
            {
                foreach($files as $file)
                {
                    $current_timestamp = Carbon::now()->timestamp;
                    $name = $input['campus_id'] . $input['institute_id'] . random_int(0, 499) . $current_timestamp . random_int(500, 999) . "." . $file->getClientOriginalExtension();
                    $file->move('images', $name);
                    $images[] = $name;
                }

            }
            WebsiteExtra::insert( [
                'file' =>  implode("|",$images),
                'campus_id' => $input['campus_id'],
                'institute_id' => $input['institute_id'],
                'type' => $input['type'],
                'name' => $input['name'],
                'description' => $input['description'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            return redirect()->back();
        }
        else {
            if($file = $request->file('file')){
                $current_timestamp = Carbon::now()->timestamp;
                $name = $input['campus_id'] . $input['institute_id'] . $input['type'] . $current_timestamp . '12.' . $file->getClientOriginalExtension();
                $file->move('images', $name);
                $input['file'] = $name;
            }
            if($file = $request->file('file2'))
            {
                $current_timestamp = Carbon::now()->timestamp;
                $name = $input['campus_id'] . $input['institute_id'] . $input['type'] . $current_timestamp . '34.' . $file->getClientOriginalExtension();
                $file->move('images', $name);
                $input['file2'] = $name;
            }
            WebsiteExtra::create($input);
            return redirect()->back();
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($type, $id)
    {
        $user = Auth::user();

        if($type == 'facilities')
        {
            $facility = WebsiteExtra::findOrFail($id);
            $photos = explode('|', $facility->file);
            $typeArray = array('facilities', '1');
            return view('website::facilities.show', compact('facility', 'photos',  'user', 'typeArray'));
        }
        if($type == 'publications')
        {
            $publication = WebsiteExtra::findOrFail($id);
            $typeArray = array('publications', '2');
            return view('website::publications.show', compact('publication', 'user', 'typeArray'));
        }
        if($type == 'circular' || $type == 'circularResult')
        {
            $circular = WebsiteExtra::findOrFail($id);
            if($type == 'circular')
            {
                $file = $circular->file;
            }
            else{
                $file = $circular->file2;
            }
            $typeArray = array('circular', '3');
            return view('website::circular.show', compact('file', 'user', 'typeArray'));
        }
        if($type == 'extra_curricular')
        {
            $extraCurricular = WebsiteExtra::findOrFail($id);
            $typeArray = array('extra_curricular', '4');
            return view('website::extra-curricular.show', compact('extraCurricular', 'user', 'typeArray'));
        }
        if($type == 'books_syllabus' || $type == 'syllabus')
        {
            $circular = WebsiteExtra::findOrFail($id);
            if($type == 'books_syllabus')
            {
                $file = $circular->file;
            }
            else{
                $file = $circular->file2;
            }
            $typeArray = array('books_syllabus', '5');
            return view('website::circular.show', compact('file', 'user', 'typeArray'));
        }

    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($type, $id)
    {
        $user = Auth::user();

        if($type == 'facilities')
        {
            $facility = WebsiteExtra::findOrFail($id);
            $typeArray = array('facilities', '1');
            return view('website::facilities.edit', compact('facility', 'user', 'typeArray'));
        }
        if($type == 'publications')
        {
            $publication = WebsiteExtra::findOrFail($id);
            $typeArray = array('publications', '2');
            return view('website::publications.edit', compact('publication', 'user', 'typeArray'));
        }
        if($type == 'circular')
        {
            $circular = WebsiteExtra::findOrFail($id);
            $typeArray = array('circular', '3');
            return view('website::circular.edit', compact('circular', 'user', 'typeArray'));
        }
        if($type == 'extra_curricular')
        {
            $extraCurricular = WebsiteExtra::findOrFail($id);
            $typeArray = array('extra_curricular', '4');
            return view('website::extra-curricular.edit', compact('extraCurricular', 'user', 'typeArray'));
        }
        if($type == 'books_syllabus')
        {
            $booksSyllabus = WebsiteExtra::findOrFail($id);
            $typeArray = array('books_syllabus', '5');
            return view('website::books-syllabus.edit', compact('booksSyllabus', 'user', 'typeArray'));
        }
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $record = WebsiteExtra::findOrFail($id);

        if($file = $request->file('file'))
        {
            $current_timestamp = Carbon::now()->timestamp;
            $name = $input['campus_id'] . $input['institute_id'] . $input['type'] . $current_timestamp . '.' . $file->getClientOriginalExtension();
            $file->move('images', $name);
            $input['file'] = $name;
        }
        if($file = $request->file('file2'))
        {
            $current_timestamp = Carbon::now()->timestamp;
            $name = $input['campus_id'] . $input['institute_id'] . $input['type'] . $current_timestamp . '.' . $file->getClientOriginalExtension();
            $file->move('images', $name);
            $input['file2'] = $name;
        }
        $record->update($input);
        return redirect()->back();
        }


    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        $record = WebsiteExtra::findOrFail($id);
        $record->delete();
        return redirect()->back();
    }

    public function facilityImageAddModal($id)
    {
        $user = Auth::user();
        $facility = WebsiteExtra::findOrFail($id);
        return view('website::facilities.image-add', compact('facility', 'user'));
    }

    public function addFacilityImage(Request $request, $id)
    {
        $album = WebsiteExtra::findOrFail($id);
        $input = $request->all();
        $images = array();

        if($files = $request->file('file'))
        {
            foreach($files as $file)
            {
                $current_timestamp = Carbon::now()->timestamp;
                $name = $input['campus_id'] . $input['institute_id'] . random_int(0, 499) . $current_timestamp . random_int(500, 999) . "." . $file->getClientOriginalExtension();
                $file->move('images', $name);
                $images[] = $name;
            }
        }
        $values = $album->file;
        $photos = explode("|", $values);
        if($photos[0] != "")
        {
            $images = array_merge($photos, $images);
            $newImages =  implode("|",$images);
        }
        else{
            $newImages =  implode("|",$images);
        }

        $data = [ 'file' =>  $newImages];
        $album->update($data);
        return redirect()->back();
    }

    public function destroyFacilityImage($id, $key)
    {
        $album = WebsiteExtra::findOrFail($id);
        $values = $album->file;
        $photos = explode("|", $values);
        unset($photos[$key]);
        $photos = implode("|", $photos);

        $data = [
            'file' =>  $photos,
        ];
        $album->update($data);
        return redirect()->back();
    }
}

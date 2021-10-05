<?php

namespace Modules\Website\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Website\Entities\WebsiteImage;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $campus = session()->get('campus');
        $institute = session()->get('institute');

        $user = Auth::user();
        $images = WebsiteImage::where('campus_id', '=', $campus)
            ->where('institute_id', '=', $institute)->get();

        return view('website::image.index', compact('images', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $user = Auth::user();
        return view('website::image.create', compact('user'));
    }


    public function store(Request $request)
    {
        $input = $request->all();
        $images = array();
        $flag = 0;

        if($files = $request->file('images'))
        {
            foreach($files as $file)
            {
                $current_timestamp = Carbon::now()->timestamp;
                $name = $input['campus_id'] . $input['institute_id'] . random_int(0, 499) . $current_timestamp . random_int(500, 999) . "." . $file->getClientOriginalExtension();
                $file->move('images', $name);
                $images[] = $name;
            }
        }

        if($input['type'] == 'Slider')
        {
            $albums = WebsiteImage::where('campus_id', '=', $input['campus_id'])
                ->where('institute_id', '=', $input['institute_id'])->get();

            foreach ($albums as $album)
            {
                if($album->type == 'Slider')
                {
                    $values = $album->images;
                    $photos = explode("|", $values);
                    $images = array_merge($photos, $images);
                    $newImages =  implode("|",$images);

                    $data = [
                        'images' =>  $newImages,
                        'campus_id' => $input['campus_id'],
                        'institute_id' => $input['institute_id'],
                        'name' => $input['name'],
                        'type' => $input['type']
                    ];

                    $find = WebsiteImage::findOrFail($album->id);
                    $find->update($data);
                    $flag = 1;
                }
            }
        }
        if($flag == 0)
        {
            WebsiteImage::insert( [
                'images' =>  implode("|",$images),
                'campus_id' => $input['campus_id'],
                'institute_id' => $input['institute_id'],
                'name' => $input['name'],
                'type' => $input['type'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
        return redirect('website/image');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $album = WebsiteImage::findOrFail($id);
        $values = $album->images;
        $photos = explode("|", $values);

        return view('website::image.show', compact('album', 'photos', 'user', 'id'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        $album = WebsiteImage::findOrFail($id);

        return view('website::image.edit', compact('album', 'user'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $album = WebsiteImage::findOrFail($id);
        $input = $request->all();
        $images = array();

        if($files = $request->file('images'))
        {
            foreach($files as $file)
            {
                $current_timestamp = Carbon::now()->timestamp;
                $name = $input['campus_id'] . $input['institute_id'] . random_int(0, 499) . $current_timestamp . random_int(500, 999) . "." . $file->getClientOriginalExtension();
                $file->move('images', $name);
                $images[] = $name;
            }
        }

        $values = $album->images;
        $photos = explode("|", $values);
        $images = array_merge($photos, $images);
        $newImages =  implode("|",$images);

        $data = [
            'images' =>  $newImages,
            'campus_id' => $input['campus_id'],
            'institute_id' => $input['institute_id'],
            'name' => $input['name'],
            'type' => $album->type
        ];
        $album->update($data);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id, $key)
    {
        $album = WebsiteImage::findOrFail($id);
        if($key != 'album')
        {
            $values = $album->images;
            $photos = explode("|", $values);
            unset($photos[$key]);
            $photos = implode("|", $photos);

            $data = [
                'images' =>  $photos,
            ];
            $album->update($data);
        }
        else {
            $album->delete();
        }
        return redirect()->back();
    }
}

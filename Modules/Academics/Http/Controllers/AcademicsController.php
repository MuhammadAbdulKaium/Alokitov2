<?php

namespace Modules\Academics\Http\Controllers;

use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Academics\Entities\AcademicsYear;
use App\Http\Controllers\Helpers\AcademicHelper;
use Validator;
use Modules\Communication\Entities\Event;
use MaddHatter\LaravelFullcalendar\Calendar;


class AcademicsController extends Controller
{

    private $academicsYear;
    private $academicsLevel;
    private $academicHelper;
    // constructor
    public function __construct(AcademicHelper $academicHelper, AcademicsYear $academicsYear, AcademicsLevel $academicsLevel)
    {
        $this->academicsYear  = $academicsYear;
        $this->academicsLevel  = $academicsLevel;
        $this->academicHelper  = $academicHelper;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {

        return view('academics::dashboard.academic');
    }


    public function academicCalendar(){


        $events = Event::where('institute',5)->where('campus',5)->get();
        return view('academics::academics-calendar.index', compact('events'));
    }

}
<?php

namespace Modules\Employee\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use App\User;
use Carbon\Carbon;
use Composer\Util\Http\Response;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Modules\Employee\Entities\EmployeeDesignation;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Employee\Entities\Evaluation;
use Modules\Employee\Entities\EvaluationMark;
use Modules\Employee\Entities\EvaluationParameter;
use Modules\Employee\Http\Requests\EvaluationRequest;
use Modules\Student\Entities\StudentProfileView;
use PhpParser\Node\Expr\Cast\Object_;

class EvaluationController extends Controller
{
    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }


    public function index($id = '')
    {
        $myEvaluation = ($id) ? Evaluation::findOrFail($id) : null;

        $evaluationParameters = EvaluationParameter::where([
            'campus_id' => session()->get('campus'),
            'institute_id' => session()->get('institute')
        ])->get();
        $designations = EmployeeDesignation::where([
            'campus_id' => session()->get('campus'),
            'institute_id' => session()->get('institute')
        ])->get();
        $evaluations = Evaluation::where([
            'campus_id' => session()->get('campus'),
            'institute_id' => session()->get('institute')
        ])->get();

        return view('employee::pages.evaluation.index', compact('evaluationParameters', 'designations', 'evaluations', 'myEvaluation'));
    }



    public function create()
    {
        return view('employee::create');
    }



    public function store(Request $request)
    {
        $sameNameParameter = EvaluationParameter::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'name' => $request->name
        ])->get();

        if (sizeOf($sameNameParameter) > 0) {
            Session::flash('errorMessage', 'Sorry! There is already an evaluation parameter in this name.');
            return redirect()->back();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->passes()) {
            DB::beginTransaction();
            try {
                $insertParameter = EvaluationParameter::insert([
                    'name' => $request->name,
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);

                if ($insertParameter) {
                    DB::commit();
                    Session::flash('message', 'Success! Evaluation parameter created successfully.');
                    return redirect()->back();
                } else {
                    Session::flash('errorMessage', 'Error creating Evaluation parameter.');
                    return redirect()->back();
                }
            } catch (\Exception $e) {
                DB::rollback();
                Session::flash('errorMessage', 'Error creating Evaluation parameter.');
                return redirect()->back();
            }
        } else {
            Session::flash('errorMessage', 'Please input valid data.');
            return redirect()->back();
        }
    }



    public function show($id)
    {
        return view('employee::show');
    }



    public function edit($id)
    {
        $evaluationParameter = EvaluationParameter::findOrFail($id);

        return view('employee::pages.evaluation.modal.edit-evaluation-parameter', compact('evaluationParameter'));
    }



    public function update(Request $request, $id)
    {
        $evaluationParameter = EvaluationParameter::findOrFail($id);

        $sameNameParameter = EvaluationParameter::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'name' => $request->name
        ])->first();

        if ($sameNameParameter) {
            if ($sameNameParameter->id != $evaluationParameter->id) {
                Session::flash('errorMessage', 'Sorry! There is already an evaluation parameter in this name.');
                return redirect()->back();
            }
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->passes()) {
            DB::beginTransaction();
            try {
                $updateParameter = $evaluationParameter->update([
                    'name' => $request->name,
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ]);

                if ($updateParameter) {
                    DB::commit();
                    Session::flash('message', 'Success! Evaluation parameter updated successfully.');
                    return redirect()->back();
                } else {
                    Session::flash('errorMessage', 'Error updating evaluation parameter.');
                    return redirect()->back();
                }
            } catch (\Exception $e) {
                DB::rollback();
                Session::flash('errorMessage', 'Error updating evaluation parameter.');
                return redirect()->back();
            }
        } else {
            Session::flash('errorMessage', 'Please Input Valid Data!');
            return redirect()->back();
        }
    }



    public function destroy($id)
    {
        $evaluationParameter = EvaluationParameter::findOrFail($id);

        if (sizeof($evaluationParameter->evaluations) > 0) {
            Session::flash('alert', 'Sorry! This evaluation parameter has assignment.');
            return redirect()->back();
        } else {
            $evaluationParameter->delete();
            Session::flash('message', 'Success! Evaluation parameter deleted successfully.');
            return redirect()->back();
        }
    }



    public function storeEvaluation(EvaluationRequest $request)
    {
        DB::beginTransaction();
        try {
            $evaluation = new Evaluation();

            $evaluation->evaluation_for = $request->evaluationFor;
            $evaluation->name = $request->name;
            $evaluation->year = $request->year;
            $evaluation->score = $request->score;
            $evaluation->created_at = Carbon::now();
            $evaluation->created_by = Auth::id();
            $evaluation->campus_id = $this->academicHelper->getCampus();
            $evaluation->institute_id = $this->academicHelper->getInstitute();

            // Evaluation By Column insert
            $evaluationBy = $request->evaluationBy;
            $isByHrFm = in_array("hrfm", $evaluationBy);
            $isByCadets = in_array("cadets", $evaluationBy);

            if ($isByHrFm && $isByCadets) {
                $evaluation->evaluation_by = 3;
                array_splice($evaluationBy, 0, 2);
            } elseif ($isByHrFm) {
                $evaluation->evaluation_by = 1;
                array_splice($evaluationBy, 0, 1);
            } elseif ($isByCadets) {
                $evaluation->evaluation_by = 2;
                array_splice($evaluationBy, 0, 1);
            }

            $evaluation->save();
            $evaluation->byDesignations()->attach($evaluationBy);

            DB::commit();
            Session::flash('message', 'Success! Evaluation created successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();

            Session::flash('errorMessage', 'Error creating Evaluation.');
            return redirect()->back();
        }
    }


    public function editEvaluation($id)
    {
        $evaluation = Evaluation::findOrFail($id);
        $designations = EmployeeDesignation::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        return view('employee::pages.evaluation.modal.edit-evaluation', compact('evaluation', 'designations'));
    }

    public function updateEvaluation(EvaluationRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $evaluation = Evaluation::FindOrfail($id);

            $evaluation->evaluation_for = $request->evaluationFor;
            $evaluation->name = $request->name;
            $evaluation->year = $request->year;
            $evaluation->score = $request->score;
            $evaluation->updated_at = Carbon::now();
            $evaluation->updated_by = Auth::id();

            // Evaluation By Column insert
            $evaluationBy = $request->evaluationBy;
            $isByHrFm = in_array("hrfm", $evaluationBy);
            $isByCadets = in_array("cadets", $evaluationBy);

            if ($isByHrFm && $isByCadets) {
                $evaluation->evaluation_by = 3;
                array_splice($evaluationBy, 0, 2);
            } elseif ($isByHrFm) {
                $evaluation->evaluation_by = 1;
                array_splice($evaluationBy, 0, 1);
            } elseif ($isByCadets) {
                $evaluation->evaluation_by = 2;
                array_splice($evaluationBy, 0, 1);
            }

            $evaluation->save();
            $evaluation->byDesignations()->sync($evaluationBy);

            DB::commit();
            Session::flash('message', 'Success! Evaluation updated successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();

            Session::flash('errorMessage', 'Error updating Evaluation.');
            return redirect()->back();
        }
    }


    public function destroyEvaluation($id)
    {
        $evaluation = Evaluation::findOrFail($id);

        if (sizeof($evaluation->parameters) > 0) {
            Session::flash('alert', 'Sorry! Parameters available in this Evaluation.');
            return redirect()->back();
        } else {
            $evaluation->delete();
            Session::flash('message', 'Success! Evaluation deleted successfully.');
            return redirect()->back();
        }
    }

    public function assignViewEvaluationParameter($id)
    {
        $evaluation = Evaluation::findOrFail($id);
        $evaluationParameters = EvaluationParameter::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();

        return view('employee::pages.evaluation.modal.assign-parameter', compact('evaluation', 'evaluationParameters'));
    }

    public function assignEvaluationParameter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'evaluation' => 'required',
        ]);

        if ($validator->passes()) {
            $evaluation = Evaluation::findOrFail($request->evaluation);

            $isScore = false;

            foreach ($evaluation->parameters as $parameter) {
                if ($parameter->pivot->score) {
                    $isScore = true;
                }
            }

            if ($isScore) {
                Session::flash('alert', 'Sorry! Score already assigned, you can not modify this now.');
                return redirect()->back();
            }

            DB::beginTransaction();
            try {
                $evaluation->parameters()->detach();
                $evaluation->parameters()->attach($request->evaluationParameters, [
                    'year' => $evaluation->year,
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id(),
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);
                DB::commit();
                Session::flash('message', 'Success! Evaluation Parameter assigned successfully.');
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollback();
                Session::flash('errorMessage', 'Error assigning evaluation parameter.');
                return redirect()->back();
            }
        } else {
            Session::flash('errorMessage', 'Please Input Valid Data!');
            return redirect()->back();
        }
    }

    public function setupEvaluationParameter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'evaluation' => 'required',
        ]);

        if ($validator->passes()) {
            return redirect('employee/evaluations/' . $request->evaluation);
        } else {
            Session::flash('errorMessage', 'Please Input Valid Data!');
            return redirect()->back();
        }
    }

    public function setupUpdateEvaluationParameter(Request $request)
    {
        $evaluation = Evaluation::findOrFail($request->evaluationId);
        $scores = $request->scores;
        $array = [];

        foreach ($request->parameters as $key => $parameter) {
            $array[$parameter] = ['score' => $scores[$key]];
        }

        if ($evaluation->parameters()->sync($array)) {
            Session::flash('message', 'Success! Evaluation parameter setup successfully.');
            return redirect()->back();
        } else {
            Session::flash('errorMessage', 'Error setting up Evaluation parameter.');
            return redirect()->back();
        }
    }


    public function getEvaluationsForUser()
    {
        $user = Auth::user();

        if ($user->role()->id == 3) {
            $evaluations = Evaluation::with('parameters')->where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ])->whereIn('evaluation_by', [2, 3])->get();
        } else {
            $employee = EmployeeInformation::where('user_id', $user->id)->first();
            if (!$employee) {
                // return abort(404);
                return [];
            }

            $employeeDesignation = $employee->designation();
            $desEvaluations = $employeeDesignation->evaluationById();
            $allEvaluations = Evaluation::with('parameters')->where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ])->whereIn('evaluation_by', [1, 3])->get()->pluck('id');
            $evaluationsId = array_merge($desEvaluations->toArray(), $allEvaluations->toArray());

            $evaluations = Evaluation::whereIn('id', array_unique($evaluationsId))->get();
        }

        return $evaluations;
    }

    public function getAllEmployeesFromEvaluation($evaluation)
    {
        $allEmployees = null;
        if ($evaluation->evaluation_for == 1 || $evaluation->evaluation_for == 2) {
            $allEmployees = EmployeeInformation::where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
                'category' => $evaluation->evaluation_for
            ])->get();
        }

        return $allEmployees;
    }

    public function getAllStudentsFromEvaluation($evaluation)
    {
        $students = null;

        if ($evaluation->evaluation_for == 3) {
            $students = StudentProfileView::where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
            ])->get();
        }

        return $students;
    }


    public function evaluationView($id = '')
    {
        $evaluations = $this->getEvaluationsForUser();

        if (sizeOf($evaluations) < 1) {
            // return abort(404);

            $myEvaluation = null;
            $existingEvaluationMarks = null;
            $employees = null;
            $students = null;
        } else {
            if ($id) {
                $myEvaluation = Evaluation::with('parameters')->findOrFail($id);
            } else {
                $myEvaluation = $evaluations[0];
            }

            $existingEvaluationMarks = EvaluationMark::where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
                'evaluation_id' => $myEvaluation->id,
                'score_by' => Auth::id()
            ])->get();

            $employees = $this->getAllEmployeesFromEvaluation($myEvaluation);
            $students = $this->getAllStudentsFromEvaluation($myEvaluation);
        }

        return view('employee::pages.evaluation.evaluation', compact('evaluations', 'myEvaluation', 'employees', 'students', 'existingEvaluationMarks'));
    }


    public function evaluationScoreDistribution(Request $request)
    {
        $user = Auth::user();
        $userEmployeeId = null;
        if ($user->role()->id != 3) {
            $employeeInformation = EmployeeInformation::where('user_id', $user->id)->first();
            $userEmployeeId = $employeeInformation->designation()->id;
        }
        $evaluation = Evaluation::findOrFail($request->evaluationId);
        $parameters = $evaluation->parameters;

        $employees = $this->getAllEmployeesFromEvaluation($evaluation);
        $students = $this->getAllStudentsFromEvaluation($evaluation);

        DB::beginTransaction();
        try {
            if ($employees) {
                foreach ($employees as $key1 => $employee) {
                    $scores = [];
                    $onTotal = 0;
                    $total = 0;
                    $on100 = 0;
                    foreach ($parameters as $parameter) {
                        $name = 'es-' . $parameter->id;
                        if ($request->$name[$key1] > $parameter->pivot->score) {
                            Session::flash('errorMessage', 'Sorry! You can not give more than parameters score.');
                            return redirect()->back();
                        }
                        $scores[$parameter->id] = $request->$name[$key1];
                        $onTotal += $parameter->pivot->score;
                        $total += $scores[$parameter->id];
                    }
                    $jsonScores = json_encode($scores);
                    if ($onTotal) {
                        $on100 = ($total / $onTotal) * 100;
                    }
                    $remarks = $request->eRemarks[$key1];

                    $existingEvaluationMark = EvaluationMark::where([
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                        'evaluation_id' => $evaluation->id,
                        'score_by' => $user->id,
                        'score_for' => $employee->user_id
                    ])->first();

                    if ($existingEvaluationMark) {
                        $existingEvaluationMark->update([
                            'parameters_score' => $jsonScores,
                            'total' => $total,
                            'on100' => $on100,
                            'remarks' => $remarks,
                            'updated_at' => Carbon::now(),
                            'updated_by' => $user->id,
                        ]);
                    } else {
                        EvaluationMark::insert([
                            'evaluation_id' => $evaluation->id,
                            'score_by' => $user->id,
                            'score_for' => $employee->user_id,
                            'parameters_score' => $jsonScores,
                            'total' => $total,
                            'on100' => $on100,
                            'year' => $evaluation->year,
                            'remarks' => $remarks,
                            'score_by_type' => ($user->role()->id == 3) ? '2' : '1',
                            'score_for_type' => $evaluation->evaluation_for,
                            'score_by_designation' => $userEmployeeId,
                            'score_for_designation' => $employee->designation()->id,
                            'created_at' => Carbon::now(),
                            'created_by' => $user->id,
                            'campus_id' => session()->get('campus'),
                            'institute_id' => session()->get('institute')
                        ]);
                    }
                }
            }

            if ($students) {
                foreach ($students as $key1 => $student) {
                    $scores = [];
                    $onTotal = 0;
                    $total = 0;
                    $on100 = 0;
                    foreach ($parameters as $parameter) {
                        $name = 'ss-' . $parameter->id;
                        if ($request->$name[$key1] > $parameter->pivot->score) {
                            Session::flash('alert', 'Sorry! You can not give more than parameters score.');
                            return redirect()->back();
                        }
                        $scores[$parameter->id] = $request->$name[$key1];
                        $onTotal += $parameter->pivot->score;
                        $total += $scores[$parameter->id];
                    }
                    $jsonScores = json_encode($scores);
                    if ($onTotal) {
                        $on100 = ($total / $onTotal) * 100;
                    }
                    $remarks = $request->sRemarks[$key1];

                    $existingEvaluationMark = EvaluationMark::where([
                        'campus_id' => session()->get('campus'),
                        'institute_id' => session()->get('institute'),
                        'evaluation_id' => $evaluation->id,
                        'score_by' => $user->id,
                        'score_for' => $student->user_id
                    ])->first();

                    if ($existingEvaluationMark) {
                        $existingEvaluationMark->update([
                            'parameters_score' => $jsonScores,
                            'total' => $total,
                            'on100' => $on100,
                            'remarks' => $remarks,
                            'updated_at' => Carbon::now(),
                            'updated_by' => $user->id,
                        ]);
                    } else {
                        EvaluationMark::insert([
                            'evaluation_id' => $evaluation->id,
                            'score_by' => $user->id,
                            'score_for' => $student->user_id,
                            'parameters_score' => $jsonScores,
                            'total' => $total,
                            'on100' => $on100,
                            'year' => $evaluation->year,
                            'remarks' => $remarks,
                            'score_by_type' => ($user->role()->id == 3) ? '2' : '1',
                            'score_for_type' => $evaluation->evaluation_for,
                            'created_at' => Carbon::now(),
                            'created_by' => $user->id,
                            'campus_id' => session()->get('campus'),
                            'institute_id' => session()->get('institute')
                        ]);
                    }
                }
            }

            DB::commit();
            Session::flash('message', 'Success! Evaluation scores saved successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error saving evaluation scores.');
            return redirect()->back();
        }
    }

    public function evaluationSearchView()
    {
        $designations = EmployeeDesignation::where([
            'campus_id' => session()->get('campus'),
            'institute_id' => session()->get('institute')
        ])->get();
        $evaluationStatus = false;

        return view('employee::pages.evaluation.search-evaluation', compact('designations', 'evaluationStatus'));
    }


    public function getEvaluationMarksFromDesignation($designation)
    {
        $evaluationMarks = EvaluationMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'score_for_type' => $designation
        ])->get();

        return $evaluationMarks;
    }


    // Ajax Methods Start
    public function evaluationAjaxSearchYear(Request $request)
    {
        $years = [];

        if ($request->designation) {
            $years = $this->getEvaluationMarksFromDesignation($request->designation)->pluck('year')->toArray();
        }

        return array_unique($years);
    }


    public function evaluationAjaxSearchEvaluation(Request $request)
    {
        $year = $request->year;
        $evaluationsId = $this->getEvaluationMarksFromDesignation($request->designations)->where('year', $year)->pluck('evaluation_id')->toArray();

        return Evaluation::whereIn('id', array_unique($evaluationsId))->get();
    }
    // Ajax Methods End


    public function getFinalRemarksPosition($evaluationMarks)
    {
        // Calculating Remarks Position start
        $marks = array();
        foreach ($evaluationMarks as $key1 => $evaluationMark) {
            $totalMark = 0;
            foreach ($evaluationMark as $key2 => $individualMark) {
                $totalMark += $individualMark->total;
            }
            $marks['i-' . $key1] = $totalMark;
        }
        arsort($marks);
        $position = [];
        $i = 1;
        foreach ($marks as $key => $mark) {
            $id = explode('-', $key)[1];
            $position[$id] = $i;
            $i++;
        }
        // Calculating Remarks Position end

        return $position;
    }

    public function getUserNames()
    {
        $users = User::all();
        $userNames = [];
        foreach ($users as $key => $user) {
            $userNames[$user->id] = $user->name;
        }
        return $userNames;
    }


    public function evaluationSearch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'evaluationFor' => 'required',
            'year' => 'required'
        ]);

        if ($validator->passes()) {
            $designations = EmployeeDesignation::where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ])->get();
            $userNames = $this->getUserNames();


            if ($request->evaluationId) {
                $evaluationMarks = $this->getEvaluationMarksFromDesignation($request->evaluationFor)->where('year', $request->year)->where('evaluation_id', $request->evaluationId)->groupBy('score_for');
                $evaluation = Evaluation::findOrFail($request->evaluationId);
                $parameters = Evaluation::findOrFail($request->evaluationId)->parameters;
                $evaluationStatus = 1;

                return view('employee::pages.evaluation.search-evaluation', compact('evaluationMarks', 'designations', 'evaluation', 'parameters', 'evaluationStatus', 'userNames'));
            } else {
                $evaluationMarks = $this->getEvaluationMarksFromDesignation($request->evaluationFor)->where('year', $request->year)->groupBy('score_for');
                $evaluationsId = $this->getEvaluationMarksFromDesignation($request->evaluationFor)->where('year', $request->year)->pluck('evaluation_id')->toArray();

                $evaluationMarks = $evaluationMarks->all();
                $evaluations = Evaluation::whereIn('id', array_unique($evaluationsId))->get();
                $evaluationStatus = 2;
                $position = $this->getFinalRemarksPosition($evaluationMarks);
                $userNames = $this->getUserNames();
                $year = $request->year;

                return view('employee::pages.evaluation.search-evaluation', compact('designations', 'evaluationMarks', 'evaluations', 'evaluationStatus', 'position', 'userNames', 'year'));
            }
        } else {
            Session::flash('errorMessage', 'Please Input Valid Data!');
            return redirect()->back();
        }
    }

    public function evaluationHistoryView()
    {
        $designations = EmployeeDesignation::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        return view('employee::pages.evaluation.history-evaluation', compact('designations'));
    }

    public function evaluationHistory(Request $request)
    {
        $evaluationMarks = $this->getEvaluationMarksFromDesignation($request->evaluationFor)->where('year', $request->year)->where('evaluation_id', $request->evaluationId)->groupBy('score_for');
        $scoreById = $this->getEvaluationMarksFromDesignation($request->evaluationFor)->where('year', $request->year)->where('evaluation_id', $request->evaluationId)->pluck('score_by')->toArray();

        $designations = EmployeeDesignation::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $evaluation = Evaluation::findOrFail($request->evaluationId);
        $userIds = array_unique($scoreById);
        $userNames = $this->getUserNames();

        return view('employee::pages.evaluation.history-evaluation', compact('evaluationMarks', 'designations', 'evaluation', 'userIds', 'userNames'));
    }
}

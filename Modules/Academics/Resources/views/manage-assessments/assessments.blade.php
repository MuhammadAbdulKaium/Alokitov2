
@extends('academics::manage-assessments.index')

<!-- page content -->
@section('page-content')

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <p class="text-right pull-right">
                <a class="btn btn-success" href="{{url('academics/manage/assessments/grade/weight_average/assign')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">
                    <i class="fa fa-plus-square" aria-hidden="true"></i> Assign W/A
                </a>
                <a class="btn btn-success" href="{{url('academics/manage/assessments/grade/scale/assign')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-plus-square" aria-hidden="true"></i> Assign Scale</a>
                <a class="btn btn-success" href="{{url('academics/manage/assessments/grade/category/assign')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-plus-square" aria-hidden="true"></i> Assign Assessment</a>
                <a class="btn btn-success" href="{{url('academics/manage/assessments/assessment/create')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-plus-square" aria-hidden="true"></i> Add Assessment</a>
            </p>
            @if($allGradeScale->count()>0)<h4 class="pull-left"><strong>Filter Assessments</strong></h4>@endif
        </div>
    </div>

    <div class="row">
        @foreach($allGradeScale as $scale)
            @if($scale->assessmentsCount()>0)
                <div class="col-md-10 col-md-offset-1">
                    {{--<h5 class="text-center text-info bg-green">--}}

                    {{--</h5>--}}

                    <table class="table table-bordered text-center bg-gray-light text-black">
                        <thead>
                        <tr class="bg-green-active">
                            <th colspan="4" class="text-center text-bold text-capitalize">
                                {{$scale->name}}
                                <a style="margin: 1px 10px 1px 1px" class="pull-right text-color-white" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md" href="/academics/manage/assessments/details/{{$scale->id}}">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i> Details
                                </a>
                            </th>
                        </tr>
                        <tr class="bg-green">
                            <th>Assessment Name</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>

                        <tbody id="assessmentbody">
                        @if($scale->assessmentCategory())
                            @foreach($scale->assessmentCategory() as $category)
                                @if($category->allAssessmentCounter($scale->id))
                                    <tr class="bg-gray">
                                        <td colspan="7">
                                            <b>{{$category->name}}</b>
                                            <span style="font-size: 10px;"></span>
                                        </td>
                                    </tr>
                                    @if($category->allAssessment($scale->id))
                                        @foreach($category->allAssessment($scale->id) as $assessment)
                                            <tr class="bg-success">
                                                <td>{{$assessment->name}}</td>
                                                @php $categoryProfile = $assessment->gradeCategory(); @endphp
                                                <td>{{$categoryProfile->name}}</td>
                                                <td>
                                                    @if($assessment->status==0)
                                                        <span class="label label-info">Pending</span>
                                                    @else
                                                        <span class="label label-success">Approved</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="/academics/manage/assessments/assessment/edit/{{$assessment->id}}" title="Edit" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><span class=" fa fa-pencil-square-o"></span></a>
                                                    <a href="/academics/manage/assessments/assessment/destroy/{{$assessment->id}}" title="Delete" data-confirm="Are you sure you want to delete this item?" data-method="get"><span class="glyphicon glyphicon-trash"></span></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endif
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            @endif
        @endforeach
    </div>
@endsection 

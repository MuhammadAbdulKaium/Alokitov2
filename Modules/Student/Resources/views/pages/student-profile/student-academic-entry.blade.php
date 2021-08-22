@extends('student::pages.student-profile.profile-layout')

@section('profile-content')
    <div class="row">

        {{--batch string--}}
        @php $batchString="Class"; @endphp
        {{--student enrollment--}}
        @php $enrollment = $personalInfo->enroll(); @endphp

        @if(Auth::user()->can('promote-student'))
            <div class="col-md-12">
                <p>
                    {{--<a id="course-enroll" class="btn btn-success pull-right" href="#" data-target="#globalModal" data-toggle="modal" style="margin-left: 10px;">--}}
                        {{--<i class="fa fa-plus-square" aria-hidden="true"></i> New Enroll--}}
                    {{--</a>--}}
                    <a class="btn btn-success pull-right" href="{{url('/student/course-edit/'.$enrollment->id)}}" data-target="#globalModal" data-toggle="modal">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
                    </a>
                </p>
            </div>
        @endif
    </div>
    {{--std enroll--}}
    <form action="/setting/academics/subject/entry" method="post">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="std_id" value="{{$std_id}}">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="exampleInputEmail1">Date</label>
                    <input type="date" class="form-control" id="exampleInputDate" name="date" required>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="exampleInputEmail1">Exam Name</label>
                    <input type="text" class="form-control" id="exampleInputDate" name="exam_name" required>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group ">
                    <label for="exampleInputEmail1" >Class</label>
                    <select name="class_id" id="" class="form-control">
                        <option value="">Class 7</option>
                        <option value="">Class 8</option>
                        <option value="">Class 9</option>
                        <option value="">Class 10</option>
                        <option value="">Class 11</option>
                        <option value="">Class 12</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($activities as $activity)
                <?php $points = \App\Helpers\AppHelper::CadetActivityPoint($activity->id) ?>
            <div class="form-group col-md-3">
{{--                <input name="subject_name[]" value="{{$activity->activity_name}}" type="hidden">--}}
                <label for="exampleInputEmail1">{{$activity->activity_name}}</label>
                <select name="subject_point[{{$activity->id}}]" class="form-control class_value">
                    <option value="">Select</option>
                    @foreach($points as $item)
                        <option value="{{$item->point}}">{{$item->value}} ({{$item->point}})</option>
                    @endforeach
                </select>

            </div>
            @endforeach
        </div>
        <div class="row">

            <div class="col-md-2 col-md-offset-10">
                <div class="form-group">
                    <div id="tot_amount"></div>
                </div>
            </div>
            <div class="col-md-2 col-md-offset-10">
                <div class="form-group">
                    <label for="">Total</label>
                    <input type="text" id="totalValue" name="totalValue" readonly>
                </div>
            </div>
        </div>
        <input type="submit" id="submit" name="Submit" class="btn btn-success">

    </form>

@endsection

@section('scripts')

    <script>
        var totalPoint=0;
        $(document).ready(function() {
            $(".class_value").change(function ()
            {
                var sum = 0;
                $('select :selected').each(function() {
                    sum += Number($(this).val());
                });
                $("#totalValue").val(sum);
            });
        });
    </script>
@endsection
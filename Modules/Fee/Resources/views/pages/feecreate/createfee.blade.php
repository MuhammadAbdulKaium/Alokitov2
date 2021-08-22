@extends('fee::layouts.feecreate')
<!-- page content -->
@section('page-content')
    <!-- grading scale -->
    <div class="box-body">

        @if(Session::has('message'))
            <div class="alert alert-success" role="alert">
                {{ Session::get('message') }}</div>
        @endif

        {{--fee head create--}}
        <form  action="{{URL::to('/fee/feesubhead/store')}}" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="row">
                <h3 class="text-center">Fee Create</h3>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label" for="feehead">Fee Head Name</label>
                        <select name="head_id" class="form-control" id="feehead">
                            <option value="">Select Fee Head</option>
                            @foreach($feeHeads as $head)
                                <option value="{{$head->id}}">{{$head->name}}</option>
                            @endforeach
                        </select>
                        <div class="help-block"></div>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="feehead">Fee Sub Head</label>
                        <input type="text" name="sub_head" class="form-control">
                        <div class="help-block"></div>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="feehead">Class</label>
                        <select name="class_id" class="form-control academicBatch" id="feehead">
                            <option value="">Select Class</option>
                            @foreach($batchs as $batch)
                                @if($batch->get_division())
                                    <option value="{{$batch->id}}">{{$batch->batch_name.' '.$batch->get_division()->name}}</option>
                                @else
                                    <option value="{{$batch->id}}">{{$batch->batch_name}}</option>
                                @endif
                            @endforeach
                        </select>
                        <div class="help-block"></div>
                    </div>


                    <div class="form-group">
                        <label class="control-label" for="feehead">Amount</label>
                        <input type="text" name="amount" class="form-control">
                        <div class="help-block"></div>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="feehead">Start Date</label>
                        <input type="date" name="start_date" class="form-control">
                        <div class="help-block"></div>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="feehead">Due Date</label>
                        <input type="date" name="due_date" class="form-control">
                        <div class="help-block"></div>
                    </div>

                </div>


                <div class="col-md-4 col-md-offset-1">
                    <div class="fundlist"></div>
                    {{--<p> Total Distribution 500TK</p>--}}
                </div>

            </div>

            <button type="submit" class="btn btn-success">Submit</button>
            <button type="reset" class="btn btn-primary" value="Reset">Reset</button>
        </form>
    </div>
    {{--end free head --}}

    {{--fee head list--}}
    {{--<table id="feehead" class="table table-striped table-bordered" style="margin-top: 20px">--}}
    {{--<thead>--}}
    {{--<tr>--}}
    {{--<th># NO</th>--}}
    {{--<th>Class</th>--}}
    {{--<th>Period</th>--}}
    {{--<th>Amount</th>--}}
    {{--<th>Action</th>--}}
    {{--</tr>--}}

    {{--</thead>--}}
    {{--<tbody>--}}

    {{--<tr class="gradeX">--}}
    {{--<td>1</td>--}}
    {{--<td>One</td>--}}
    {{--<td>1st Period</td>--}}
    {{--<td>20</td>--}}
    {{--<td>--}}
    {{--<a href="/fees/feetype/edit/1" class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>--}}
    {{--<a id="1" class="feetype_delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>--}}
    {{--</td>--}}
    {{--</tr>--}}


    {{--<tr class="gradeX">--}}
    {{--<td>2</td>--}}
    {{--<td>Two</td>--}}
    {{--<td>1st Period</td>--}}
    {{--<td>30</td>--}}
    {{--<td>--}}
    {{--<a href="/fees/feetype/edit/1" class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>--}}
    {{--<a id="1" class="feetype_delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>--}}
    {{--</td>--}}
    {{--</tr>--}}


    {{--<tr class="gradeX">--}}
    {{--<td>3</td>--}}
    {{--<td>Four</td>--}}
    {{--<td>2nd Period</td>--}}
    {{--<td>40</td>--}}
    {{--<td>--}}
    {{--<a href="/fees/feetype/edit/1" class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>--}}
    {{--<a id="1" class="feetype_delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>--}}
    {{--</td>--}}
    {{--</tr>--}}

    {{--<tr class="gradeX">--}}
    {{--<td>4</td>--}}
    {{--<td>Five</td>--}}
    {{--<td>1st Period</td>--}}
    {{--<td>50</td>--}}
    {{--<td>--}}
    {{--<a href="/fees/feetype/edit/1" class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>--}}
    {{--<a id="1" class="feetype_delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>--}}
    {{--</td>--}}
    {{--</tr>--}}

    {{--</tbody>--}}
    {{--</table>--}}



@endsection


@section('page-script')
    <script>
        $(document).ready(function () {
            $('#feehead').on('change', function() {
                var headid= $('#feehead').val();
                // ajax request
                $.ajax({
                    // get all fund list by fee head id
                    url: '{{URL::to('/fee/feehead/fundlist/')}}',
                    type: 'POST',
                    cache: false,
                    data: 'headid='+ headid,
                    datatype: 'json/application',

                    beforeSend: function() {
                        $('.fundlist').append('');
                    },

                    success:function(data){
                        $('.fundlist').empty();
                        $('.fundlist').append(data);
                    },

                    error:function(data){
                    }
                });
            });



        })

    </script>

@endsection

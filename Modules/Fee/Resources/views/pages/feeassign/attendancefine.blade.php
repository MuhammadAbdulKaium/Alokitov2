@extends('fee::layouts.feesassign')
<!-- page content -->
@section('page-content')
    <!-- grading scale -->
    <div class="box-body">
        {{--fee head create--}}
        <form  action="">
            <div class="row">

                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="control-label" for="feehead">Select Class</label>
                        <select class="form-control" id="period">
                            <option value="">Select Class</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                            <option value="4">Four</option>
                            <option value="5">Five</option>
                        </select>
                        <div class="help-block"></div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="control-label" for="feehead">Select Period</label>
                        <select class="form-control" id="period">
                            <option value="">Select Period</option>
                            <option value="1">1st Period</option>
                            <option value="2">2nd Period</option>
                            <option value="3">3rd Period</option>
                            <option value="4">4th Period</option>
                            <option value="5">5th Period</option>
                        </select>
                        <div class="help-block"></div>
                    </div>
                </div>



                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="control-label" for="feehead">Fine Amount</label>
                        <input class="form-control" type="text" name="feehead"/>
                        <div class="help-block"></div>
                    </div>
                </div>

            </div>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>
    {{--end free head --}}

    {{--fee head list--}}
    <table id="feehead" class="table table-striped table-bordered" style="margin-top: 20px">
        <thead>
        <tr>
            <th># NO</th>
            <th>Class</th>
            <th>Period</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>

        </thead>
        <tbody>

        <tr class="gradeX">
            <td>1</td>
            <td>One</td>
            <td>1st Period</td>
            <td>20</td>
            <td>
                <a href="/fees/feetype/edit/1" class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                <a id="1" class="feetype_delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
            </td>
        </tr>


        <tr class="gradeX">
            <td>2</td>
            <td>Two</td>
            <td>1st Period</td>
            <td>30</td>
            <td>
                <a href="/fees/feetype/edit/1" class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                <a id="1" class="feetype_delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
            </td>
        </tr>


        <tr class="gradeX">
            <td>3</td>
            <td>Four</td>
            <td>2nd Period</td>
            <td>40</td>
            <td>
                <a href="/fees/feetype/edit/1" class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                <a id="1" class="feetype_delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
            </td>
        </tr>

        <tr class="gradeX">
            <td>4</td>
            <td>Five</td>
            <td>1st Period</td>
            <td>50</td>
            <td>
                <a href="/fees/feetype/edit/1" class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                <a id="1" class="feetype_delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
            </td>
        </tr>

        </tbody>
    </table>

@endsection



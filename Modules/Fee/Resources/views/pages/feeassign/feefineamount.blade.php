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
                        <label class="control-label" for="feehead">Select Year</label>
                        <select class="form-control" id="period">
                            <option value="">Select Year</option>
                            <option value="1">2019</option>
                            <option value="2">2020</option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="control-label" for="feehead">Fee Head</label>
                        <select class="form-control" id="period">
                            <option value="">Select Fee Head</option>
                            <option value="1">Tuition Fee</option>
                            <option value="2">Admission Fee</option>
                        </select>
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
            <th>Fee Sub Head</th>
            <th>Due Date</th>
            <th>Due Amount</th>
        </tr>

        </thead>
        <tbody>

        <tr class="gradeX">
            <td>1</td>
            <td>January</td>
            <td><input type="date"> </td>
            <td><input type="number"> </td>
        </tr>


        <tr class="gradeX">
            <td>2</td>
            <td>January</td>
            <td><input type="date"> </td>
            <td><input type="number"> </td>
        </tr>


        <tr class="gradeX">
            <td>3</td>
            <td>March</td>
            <td><input type="date"> </td>
            <td><input type="number"> </td>
        </tr>

        <tr class="gradeX">
            <td>4</td>
            <td>April</td>
            <td><input type="date"> </td>
            <td><input type="number"> </td>
        </tr>

        <tr class="gradeX">
            <td>5</td>
            <td>May</td>
            <td><input type="date"> </td>
            <td><input type="number"> </td>
        </tr>

        <tr class="gradeX">
            <td>5</td>
            <td>June</td>
            <td><input type="date"> </td>
            <td><input type="number"> </td>
        </tr>

        </tbody>
    </table>
    <button type="submit" class="btn btn-success pull-right">Save</button>

@endsection



@extends('fee::layouts.feesassign')
<!-- page content -->
@section('page-content')

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
                        <div class="help-block"></div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="control-label" for="feehead">Select Class</label>
                        <select class="form-control" id="period">
                            <option value="">Select Class</option>
                            <option value="1">One </option>
                            <option value="2">Two</option>
                        </select>
                        <div class="help-block"></div>
                    </div>
                </div>



                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="control-label" for="feehead">Select Section</label>
                        <select class="form-control" id="period">
                            <option value="">Select Section</option>
                            <option value="1">A</option>
                            <option value="2">B</option>
                        </select>
                        <div class="help-block"></div>
                    </div>
                </div>

            </div>
            <button type="submit" class="btn btn-success">Search</button>
        </form>
    </div>


    <table id="feehead" class="table table-striped table-bordered" style="margin-top: 20px">
        <thead>
        <tr>
            <th>Select</th>
            <th>Student ID</th>
            <th>Name</th>
            <th>Roll</th>
        </tr>

        </thead>
        <tbody>

        <tr class="gradeX">
            <td><input type="checkbox" value=""></td>
            <td>STD0001</td>
            <td>Romesh SHil</td>
            <td>1</td>
           
        </tr>

        <tr class="gradeX">
            <td><input type="checkbox" value=""></td>
            <td>STD0002</td>
            <td>Gopal Das</td>
            <td>2</td>
           
        </tr>

        <tr class="gradeX">
            <td><input type="checkbox" value=""></td>
            <td>STD0003</td>
            <td>Dumon Das</td>
            <td>3</td>
           
        </tr>
        


        </tbody>
    </table>

@endsection
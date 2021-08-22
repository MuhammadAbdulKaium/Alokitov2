
@extends('layouts.master')


@section('styles')
    <!-- DataTables -->
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')

    {{--batch string--}}
    @php $batchString="Class"; @endphp

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Cadet Factor Entries
            </h1>
            <ul class="breadcrumb">
                <li><a href="{{URL::to('/home')}}"><i class="fa fa-home"></i>Home</a></li>
                <li class="active">Cadet Factor Entries</li>
            </ul>
        </section>
        <section class="content">
            <div class="box box-solid">
                <form id="std_manage_search_form" method="POST">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label class="control-label" for="academic_level">Date</label>
                                    <input type="date" class="form-control">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label class="control-label" for="academic_level">Academic Year</label>
                                    <select id="academic_level" class="form-control academicLevel" name="academic_level">
                                        <option value="">--- Select Year ---</option>
                                        {{--                                        @foreach($academicLevels as $level)--}}
                                        {{--                                            <option value="{{$level->id}}">{{$level->level_name}}</option>--}}
                                        {{--                                        @endforeach--}}
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>

                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label class="control-label" for="academic_level">Month</label>
                                    <input type="date" class="form-control">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label class="control-label" for="academic_level">Academic Level</label>
                                    <select id="academic_level" class="form-control academicLevel" name="academic_level">
                                        <option value="">--- Select Level ---</option>
                                        {{--                                        @foreach($academicLevels as $level)--}}
                                        {{--                                            <option value="{{$level->id}}">{{$level->level_name}}</option>--}}
                                        {{--                                        @endforeach--}}
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label class="control-label" for="section">Division</label>
                                    <select id="section" class="form-control academicSection" name="section">
                                        <option value="" selected>--- Select Division ---</option>

                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label class="control-label" for="section">Class</label>
                                    <select id="section" class="form-control academicSection" name="section">
                                        <option value="" selected>--- Select Section ---</option>

                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label class="control-label" for="section">Section</label>
                                    <select id="section" class="form-control academicSection" name="section">
                                        <option value="" selected>--- Select Section ---</option>

                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label class="control-label" for="section">Entry for</label>
                                    <select id="section" class="form-control academicSection" name="section">
                                        <option value="" selected>--- Select Section ---</option>

                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label class="control-label" for="section">category</label>
                                    <select id="section" class="form-control academicSection" name="section">
                                        <option value="" selected>--- Select Section ---</option>

                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label class="control-label" for="section">Entity</label>
                                    <select id="section" class="form-control academicSection" name="section">
                                        <option value="" selected>--- Select Section ---</option>

                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label" for="section">Cadet Number & Name</label>
                                    <select id="section" class="form-control academicSection" name="section">
                                        <option value="" selected>--- Select Section ---</option>

                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ./box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-info pull-right">Search</button>
                        <button type="reset" class="btn btn-default">Reset</button>
                    </div>
                </form>
            </div>
            {{--std list container--}}
            <div id="std_list_container_row" class="row">
            </div>
        </section>
        <div class="content">
            <div class="box-body table-responsive  box box-solid">
                <table id="example1" class="table table-striped">
                    <thead>
                    <tr>
                        <th>SL</th>
                        <th>Photo</th>
                        <th>Cadet Number</th>
                        <th>Cadet Name</th>
                        <th>Admission Year</th>
                        <th>Academic Year</th>
                        <th>Division</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Roll</th>
                        <th>Category</th>
                        <th>Sub Category</th>
                        <th>Parameter</th>
                        <th>Score</th>
                        <th>New Score & Point</th>
                        <th>Action</th>
                        <th>Details</th>
                        <th>History</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        </td>
                        <td>Photo</td>
                        <td>1234</td>
                        <td>Masuma Khatun</td>
                        <td>2020</td>
                        <td>2020</td>
                        <td>Science</td>
                        <td>Class 10</td>
                        <td>A</td>
                        <td>1</td>
                        <td>
                            <div class="form-group">
                                <select id="section" class="form-control academicSection" name="section">
                                    <option value="" selected>--- Select Section ---</option>

                                </select>
                                <div class="help-block"></div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <select id="section" class="form-control academicSection" name="section">
                                    <option value="" selected>--- Select Section ---</option>

                                </select>
                                <div class="help-block"></div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <select id="section" class="form-control academicSection" name="section">
                                    <option value="" selected>--- Select Section ---</option>

                                </select>
                                <div class="help-block"></div>
                            </div>
                        </td>
                        <td>4-Very Good</td>
                        <td>24/08/2020</td>
                        <td>Action</td>
                        <td>Details</td>
                        <td>History</td>
                    </tr>

                    </tbody>
                </table>
                {{--paginate--}}
            </div>
        </div>
    </div>


@endsection



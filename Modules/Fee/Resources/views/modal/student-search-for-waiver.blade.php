<link rel="stylesheet" type="text/css" href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.6/css/dataTables.checkboxes.css">

<form id="studentList">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" class="studentIds" name="studentId" value="">
    <table id="studentlistTable" class="table table-striped table-bordered" style="margin-top: 20px">
    <thead>
    <tr>
        <th><button type="button" id="selectAll" class="main btn-primary">Check/Uncheck</button></th>
        <th>Student Name</th>
        <th>User Name/ID</th>
        <th>Roll</th>
    </tr>

    </thead>
    <tbody>
    @foreach($studentList as $student)
    <tr class="gradeX">
        <td><input type="checkbox" name="student_id[]" class="call-checkbox" value="{{$student->std_id}}"> </td>
        <td>{{$student->first_name.' '.$student->middle_name.''.$student->last_name}}</td>
        <td>{{$student->username}}</td>
        <td>{{$student->gr_no}}</td>
    </tr>

    @endforeach


    </tbody>
</table>
    <button type="button" class="btn btn-primary pull-right WaiverProcess">Process</button>
</form>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">

                <form class="form-horizontal" action="{{URL::to('/fee/waiver/assign/store')}}" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" id="StudentIDs" name="student_ids" value="">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Fee Head:</label>
                        <div class="col-sm-4">
                            <select class="form-control" id="sel1" name="feehead" >
                                @foreach($feeheads as $head)
                                    <option value="{{$head->id}}">{{$head->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Waiver Type:</label>
                        <div class="col-sm-4">
                            <select class="form-control" id="waiverType" name="waiver_type">
                                @foreach($feewaiverTypes as $waiver)
                                    <option value="{{$waiver->id}}">{{$waiver->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Percentage/Amount:</label>
                        <div class="col-sm-4">
                            <select class="form-control" id="percentage_amount" name="percentage_amount">
                                <option value="1">Percentage</option>
                                <option value="2">Amount</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="pwd">Amount</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="pwd" placeholder="Enter Amount" name="amount">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-4">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


<script>

    var oTable = $('#studentlistTable').dataTable({
        stateSave: true
    });

    var allPages = oTable.fnGetNodes();

    $('body').on('click', '#selectAll', function () {
        alert(1);
        if ($(this).hasClass('allChecked')) {
            $('input[type="checkbox"]', allPages).prop('checked', false);
        } else {
            $('input[type="checkbox"]', allPages).prop('checked', true);
        }
        $(this).toggleClass('allChecked');
    });

    $('.WaiverProcess').on('click', function(e){
        e.preventDefault();
        var matches = [];
        var oTable = $('#studentlistTable').dataTable();
        var rowcollection =  oTable.$(".call-checkbox:checked", {"page": "all"});
        rowcollection.each(function(index,elem){
            matches.push($(elem).val());
            //Do something with 'checkbox_value'
        });

//        alert(JSON.stringify(matches));
//            alert(100);
        $('#StudentIDs').val(matches);
        $('#myModal').modal('show');


    });



</script>
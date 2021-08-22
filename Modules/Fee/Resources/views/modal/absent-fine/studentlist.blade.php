<link rel="stylesheet" type="text/css" href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.6/css/dataTables.checkboxes.css">

<table id="studentListTable" class="table table-striped table-bordered" style="margin-top: 20px">
    <thead>
    <tr>
        <th><button type="button" id="selectAll" class="btn btn-primary">Check</button></th>
        <th>STD ID</th>
        <th>Name</th>
        <th>Roll</th>
        <th>Total Absent</th>
        <th>Fine Rate</th>
        <th>Total Fine</th>
        <th>Payable</th>
        <th>Pay</th>
    </tr>
    </thead>
    <tbody>

    @foreach($absentStudentList as $student)

        <tr class="gradeX">
            <td width="10%"><input type="checkbox" name="student_ids[]" class="call-checkbox" value="{{$student->std_id}}"> </td>
            @php $studentProfile=$student->studentProfile(); @endphp
            <td width="15%">{{$studentProfile->username}}</td>
            <td width="20%">{{$studentProfile->first_name.' '.$studentProfile->middle_name.' '.$studentProfile->last_name}}</td>
            <td>{{$studentProfile->gr_no}}</td>
            <td>{{$student->total_absent}}</td>
            <td>{{$fineRate->amount}}</td>
            @php $alreadyPaid =0;
            $totalAbsentFineAmount=$fineRate->amount*$student->total_absent;
            $alreadyPaid=$student->totalAbsentAmountPaid($student->std_id)
            @endphp
            <td>{{$totalAbsentFineAmount}}</td>
            <td>{{$totalAbsentFineAmount-$alreadyPaid}}</td>
            <td><a href="/fee/collection/absent/single/{{$student->std_id}}/{{$fineRate->amount}}" class="btn btn-primary" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Pay</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
<button type="button" class="btn btn-primary pull-right AbsentFineProcess">Process</button>
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

                <form class="form-horizontal" action="{{URL::to('/fee/collection/multiple/student/store')}}" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" id="studentInvoiceIds" name="invoice_ids" value="">
                    <h4 style="display: inline-flex">Total Amount: <p class="total_amount_modal"></p>TK.</h4>
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

    var oTable = $('#studentListTable').dataTable({
        stateSave: true
    });

    var allPages = oTable.fnGetNodes();

    $('body').on('click', '#selectAll', function () {
        if ($(this).hasClass('allChecked')) {
            $('input[type="checkbox"]', allPages).prop('checked', false);
        } else {
            $('input[type="checkbox"]', allPages).prop('checked', true);
        }
        $(this).toggleClass('allChecked');
    });

    $('.AbsentFineProcess').on('click', function(e){
        alert(2);
        e.preventDefault();
        var matches = [];
        var totalAmount=0;
        var oTable = $('#studentListTable').dataTable();
        var rowcollection =  oTable.$(".call-checkbox:checked", {"page": "all"});
        rowcollection.each(function(index,elem){

            var row = $(this).closest("tr")[0];
            totalAmount += parseFloat(row.cells[7].innerHTML);
            if(totalAmount>0){
                matches.push($(elem).val());
            }
//                alert(totalAmount);
            //Do something with 'checkbox_value'
        });

//        alert(JSON.stringify(matches));
//            alert(100);
        $('#studentInvoiceIds').val(matches);
        $('.total_amount_modal').html(totalAmount);
        $('#myModal').modal('show');


    });

</script>
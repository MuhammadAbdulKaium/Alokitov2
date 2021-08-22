<link rel="stylesheet" type="text/css" href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.6/css/dataTables.checkboxes.css">

<div class="row">
    <div class="col-md-8 col-md-offset-2 userprofile">
        <div class="col-md-3 col-md-offset-1">
            @if($studentProfile->singelAttachment("PROFILE_PHOTO"))
                <img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/'.$studentProfile->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:100px;height:100px">
            @else
                <img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:100px;height:100px">
            @endif
            {{--<img src="http://songtr.ee/songs/userimages/cache/imgid822526_1000x1000.jpg" class="img-rounded" alt="Cinque Terre" width="100" height="100">--}}
        </div>
        <div class="col-sm-4">
{{--            {{dd($studentProfile)}}--}}
            <ul class="list-group">
                <li class="list-group-item text-right"><span class="pull-left"><strong class="">Name:</strong></span> {{$studentProfile->first_name.' '.$studentProfile->middle_name.' '.$studentProfile->last_name}}</li>
                <li class="list-group-item text-right"><span class="pull-left"><strong class="">Roll</strong></span>  {{$studentProfile->gr_no}}
                </li>
            </ul>

        </div>

        <div class="col-sm-4">
            <ul class="list-group">
                <li class="list-group-item text-right"><span class="pull-left"><strong class="">Class: </strong></span> {{$studentProfile->batch()->batch_name}}</li>
                <li class="list-group-item text-right"><span class="pull-left"><strong class="">Section: </strong></span> {{$studentProfile->section()->section_name}}
                </li>
            </ul>
        </div>
    </div>
</div>




<table id="invoiceListTable" class="table table-striped table-bordered" style="margin-top: 20px">
    <thead>
    <tr>
        <th><button type="button" id="selectAll" class="main btn-primary">Check/Uncheck</button></th>
        <th>Fee Head</th>
        <th>Sub Head</th>
        <th>Amount</th>
        <th>Waiver</th>
        {{--<th>Fine</th>--}}
        <th>Paid Amount</th>
        <th>Payable Amount</th>
        <th>Take</th>
        <th>Action</th>
    </tr>

    </thead>
    <tbody>
    @php  $fine=0;$total=0; @endphp
    @foreach($studentInvoiceList as $invoice)
    <tr class="tr_{{$invoice->id}}">
        <td><input type="checkbox" name="invoice_id[]" class="call-checkbox" value="{{$invoice->id}}"> </td>
        <td>{{$invoice->feehead()->name}}</td>
        <td>{{$invoice->subhead()->name}}</td>
        <td class="amount">{{$invoice->amount}}</td>
        <td>
            @php $waiverProfile=$invoice->isWaiver($invoice->student_id,$invoice->head_id,$invoice->amount) @endphp
            {{$waiverProfile['waiver']}}
        </td>
        @php $payableAmount=($invoice->amount-$waiverProfile['waiver'])-$invoice->paid_amount @endphp


        <td class="paid_amount_{{$invoice->id}}">{{$invoice->paid_amount}}</td>
        <td class="payableAmount due_amount_{{$invoice->id}}">{{$payableAmount}}</td>
        <td>
            @if($invoice->status=='paid')
                <span class="label label-success">Paid</span>
            @else
            <a  href="/fee/invoice/payment/single/{{$invoice->id}}" class="btn btn-primary" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Pay</a>
            @endif
        </td>
        <td><a id="{{$invoice->id}}" class="delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a> </td>

    </tr>

  @endforeach

    </tbody>
</table>
<button type="button" class="btn btn-success InvoiceProcess">Process</button>

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
                    <input type="hidden" id="studentInvoicePayable" name="invoice_payable" value="">

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
//    alert(22);

    var oTable = $('#invoiceListTable').dataTable({
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

    $('.InvoiceProcess').on('click', function(e){
        e.preventDefault();
        var matches = [];
        var paybale = [];
        var totalAmount=0;
        var oTable = $('#invoiceListTable').dataTable();
        var rowcollection =  oTable.$(".call-checkbox:checked", {"page": "all"});
        rowcollection.each(function(index,elem){

            var row = $(this).closest("tr")[0];
            totalAmount += parseFloat(row.cells[6].innerHTML);
            if(totalAmount>0){
                matches.push($(elem).val());
                paybale.push(parseFloat(row.cells[6].innerHTML));
            }
//                alert(totalAmount);
            //Do something with 'checkbox_value'
        });

//        alert(JSON.stringify(matches));
//            alert(100);
        $('#studentInvoiceIds').val(matches);
        $('#studentInvoicePayable').val(paybale);
        $('.total_amount_modal').html(totalAmount);
        $('#myModal').modal('show');


    });

//    $('.testButton').click(function () {
//        alert(200);
//    })


// invoice delete ajax request
$('.delete_class').click(function(e){
    var tr = $(this).closest('tr'),
        del_id = $(this).attr('id');

    swal({
            title: "Are you sure?",
            text: "You want to delete invoice?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes!',
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm) {

            if (isConfirm) {
                $.ajax({
                    url: "/fee/student/invoice/delete/" + del_id,
                    type: 'GET',
                    cache: false,
                    success: function (result) {
                        tr.fadeOut(1000, function () {
                            $(this).remove();
                        });
                        swal("Success!", "Invoice Successfully Deleted", "success");

                    }
                });
            } else {
                swal("NO", "Your Fee and Invoice is safe :)", "error");
                e.preventDefault();
            }
        });
});

</script>

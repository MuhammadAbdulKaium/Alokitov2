<link rel="stylesheet" type="text/css" href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.6/css/dataTables.checkboxes.css">

<table id="invoiceListTable" class="table table-striped table-bordered" style="margin-top: 20px">
        <thead>
        <tr>
            <th><button type="button" id="selectAll" class="main btn-primary">Check/Uncheck</button></th>
            <th>STD ID</th>
            <th>Name</th>
            <th>Roll</th>
            <th>Amount</th>
            <th>Waiver</th>
            {{--<th>Fine</th>--}}
            <th>Paid Amount</th>
            <th>Payable Amount</th>
            <th>Action</th>
        </tr>

        </thead>
        <tbody>

{{--        {{$feeInvoiceList}}--}}
@php $waiver=0; @endphp

        @foreach($feeInvoiceList as $invoice)

            <tr class="gradeX">
                <td><input type="checkbox" name="invoice_id[]" class="call-checkbox" value="{{$invoice->id}}"> </td>
                @php $studentProfile=$invoice->studentProfile(); @endphp
                <td>{{$studentProfile->username}}</td>
                <td>{{$studentProfile->first_name.' '.$studentProfile->middle_name.' '.$studentProfile->last_name}}</td>
                <td>{{$studentProfile->gr_no}}</td>
                <td>{{$invoice->amount}}</td>
                <td>
                    @php $waiverProfile=$invoice->isWaiver($invoice->student_id,$invoice->head_id,$invoice->amount) @endphp
                    {{$waiverProfile['waiver']}}
                    @php $payableAmount=($invoice->amount-$waiverProfile['waiver'])-$invoice->paid_amount @endphp
                </td>
                <td>{{$invoice->paid_amount}}</td>
                <td class="payableAmount">{{$payableAmount}}</td>
                    {{--<a href="/fees/feetype/edit/1" class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>--}}
                <td><a id="{{$invoice->id}}" class="delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a> </td>
            </tr>
        @endforeach

        </tbody>
</table>
    <button type="button" class="btn btn-primary pull-right InvoiceProcess">Process</button>
    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Payment</h4>
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
//            alert(2);
            e.preventDefault();
            var matches = [];
            var paybale = [];
            var totalAmount=0;
            var oTable = $('#invoiceListTable').dataTable();
            var rowcollection =  oTable.$(".call-checkbox:checked", {"page": "all"});
            rowcollection.each(function(index,elem){

                var row = $(this).closest("tr")[0];
//                alert(row);
                totalAmount += parseFloat(row.cells[7].innerHTML);
                if(totalAmount>0){
                    matches.push($(elem).val());
                    paybale.push(parseFloat(row.cells[7].innerHTML));
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
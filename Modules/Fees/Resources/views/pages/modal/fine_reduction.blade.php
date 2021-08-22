<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title" id="gsmTitle">Fine Reduction</h4>
</div>
<div class="modal-body">

    <div class="alert-success alert-auto-hide alert fade in update" id="w0-success-0" style="opacity: 423.642;">
        <button aria-hidden="true" class="close" data-dismiss="alert" type="button">×</button>
        <p><i class="icon fa fa-check"></i>Fine Reduction Successfully Updated</p>
    </div>

    <div class="alert-success alert-auto-hide alert fade in create" id="w0-success-0" style="opacity: 423.642;">
        <button aria-hidden="true" class="close" data-dismiss="alert" type="button">×</button>
        <p><i class="icon fa fa-check"></i>Fine Reduction Successfully Created</p>
    </div>

    <form class="form-horizontal" id="fineReduction" method="post">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="invoice_id" value="{{$invoiceId}}">
        <div class="form-group">
            <label class="control-label col-sm-4 full-left" for="email">Due Fine</label>
            <div class="col-sm-2">
                <input type="number" class="form-control" name="due_fine" readonly value="{{$dueFine}}" id="dueFine">
            </div>
            <div class="col-sm-2">
                <input type="number" class="form-control" name="reduction_due_fine" id="reductionDueFine" @if(!empty($fineReductionProfile)) value="{{$fineReductionProfile->due_fine}}" @else value="0" @endif>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="pwd">Attendance Fine:</label>
            <div class="col-sm-2">
                <input type="number" class="form-control" readonly value="{{$attendanceFine}}" name="attendance_fine" id="attendanceFine" >
            </div>
            <div class="col-sm-2">
                <input type="number" class="form-control" name="reduction_attendance_fine" id="reductionAttendanceFine" @if(!empty($fineReductionProfile)) value="{{$fineReductionProfile->attendance_fine}}" @else value="0" @endif >
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-4" for="pwd">Total:</label>
            <div class="col-sm-2">
                <input type="number" class="form-control" readonly name="total_fine" value="" id="totalFine">
            </div>
            <div class="col-sm-2">
                <input type="number" class="form-control" name="total_reduction"  readonly value="0" id="totalReduction">
            </div>
        </div>
        <div class="modal-footer">
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-4">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>


    </form>

</div>

<script>

    $(document).ready(function () {

        $('.create').hide();
        $('.update').hide();

        var due_fine=parseInt($('#dueFine').val());
        var attendance_fine=parseInt($('#attendanceFine').val());
        var total_fine=due_fine+attendance_fine;
        $("#totalFine").val(total_fine);

        var reduction_due_fine=parseInt($('#reductionDueFine').val());
        var reduction_attendance_fine=parseInt($('#reductionAttendanceFine').val());
        var total_reduction=reduction_due_fine+reduction_attendance_fine;
        $("#totalReduction").val(total_reduction);



        $('#reductionDueFine').keyup(function(){
            var reduction_due_fine=parseInt($('#reductionDueFine').val());
            var reduction_attendance_fine=parseInt($('#reductionAttendanceFine').val());
            var total_reduction=reduction_due_fine+reduction_attendance_fine;
            $("#totalReduction").val(total_reduction);
        });

        $('#reductionAttendanceFine').keyup(function(){
            var reduction_due_fine=parseInt($('#reductionDueFine').val());
            var reduction_attendance_fine=parseInt($('#reductionAttendanceFine').val());
            var total_reduction=reduction_due_fine+reduction_attendance_fine;
            $("#totalReduction").val(total_reduction);
        });

    });



    // request fine reduction data store
    $('form#fineReduction').on('submit', function (e) {
        e.preventDefault();
        // ajax request
        $.ajax({

            url: '/fees/student/fine-reduction/store',
            type: 'POST',
            cache: false,
            data: $('form#fineReduction').serialize(),
            datatype: 'json/application',

            beforeSend: function() {
                // alert($('form#PayerStudent').serialize());
            },

            success:function(data){
               if(data='update') {
                   $('.create').hide();
                   $('.update').show();
               } else {
                   $('.update').hide();
                   $('.create').show();
               }

                var reduction_due_fine=parseInt($('#reductionDueFine').val());
                var reduction_attendance_fine=parseInt($('#reductionAttendanceFine').val());
                var discount=parseInt($("#getDiscount").text());
                var subtotal=parseInt($("#getSubtotal").text());
                var totalAmount=subtotal+reduction_due_fine+reduction_attendance_fine-discount;

                  $("#getDueFine").text(reduction_due_fine);
                  $("#getAttendanceFine").text(reduction_attendance_fine);
                  $("#totalAmount").text(totalAmount);

            },

            error:function(data){
                alert('error');
            }
        });


    });





</script>


<!-- DataTables -->
<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>

<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">
        <i class="fa fa-info-circle"></i> Assinging Leave Type for the
    </h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-5">
            <table id="example1" class="table table-striped">
                <thead>
                <tr>
                    <th>Type Name</th>
                </tr>
                </thead>
                <tbody id="leaveTypeTable">
                @foreach($allLeaveTypes as $type)
                    <tr>
                        <td>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input id="{{$type->id}}" name="type_id" value="{{$type->id}}" type="checkbox">
                                        <input id="{{$type->id}}_name" value="{{$type->name}}" type="hidden">
                                        <strong>{{$type->name}}</strong>
                                    </label>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-7">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="box box-solid">
                <div class="et">
                    <div class="box-header with-border">
                        <h3 class="box-title">All Selected Types</h3>
                    </div>
                </div>
                <form action="{{url('/employee/manage/leave/structure/assign/type/store')}}" method="POST">
                    <input name="_token" value="{{csrf_token()}}" type="hidden">
                    <input id="tr_count" type="hidden" name="rows_no" value="0">
                    <input id="delete_count" type="hidden" name="delete_count" value="0">
                    <input id="structure_id" type="hidden" name="str_id" value="{{$strId}}">

                    <div class="box-body table-responsive">
                        <div class="box-body">
                            <table id="structure_type_table" class="table table-striped text-center table-bordered hide" role="grid" aria-describedby="example2_info">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type name</th>
                                    <th>Leave Days</th>
                                </tr>
                                </thead>
                                <tbody id="structueTypeTableBody"></tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="3">
                                        <button type="submit" class="btn btn-info pull-right">Submit</button>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="box-footer">
    <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
</div>
<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){

        $("#example1").DataTable();
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });

        $('.alert-auto-hide').fadeTo(7500, 500, function () {
            $(this).slideUp('slow', function () {
                $(this).remove();
            });
        });


        $('#leaveTypeTable :input').click(function(){
            // leave type information
            var type_id = $(this).attr('id');
            var type_name = $('#'+type_id+'_name').val();
            var row_count = parseInt($('#tr_count').val());

            //  checking
            if($(this).is(':checked')){
                var tr =  '<tr class="item" id="row_'+type_id+'"><td id="count_'+type_id+'">'+(row_count+1)+'</td><td>'+type_name+'<input type="hidden" id="name_'+type_id+'" name="'+(row_count+1)+'[type_id]" value="'+type_id+'"><input type="hidden" id="row_id_'+type_id+'" name="'+(row_count+1)+'[row_id]" value="0"></td><td><input class="form-control" id="days_'+type_id+'" type="text" name="'+(row_count+1)+'[days]" value="" required></td></tr>';

                // visible containing table
                $('#structure_type_table').removeClass('hide');
                // append table row
                $("#structueTypeTableBody").append(tr);
                // row counter increment
                $('#tr_count').val(row_count+1);
            }else{
                // make delete list
                var leaveStructureTypeId = $('#row_id_'+type_id).val();

                $('#row_'+type_id).remove();
                // row counter decrement
                $('#tr_count').val(row_count-1);
                // sorting order
                sortingOrder();

                // add the classSubject to the deletelist
                if(leaveStructureTypeId > 0){
                    var deleteCounter = parseInt($('#delete_count').val());
                    var deleteCount = deleteCounter+1;
                    // deletelist
                    var deleteItem = '<input type="hidden" name="deleteList[id_'+deleteCount+']" value="'+leaveStructureTypeId+'">';
                    $("#structueTypeTableBody").append(deleteItem);
                    $('#delete_count').val(deleteCount);
                }
            }
        });

        // sorting order
        function sortingOrder(){
            // row checking
            if( $('#tr_count').val()>0){
                // rearrange table row
                var mycount = 1;
                $("tr.item").each(function() {
                    $this = $(this);
                    var rowId = $this.attr("id");
                    var typeId = rowId.replace("row_","");
                    // row counter
                    $("#count_"+typeId).text(mycount);
                    // row id
                    $("#row_id_"+typeId).removeAttr('name');
                    $("#row_id_"+typeId).attr('name', mycount+'[row_id]');
                    // type id
                    $("#name_"+typeId).removeAttr('name');
                    $("#name_"+typeId).attr('name', mycount+'[type_id]');
                    // days
                    $("#days_"+typeId).removeAttr('name');
                    $("#days_"+typeId).attr('name', mycount+'[days]');
                    // mycounter increment
                    mycount++;
                });
            }else{
                // hide containing table
                $('#structure_type_table').addClass('hide');
            }
        }

        // leave structure types incase of updating
        @if($leaveStructureTypes)
        // leaveStructureTypes
        var myTypes  = JSON.parse( '<?php echo json_encode($leaveStructureTypes); ?>' );
        // checking
        if(myTypes.length>0){
            // visible containing table
            $('#structure_type_table').removeClass('hide');
            // looping
            for(var i=0;i<myTypes.length;i++){
                // type details
                var type_id = myTypes[i].type_id;
                var type_name = myTypes[i].type_name;
                var leave_days = myTypes[i].leave_days;
                var row_id = myTypes[i].id;
                var row_count = i;
                // set table row
                var tr =  '<tr class="item" id="row_'+type_id+'"><td id="count_'+type_id+'">'+(row_count+1)+'</td><td>'+type_name+'<input type="hidden" id="name_'+type_id+'" name="'+(row_count+1)+'[type_id]" value="'+type_id+'"><input type="hidden" id="row_id_'+type_id+'" name="'+(row_count+1)+'[row_id]" value="'+row_id+'"></td><td><input class="form-control" id="days_'+type_id+'" type="text" name="'+(row_count+1)+'[days]" value="'+leave_days+'" required></td></tr>';

                // append table row
                $("#structueTypeTableBody").append(tr);
                // row counter increment
                $('#tr_count').val(row_count+1);

                // makeing selected the checkbox
                $("#"+type_id).prop( "checked", true );
                // makeing disabled the checkbox
                $("#"+type_id).attr('disabled', 'disabled');
            }
            // set row counter
        }
        @endif

    });
</script>

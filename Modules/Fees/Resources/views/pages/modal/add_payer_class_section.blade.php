<style type="text/css">
    .ui-autocomplete {z-index:2147483647;}
    .ui-autocomplete span.hl_results {background-color: #ffff66 ;}
</style>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title" id="gsmTitle">Add Payer Class Section</h4>
</div>
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('css/datatables/dataTable.min.css') }}" rel="stylesheet" type="text/css"/>
{{--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/s/dt/dt-1.10.10,se-1.1.0/datatables.min.css">--}}
<link rel="stylesheet" type="text/css" href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.6/css/dataTables.checkboxes.css">


<form id="class_section_payer_add_section" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="fees_id" value="{{$fees_id}}">
    <div class="box-body">
        <div class="row">
              <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label" for="academic_level">Academic Level</label>
                    <select id="academic_level" class="form-control academicLevel" name="academic_level">
                        <option value="">--- Select Academic Level ---</option>
                        @foreach($academicLevels as $level)
                            <option value="{{$level->id}}">{{$level->level_name}}</option>
                        @endforeach
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label" for="batch">Class</label>
                    <select id="batch" class="form-control academicBatch" name="batch">
                        <option value="" selected>--- Select ---</option>
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label" for="section">Section</label>
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
        <button type="submit" class="btn btn-info">Search</button>
        <button type="reset" class="btn btn-default">Reset</button>
    </div>

</form>

{{--<div id="class-section-student-search">--}}
{{--</div>--}}



<form id="studentListFrom" style="margin: 50px;    border: 1px solid #efefef;   padding: 15px; display: none">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="fees_id" value="{{$fees_id}}">
    <input type="hidden" id="studentId" name="studentId" value="">
    {{--<table id="studentList" class="display" cellspacing="0" width="100%">--}}
        {{--<thead>--}}
        {{--<tr>--}}
            {{--<th></th>--}}
            {{--<th>Name</th>--}}
        {{--</tr>--}}
        {{--</thead>--}}
        {{--<tfoot>--}}
        {{--<tr>--}}
            {{--<th></th>--}}
            {{--<th>Name</th>--}}
        {{--</tr>--}}
        {{--</tfoot>--}}
    {{--</table>--}}


    <table id="studentListDataTable" class="display table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th width="10%"></th>
            <th>Name</th>
        </tr>
        </thead>
        <tfoot>
        </tfoot>
    </table>


    <div class="row">

        @if(!empty($feesModule) && ($feesModule->count()>0))
        <div class="form-group">
            <label class="col-md-2 control-label" for="firstname">Send Auto Sms</label>
            <div class="col-md-2">
                <input type="hidden" name="auto_sms" class="auto_sms" value="0">
                <input type="checkbox" name="auto_sms" class="auto_sms" value="0">
            </div>
        </div>
        @endif
            <div class="col-md-2">
                <button class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>


</form>


{{--@php--}}
{{--$fees=$invoiceInfo->fees();--}}

{{--@endphp--}}


<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
{{--<script type="text/javascript" src="https://cdn.datatables.net/s/dt/dt-1.10.10,se-1.1.0/datatables.min.js" ></script>--}}
<script type="text/javascript" src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.6/js/dataTables.checkboxes.min.js" ></script>
{{--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" ></script>--}}

<script>


    $(document).ready(function() {
        $('.auto_sms').on('change', function(){
            this.value = this.checked ? 1 : 0;
        }).change();
    });

//    // DataTable
//    var table = $('#examplefff').DataTable();

    // request for payers fees payer id and fees id
    $('form#class_section_payer_add_section').on('submit', function (e) {
        e.preventDefault();

        var academic_year=$("#academic_year").val();
        var academic_level=$("#academic_level").val();
        var batch=$("#batch").val();
        var section=$("#section").val();


        //$('#examplefff').hide();
        // ajax request
        $.ajax({

            url: '/fees/feesmanage/add/payer/class/section',
            type: 'GET',
            cache: false,
            data: $('form#class_section_payer_add_section').serialize(),
            datatype: 'json/application',

            beforeSend: function() {

            },

            success:function(data){

                $("#studentListFrom").show();
//                alert(data)
//                alert(JSON.stringify(data));
                var table = $('#studentListDataTable').DataTable();
                table.destroy();


                var table = $('#studentListDataTable').DataTable({
                    'ajax': '/fees/feesmanage/add/payer/class/section?academic_year='+academic_year+'&academic_level='+academic_level+'&batch='+batch+'&section='+section+'',
                    'columnDefs': [{
                        'targets': 0,
                        'checkboxes': {
                            'selectRow': true
                        }
                    }],
                    'select': {
                        'style': 'multi'
                    },
                    'order': [
                        [1, 'asc']
                    ]
                });
                //$('#examplefff').show();


//                $("#class-section-student-search").html('');
//                $("#class-section-student-search").append(data);

//                if(data=="error") {
//                    $(".payer-error-message").show();
//                } else {
//                    $(".payer-error-message").hide();
//                    $("tbody.add-payer-section").prepend(data);
//                }
//                if(data == 'success'){
//                    alert("Payer Added Successfully");
//                }else if(data == 'error'){
//                    alert("Payer Already Exist");
//                }
            },

            error:function(data){
                alert(JSON.stringify(data));
            }
        });


    });


//    var table = $('#examplefff').DataTable();




    // Handle form submission event
    $('#studentListFrom').on('submit', function(e) {
        e.preventDefault();
        var form = this;
        var table = $('#studentListDataTable').DataTable();

        var rows_selected = table.column(0).checkboxes.selected();
        $('#studentId').val(rows_selected.join(","));
        // Remove added elements
        $('input[name="id\[\]"]', form).remove();


        $.ajax({
            url: '/fees/feesmanage/add/payer/class/section/store',
            type: 'POST',
            cache: false,
            data: $(form).serialize(),
            beforeSend: function() {
                    // clear std list container
                },

                success:function(data){
                    $('#globalModal').modal('hide');
//                    window.location.reload();
                },

                error:function(){
                    alert(JSON.stringify(data));
                }
            });

        // Prevent actual form submission
        e.preventDefault();


    });





    //    $(document).ready(function(){
//        var table = $('#studentList').DataTable({
//            'data':'https://api.myjson.com/bins/1us28',
//            'columnDefs': [{
//                'targets': 0,
//                'checkboxes': {
//                    'selectRow': true
//                }
//            }],
//            'select': {
//                'style': 'multi'
//            },
//            'order': [
//                [1, 'asc']
//            ]
//        });
//    });






    // request for batch list using level id
    jQuery(document).on('change','.academicYear',function(){
        // console.log("hmm its change");

        // get academic year id
        var year_id = $(this).val();
        var div = $(this).parent();
        var op="";

        $.ajax({
            url: "{{ url('/academics/find/level') }}",
            type: 'GET',
            cache: false,
            data: {'id': year_id }, //see the $_token
            datatype: 'application/json',

            beforeSend: function() {
                // clear std list container
                $('#std_list_container_row').html('');
            },

            success:function(data){
                console.log('success');

                //console.log(data.length);
                op+='<option value="0" selected>--- Select Level ---</option>';
                for(var i=0;i<data.length;i++){
                    // console.log(data[i].level_name);
                    op+='<option value="'+data[i].id+'">'+data[i].level_name+'</option>';
                }

                // set value to the academic secton
                $('.academicSection').html("");
                $('.academicSection').append('<option value="" selected>--- Select Section ---</option>');

                // set value to the academic batch
                $('.academicBatch').html("");
                $('.academicBatch').append('<option value="" selected>--- Select Class ---</option>');

                // set value to the academic batch
                $('.academicLevel').html("");
                $('.academicLevel').append(op);
            },

            error:function(){
                alert(JSON.stringify(data));
            }
        });
    });

    // request for batch list using level id
    jQuery(document).on('change','.academicLevel',function(){
        // console.log("hmm its change");

        // get academic level id
        var level_id = $(this).val();
        var div = $(this).parent();
        var op="";

        $.ajax({
            url: "{{ url('/academics/find/batch') }}",
            type: 'GET',
            cache: false,
            data: {'id': level_id }, //see the $_token
            datatype: 'application/json',

            beforeSend: function() {
                // clear std list container
                $('#std_list_container_row').html('');
            },

            success:function(data){
                console.log('success');

                //console.log(data.length);
                op+='<option value="" selected>--- Select Class ---</option>';
                for(var i=0;i<data.length;i++){
                    op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                }

                // set value to the academic batch
                $('.academicBatch').html("");
                $('.academicBatch').append(op);

                // set value to the academic secton
                $('.academicSection').html("");
                $('.academicSection').append('<option value="0" selected>--- Select Section ---</option>');
            },

            error:function(){
                alert(JSON.stringify(data));
            }
        });
    });

    // request for section list using batch id
    jQuery(document).on('change','.academicBatch',function(){
        console.log("hmm its change");

        // get academic level id
        var batch_id = $(this).val();
        var div = $(this).parent();
        var op="";

        $.ajax({
            url: "{{ url('/academics/find/section') }}",
            type: 'GET',
            cache: false,
            data: {'id': batch_id }, //see the $_token
            datatype: 'application/json',

            beforeSend: function() {
                // clear std list container
                $('#std_list_container_row').html('');
            },

            success:function(data){
                console.log('success');

                //console.log(data.length);
                op+='<option value="" selected>--- Select Section ---</option>';
                for(var i=0;i<data.length;i++){
                    op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                }

                // set value to the academic batch
                $('.academicSection').html("");
                $('.academicSection').append(op);
            },

            error:function(){
                alert(JSON.stringify(data));
            },
        });
    });

</script>


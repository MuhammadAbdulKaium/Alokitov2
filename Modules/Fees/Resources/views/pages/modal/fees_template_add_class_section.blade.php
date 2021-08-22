<style type="text/css">
    .ui-autocomplete {z-index:2147483647;}
    .ui-autocomplete span.hl_results {background-color: #ffff66 ;}
</style>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title" id="gsmTitle">Add Class Section</h4>
</div>
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('css/datatables/dataTable.min.css') }}" rel="stylesheet" type="text/css"/>
{{--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/s/dt/dt-1.10.10,se-1.1.0/datatables.min.css">--}}
<link rel="stylesheet" type="text/css" href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.6/css/dataTables.checkboxes.css">



<div class="alert alert-warning payer-error-message" style="margin: 30px; display: none">
    <p>Class Section Already Exits</p>
</div>

<div class="alert alert-success payer-success-message" style="margin: 30px; display: none">
    <p>Class Section Added Successfully</p>
</div>





<form id="class_section_payer_add_section" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="fees_id" value="{{$feesTemplateId}}">
    <div class="box-body">
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label" for="academic_year">Academic Year</label>
                    <select id="academic_year" class="form-control academicYear" name="academic_year" required>
                        <option value="">--- Select Academic Year ---</option>
                        @foreach($academicYears as $year)
                            <option value="{{$year->id}}">{{$year->year_name}}</option>
                        @endforeach
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label" for="academic_level">Academic Level</label>
                    <select id="academic_level" class="form-control academicLevel" name="academic_level">
                        <option value="" selected>--- Select Level ---</option>
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
        <button type="submit" class="btn btn-info">Submit</button>
        <button type="reset" class="btn btn-default">Reset</button>
    </div>

</form>



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




    // request for payers fees payer id and fees id
    $('form#class_section_payer_add_section').on('submit', function (e) {
        e.preventDefault();
        // ajax request
        $.ajax({

            url: '/fees/feestemplate/add/class/section',
            type: 'POST',
            cache: false,
            data: $('form#class_section_payer_add_section').serialize(),
            datatype: 'json/application',

            beforeSend: function() {
                {{--alert($('form#PayerStudent').serialize());--}}
            },

            success:function(data){
//                alert(JSON.stringify(data));
                if(data=="error") {
                    $(".payer-success-message").hide();
                    $(".payer-error-message").show();
                } else {
                    $(".payer-error-message").hide();
                    $(".payer-success-message").show();
                    $("tbody.add-class-section").prepend(data);
                }
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


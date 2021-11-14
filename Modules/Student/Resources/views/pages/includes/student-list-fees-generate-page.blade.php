
{{-- @php  print_r($allEnrollments) @endphp --}}
<div class="col-md-12">
    <div class="box box-solid">
        <div class="et">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i> View Student List</h3>
            </div>
        </div>
        <div class="card">
            <form method="post" action="/cadetfees/generate/cadet/fees/">
                @csrf
                @if(isset($searchData))
                    @if($searchData->count()>0)
                        @php $i=1; @endphp
                        <table class="table">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Student Number</th>
                                <th>Name</th>
                                <th>Roll</th>
                                <th>Structure</th>
                                <th>Month</th>
                                <th>Fees</th>
                                <th>Delay Fine</th>
                                <th>Fine Type</th>
                                <th>Last Payment Date</th>
                            </tr>

                            </thead>

                            <tbody>
                            @foreach($searchData as $key=>$data)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td><a href="/student/profile/personal/{{$data->std_id}}" target="_blank">{{$data->email}}</a></td>
                                    <td><a href="/student/profile/personal/{{$data->std_id}}" target="_blank">{{$data->first_name}} {{$data->last_name}}</a></td>
                                    <td>{{$data->gr_no}}</td>
                                    <td>{{$data->structure_name}}</td>
                                    <td>
                                        @foreach($month_list as $key=>$value)
                                            @if($month_name == $key)
                                                {{$value}}
                                            @endif
                                        @endforeach
                                    </td>
                                        <input type="hidden" name="structure_id[]" value="{{$data->structure_id}}">
                                        <input type="hidden" name="assign_id[]" value="{{$data->id}}">
                                        <input type="hidden" name="academic_year[]" value="{{$data->academic_year}}">
                                        <input type="hidden" id="cad_{{$data->std_id}}" name="std_id[]" value="{{$data->std_id}}">
                                        <input type="hidden"  name="batch[]" value="{{$data->batch}}">
                                        <input type="hidden"  name="section[]" value="{{$data->section}}">
                                        <input type="hidden" id="academic_level_{{$data->academic_level}}" name="academic_level[]" value="{{$data->academic_level}}">
                                        <input type="hidden" id="amount_{{$data->std_id}}" name="amount[]" value="{{$data->total_fees}}" class="form-control">
                                    <td> {{$data->total_fees}}</td>
                                    <td>
                                        <input type="number" id="fine_{{$data->std_id}}" name="fine[]" value="{{$fine}}" class="form-control">
                                    </td>
                                    <td>
                                        <select name="fine_type[]" class="form-control" readonly>
                                            <option value="1" {{ 1 == $fine_type ? 'selected' : '' }}>Per day</option>
                                            <option value="2" {{ 2 == $fine_type ? 'selected' : '' }}>Fixed</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="hidden" value="{{$month_name}}" class="form-control" name="month_name[]" readonly>
                                        <input type="date" value="{{$payment_last_date}}" class="form-control" name="payment_last_date[]" readonly>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @php $i++; @endphp
                    @else
                        <h5 class="text-center"> <b>Sorry!!! No Result Found</b></h5>
                    @endif
                @endif
                <button type="submit" class="btn btn-primary" id="assignData">Generate</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(function () {
        $("#selectAll").click(function () {
            $("input[type=checkbox]").prop("checked", $(this).prop("checked"));

        });

        $("input[type=checkbox]").click(function () {
            if (!$(this).prop("checked")) {
                $("#selectAll").prop("checked", false);
            }
        });

        $('form#cad_assign_submit_form').on('submit', function (e) {
            e.preventDefault();
            // ajax request
            $.ajax({
                url: "/cadetfees/assign/cadet/fees/generate/",
                type: 'POST',
                cache: false,
                data: $('form#cad_assign_submit_form').serialize(),
                datatype: 'application/json',


                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },

                success: function (data) {
                    waitingDialog.hide();
                    console.log(data);
                },

                error: function (data) {
                    alert(JSON.stringify(data));
                }
            });
        });
    })
    // var selectedData = [];
    // function selectCadet(id)
    // {
    // 	if($('#selectCad_'+id).is(":checked"))
    // 	{
    // 		var cad = $('#cad_'+id).val();
    // 		var amount = $('#amount_'+id).val();
    // 		var fine = $('#fine_'+id).val();
    // 		var fine_type = $('#fine_type_'+id).val();
    // 		var jsonData = JSON.parse('{"cad":"'+cad+'", "amount" : "'+amount+'" ,"fine" : "'+fine+'","fine_type" : "'+fine_type+'"}');
    // 		selectedData.push(jsonData);
    // 	}
    // 	else
    // 	{
    // 		var index = 0;
    // 		for (index = selectedData.length - 1; index >= 0; --index) {
    // 			console.log(selectedData[index]);
    // 			if (selectedData[index].cad == id) {
    // 				selectedData.splice(index, 1);
    // 			}
    // 		}
    // 	}
    // }
    {{--$(function (){--}}
    {{--	$("#assignData").click(function (e) {--}}
    {{--		e.preventDefault();--}}

    {{--		// ajax request--}}
    {{--		$.ajax({--}}
    {{--			url: "/cadetfees/assign/cadet/fees/",--}}
    {{--			type: 'POST',--}}
    {{--			cache: false,--}}
    {{--			data: {"_token": "{{ csrf_token() }}",--}}
    {{--				"selectedData":selectedData},--}}
    {{--			datatype: 'application/json',--}}


    {{--			beforeSend: function() {--}}
    {{--				// show waiting dialog--}}
    {{--				waitingDialog.show('Loading...');--}}
    {{--			},--}}

    {{--			success:function(data){--}}

    {{--				console.log(data);--}}
    {{--				waitingDialog.hide();--}}
    {{--			},--}}

    {{--			error:function(data){--}}
    {{--				alert(JSON.stringify(data));--}}
    {{--			}--}}
    {{--		});--}}
    {{--	});--}}
    {{--});--}}

    // for single checkbox end
    // for select all checkbox




    $(function () {
        $("#example2").DataTable();
        $('#example1').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": false
        });

        // paginating
        $('.pagination a').on('click', function (e) {
            e.preventDefault();
            var url = $(this).attr('href').replace('store', 'find');
            loadRolePermissionList(url);
            // window.history.pushState("", "", url);
            // $(this).removeAttr('href');
        });
        // loadRole-PermissionList
        function loadRolePermissionList(url) {
            $.ajax({
                url: url,
                type: 'POST',
                cache: false,
                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },
                success:function(data){
                    // hide waiting dialog
                    waitingDialog.hide();
                    // checking
                    if(data.status=='success'){
                        var std_list_container_row = $('#std_list_container_row');
                        std_list_container_row.html('');
                        std_list_container_row.append(data.html);
                    }else{
                        alert(data.msg)
                    }
                },
                error:function(data){
                    alert(JSON.stringify(data));
                }
            });
        }


        // downlaod student report

//		$('.download').click(function () {
//		    var download_type=$(this).attr("id");
////			alert(download_type);
//
//            $.ajax({
//
//                url: '/student/manage/download/excel/pdf',
//                type: 'POST',
//                cache: false,
//                data: $('form#downlaodStdExcelPDF').serialize()+ "&download_type=" + download_type,
//                datatype: 'json/application',
//
//                beforeSend: function () {
//                    // alert($('form#class_section_form').serialize());
//                    // show waiting dialog
////                    waitingDialog.show('Loading...');
//                },
//
//                success: function (data) {
//                    // hide waiting dialog
////                    waitingDialog.hide();
//					console.log(data);
//
//                },
//
//                error: function (data) {
//                    alert('error');
//                }
//            });
//
//
//        })




    });
</script>
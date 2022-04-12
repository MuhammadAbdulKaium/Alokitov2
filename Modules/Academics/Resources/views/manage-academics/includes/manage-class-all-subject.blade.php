
<div class="row">
    <form id="class_subject_form" action="{{url('academics/manage/subject/store')}}" method="post">
        <div class="col-md-3">
            <div class="box box-solid">
                <div class="et">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> All Subject List</h3>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <div class="box-body">
                        <table id="example1" class="table table-striped table-bordered table-responsive" style="font-size: 10px;">
                            <thead>
                            <tr>
                                <th>Subject Name</th>
                                <th>Subject Name</th>
                            </tr>
                            </thead>
                            <tbody>
                            @for($x = 0; $x < count($allSubjects); $x=$x+2)
                                <tr>
                                    @php $count_td =0; @endphp
                                    @if($x< count($allSubjects))
                                        <td>
                                            @php $count_td++; @endphp
                                            <div class="checkbox form-group">
                                                <label>
                                                    <input type="checkbox" id='{{$allSubjects[($x).""]["id"]}}' onclick="addSubject(this.id)">
                                                    <input type="hidden" id='{{$allSubjects[($x).""]["id"]}}_s' value='{{$allSubjects[($x).""]["subject_name"]}}'>
                                                    <input type="hidden" id='{{$allSubjects[($x).""]["id"]}}_c' value='{{$allSubjects[($x).""]["subject_code"]}}'>
                                                    <input type="hidden" id='{{$allSubjects[($x).""]["id"]}}_sg' value='@if($allSubjects[($x).""]["check_sub_group_assign_single"]){{$allSubjects[($x).""]["check_sub_group_assign_single"]["subject_group_single"]["id"]}}@endif'>
                                                    <input type="hidden" >
                                                    {{$allSubjects[($x).""]["subject_name"]}}
                                                </label>
                                            </div>
                                        </td>
                                    @endif

                                    @if($x +1< count($allSubjects))
                                        @php $count_td++; @endphp
                                        <td>
                                            <div class="checkbox form-group">
                                                <label>
                                                    <input type="checkbox" id='{{$allSubjects[($x+1).""]["id"]}}' onclick="addSubject(this.id)">
                                                    <input type="hidden" id='{{$allSubjects[($x+1).""]["id"]}}_s' value='{{$allSubjects[($x+1).""]["subject_name"]}}'>
                                                    <input type="hidden" id='{{$allSubjects[($x+1).""]["id"]}}_c' value='{{$allSubjects[($x+1).""]["subject_code"]}}'>
                                                    <input type="hidden" id='{{$allSubjects[($x+1).""]["id"]}}_sg' value='@if($allSubjects[($x+1).""]["check_sub_group_assign_single"]){{$allSubjects[($x+1).""]["check_sub_group_assign_single"]["subject_group_single"]["id"]}}@endif'>
                                                    {{$allSubjects[($x+1).""]["subject_name"]}}
                                                </label>
                                            </div>
                                        </td>
                                    @endif
                                    @if($count_td == 1)
                                        <td></td>
                                        @php $count_td++; @endphp
                                    @endif
                                </tr>
                            @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- subject assing -->
        <div class="col-md-9">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="box box-solid">
                <div class="et">
                    <div class="box-header with-border">
                        <h3 class="box-title">All Selected Subjects</h3>
                    </div>
                </div>
                <input id="tr_count" type="hidden" name="rows_no" value="0">
                <input id="delete_count" type="hidden" name="delete_no" value="0">

                <div class="box-body table-responsive">
                    <table class="table table-striped table-bordered sorted_table text-center">
                        <thead>
                        <tr>
                            <th class="col-md-3">
                                {{$batchString}}:
                                <select class="form-control" id="batch_id" name="batch_id" required>
                                    <option value="">--Choose Batch--</option>
                                    @php
                                        $selectedBatch = null;
                                    @endphp
                                    @foreach ($batches as $batch)
                                        @php
                                            $selected = "";
                                            if(Session::get('batchId')){
                                                if (Session::get('batchId') == $batch->id) {
                                                    $selected = "selected";
                                                    $selectedBatch = $batch;
                                                }
                                            }
                                        @endphp
                                        <option value="{{$batch->id}}" {{ $selected }}>{{$batch->batch_name}}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th class="col-md-3">
                                Section:
                                <select class="form-control section_id" id="section_id" name="section_id" required>
                                    <option value="" disabled selected>-- Select Section --</option>
                                    @if (Session::get('batchId'))
                                        @foreach ($selectedBatch->section() as $section)
                                            @php
                                                $selected = "";
                                                if(Session::get('sectionId')){
                                                    if (Session::get('sectionId') == $section->id) {
                                                        $selected = "selected";
                                                    }
                                                }
                                            @endphp
                                            <option value="{{ $section->id }}" {{ $selected }}>{{ $section->section_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </th>

                            <th class="col-md-3">
                                Copy From:
                                <select class="form-control copy_from" id="copy_from" name="copy_from" >
                                    <option value="" selected disabled>-- Select Class Section -- </option>
                                    @foreach(json_decode($allBatchSectionDivision) as $batchSection)
                                        <option value="{{$batchSection->id}}">{{$batchSection->name}}</option>
                                    @endforeach
                                </select>
                                @foreach(json_decode($allBatchSectionDivision) as $batchSection)
                                    <input type="hidden" id="{{$batchSection->id}}_batch" value = "{{$batchSection->batch_id}}" />
                                    <input type="hidden" id="{{$batchSection->id}}_section" value = "{{$batchSection->section_id}}" />
                                @endforeach
                            </th>
                        </tr>
                        </thead>
                    </table>
                    <table id="subject_table" style="font-size: 12px;" class="table table-striped table-bordered sorted_table text-center" role="grid" aria-describedby="example2_info">
                        <thead>
                        <tr id="tableHead" class="hide">
                            <th width="1%"> <input id="count_all_class_subject" type="checkbox"></th>
                            <th width="9%" class="text-left">Subject Name</th>
                            <th width="6%">Code</th>
                            <th width="9%">Type</th>
                            <th width="9%">Group</th>
                            <th width="7%">List</th>
                            <th width="15%">Assigned Teachers</th>
                        </tr>
                        </thead>
                        <tbody id="tableBody" class="hide"></tbody>
                        {{-- @if (in_array("academics/manage/subject/store" ,$pageAccessData)) --}}
                        <tfoot id="tableFoot" class="hide">
                            <tr>
                                <td colspan="7">
                                    <button type="button" class="btn btn-info pull-right subject_form_submit_btn">Submit</button>
                                </td>
                            </tr>
                        </tfoot>
                        {{-- @endif --}}
                    </table>
                </div>
            </div>
        </div>
    </form>
</div>
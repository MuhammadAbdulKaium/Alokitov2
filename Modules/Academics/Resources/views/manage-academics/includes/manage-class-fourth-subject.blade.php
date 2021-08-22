<div class="row">
    <div class="col-md-12">
        <form id="class_section_fourth_subject_assign_form" method="POST">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="box-body">
                @php $batchString = 'Class'; @endphp
                <div class="row text-center">
                    <div class="col-sm-10 col-md-offset-1">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="academic_level">Academic Level</label>
                                <select id="academic_level" class="form-control academicLevel" name="academic_level">
                                    <option value="" selected disabled>--- Select Level ---</option>
                                    @foreach($allAcademicsLevel as $level)
                                    <option value="{{$level->id}}">{{$level->level_name}}</option>
                                    @endforeach
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="batch">{{$batchString}}</label>
                                <select id="batch" class="form-control academicBatch" name="batch" onchange="">
                                    <option value="" selected disabled>--- Select {{$batchString}} ---</option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="class_section_id">Section</label>
                                <select id="class_section_id" class="form-control academicSection" name="section">
                                    <option value="" selected disabled>--- Select Section ---</option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label">Action</label><br>
                                <button id="class_section_fourth_subject_list_finder" type="button" class="btn btn-success">Find</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{--class subject fourth subject container--}}
<div id="fourth_subject_list_container" class="row">
    {{--class subject fourth subject will be here--}}
</div>
{{--<form>--}}
   {{--<div class="modal-header">--}}
      {{--<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>--}}
      {{--<h4 class="modal-title">--}}
         {{--<i class="fa fa-info-circle"></i> @if($assessmentProfile)Update @else Add @endif Assessment--}}
      {{--</h4>--}}
   {{--</div>--}}
   {{--<!--modal-header-->--}}
   {{--<div class="modal-body">--}}
      {{--<div class="row">--}}
         {{--<div class="col-sm-4">--}}
            {{--<div class="form-group">--}}
               {{--<label class="control-label" for="">Assessment Name</label>--}}
               {{--<input name="name" maxlength="35" value="@if($assessmentProfile){{$assessmentProfile->name}}@endif" class="form-control" type="text" placeholder="Assessment Name">--}}
               {{--<div class="help-block"></div>--}}
            {{--</div>--}}
         {{--</div>--}}
         {{--<div class="col-sm-4">--}}
            {{--<div class="form-group">--}}
               {{--<label class="control-label" for="">Grading Category</label>--}}
               {{--<select name="grading_category" class="form-control">--}}
                  {{--<option value=""> Grading Category</option>--}}
                  {{--@if($allGradeCategory)--}}
                     {{--@foreach($allGradeCategory as $gradeCategory)--}}
                        {{--<option value="{{$gradeCategory->id}}" @if($assessmentProfile) @if($gradeCategory->id == $assessmentProfile->grading_category_id) selected @endif @endif>{{$gradeCategory->name}}</option>--}}
                     {{--@endforeach--}}
                  {{--@endif--}}
               {{--</select>--}}
               {{--<div class="help-block"></div>--}}
            {{--</div>--}}
         {{--</div>--}}
         {{--<div class="col-sm-4">--}}
            {{--<div class="form-group">--}}
               {{--<label class="control-label" for="">Grading Scale</label>--}}
               {{--<select name="grade" class="form-control">--}}
                  {{--<option value=""> Select Grading Scale </option>--}}
                  {{--@if($allGradeScale)--}}
                     {{--@foreach($allGradeScale as $gradeScale)--}}
                        {{--<option disabled style="font-size: 18px; background: gray; color: white;">{{$gradeScale->name}}</option>--}}
                        {{--@if($gradeScale->allGarde())--}}
                           {{--@foreach($gradeScale->allGarde() as $grade)--}}
                              {{--<option value="{{$grade->id}}" @if($assessmentProfile)  @if($grade->id == $assessmentProfile->grade_id) selected @endif @endif>{{$grade->name}}</option>--}}
                           {{--@endforeach--}}
                        {{--@endif--}}
                     {{--@endforeach--}}
                  {{--@endif--}}
               {{--</select>--}}
               {{--<div class="help-block"></div>--}}
            {{--</div>--}}
         {{--</div>--}}
      {{--</div>--}}
      {{--<div class="row">--}}
         {{--<div class="col-sm-4">--}}
         {{--<div class="form-group">--}}
         {{--<label class="control-label" for="">Points</label>--}}
         {{--<input name="points" maxlength="3" value="@if($assessmentProfile) @if($assessmentProfile){{$assessmentProfile->points}} @endif @endif" class="form-control" type="text" placeholder="Assessment Points">--}}
         {{--<div class="help-block"></div>--}}
         {{--</div>--}}
         {{--</div>--}}
         {{--<div class="col-sm-4">--}}
         {{--<div class="form-group">--}}
         {{--<label class="control-label" for="">Passing Points</label>--}}
         {{--<input name="passing_points" maxlength="3" value="@if($assessmentProfile) @if($assessmentProfile){{$assessmentProfile->passing_points}} @endif @endif" class="form-control" type="text" placeholder="Assessment Passing Points">--}}
         {{--<div class="help-block"></div>--}}
         {{--</div>--}}
         {{--</div>--}}
         {{--<div class="col-sm-4">--}}
            {{--<div class="form-group">--}}
               {{--<label class="control-label" for="">Status</label>--}}
               {{--<select name="status" class="form-control">--}}
                  {{--<option value=""> Status </option>--}}
                  {{--<option value="1"@if($assessmentProfile) @if($assessmentProfile->status == 1) selected @endif @endif > Published </option>--}}
                  {{--<option value="0"@if($assessmentProfile) @if($assessmentProfile->status == 0) selected @endif @endif> Unpublished </option>--}}
               {{--</select>--}}
               {{--<div class="help-block"></div>--}}
            {{--</div>--}}
         {{--</div>--}}
      {{--</div>--}}

      {{--<div class="row">--}}
         {{--<div class="col-sm-6">--}}
         {{--<div class="form-group">--}}
         {{--<label class="control-label" for="">Applied To</label> <br/>--}}
         {{--<input type="radio" name="applied_to" value="all"@if($assessmentProfile) @if($assessmentProfile->applied_to == 'all') checked="checked" @endif @endif>All--}}
         {{--<input type="radio" name="applied_to" value="class"@if($assessmentProfile) @if($assessmentProfile->applied_to == 'class') checked="checked" @endif @endif>All Subjects--}}
         {{--<input type="radio" name="applied_to" value="subject"@if($assessmentProfile) @if($assessmentProfile->applied_to == 'subject') checked="checked" @endif @endif>Particular Subjects--}}
         {{--<div class="help-block"></div>--}}
         {{--</div>--}}
         {{--</div>--}}
         {{--<div class="col-sm-6">--}}
         {{--<div class="form-group">--}}
         {{--<label class="control-label" for="counts_overall_score">Counts towords Overall Score</label>  <br/>--}}
         {{--<input type="radio" value="1" name="counts_overall_score" @if($assessmentProfile) @if($assessmentProfile->counts_overall_score == 1) checked="checked" @endif @endif>Yes--}}
         {{--<input type="radio" value="0" name="counts_overall_score" @if($assessmentProfile) @if($assessmentProfile->counts_overall_score == 0) checked="checked" @endif @endif>No--}}
         {{--<div class="help-block"></div>--}}
         {{--</div>--}}
         {{--</div>--}}
      {{--</div>--}}
   {{--</div>--}}
   {{--<div class="modal-footer">--}}
      {{--<button type="submit" class="btn btn-info pull-left">Submit</button>--}}
      {{--<a class="btn btn-default pull-right" href="#" data-dismiss="modal">Cancel</a>--}}
   {{--</div>--}}
{{--</form>--}}


{{--<!--  -->--}}
{{--<script type ="text/javascript">--}}
    {{--$(document).ready(function(){--}}
        {{--$('form').on('submit', function (e) {--}}
            {{--e.preventDefault();--}}

            {{--var td;--}}
                   {{--@if($assessmentProfile)--}}
            {{--var routeLink = '/academics/manage/assessments/assessment/update/{{$assessmentProfile->id}}';--}}
                   {{--@else--}}
            {{--var routeLink = '/academics/manage/assessments/assessment/store';--}}
            {{--@endif--}}


            {{--$.ajax({--}}
                {{--type: 'post',--}}
                {{--url: routeLink,--}}
                {{--data: $('form').serialize(),--}}
                {{--datatype: 'application/json',--}}

                {{--beforeSend: function() {--}}
                    {{--// statement--}}
                {{--},--}}

                {{--success: function (data) {--}}
                    {{--if(data.length>0){--}}
                        {{--$("#assessmentPagetabeBody").html('');--}}
                        {{--// looping--}}
                        {{--for(var i=0;i<data.length;i++){--}}
                            {{--// table rows--}}
                            {{--td +='<tr><td class="text-center">'+(i+1)+'</td><td>'+data[i].name+'</td><td>'+data[i].category+'</td><td>'+data[i].scale+'</td><td>'+data[i].applied+'</td><td>'+data[i].status+'</td><td class="text-center"><a href="/academics/manage/assessments/assessment/edit/'+data[i].id+'" title="Edit" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><span class=" fa fa-pencil-square-o"></span></a><a href="manage/assessments/assessment/destroy/'+data[i].id+'" title="Delete" data-confirm="Are you sure you want to delete this item?" data-method="get"><span class="glyphicon glyphicon-trash"></span></a></td></tr>';--}}
                        {{--}--}}
                        {{--// append tabe data--}}
                        {{--$("#assessmentPagetabeBody").append(td);--}}
                        {{--// hide modal--}}
                        {{--$('#globalModal').modal('hide');--}}
                    {{--}--}}
                {{--},--}}

                {{--error:function(){--}}
                    {{--// statements--}}
                {{--}--}}
            {{--});--}}

        {{--});--}}
    {{--});--}}
{{--</script>--}}

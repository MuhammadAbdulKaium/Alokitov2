<form>
<input type="hidden" name="_token" value="{{csrf_token()}}">
   <div class="modal-header">
      <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
      <h4 class="modal-title">
         <i class="fa fa-info-circle"></i> Update Grading Scale
      </h4>
   </div>
   <!--modal-header-->
   <div class="modal-body">
      <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <table class="table text-center table-responsive ta-striped">
                <thead>
                   <tr>
                      <td colspan="7">
                        <input id="addScaleId" name="scale_name" value="{{$gradeProfile->grade_scale_id}}" type="hidden">
                        <input id="addScaleType" name="scale_type" value="1" type="hidden">
                        <input class="form-control" id="gradeName" name="grade_name" value="{{$gradeProfile->name}}" type="text" placeholder="Grade Scale Name" required>
                        <input name="gradeCounter" id="gradeCounter" value="{{$gradeProfile->allDetails()->count()}}" type="hidden">
                        <input name="gradeDeleteCounter" id="gradeDeleteCounter" value="0" type="hidden">
                      </td>
                   </tr>
                   <tr>
                      <th width="20%" class="text-center" style="font-size:12px;"><b>Min (%)</b></th>
                      <th style="width:30px;">&nbsp;</th>
                      <th width="20%" class="text-center" style="font-size:12px;"><b>Max (%)</b></th>
                      <th style="width:30px;">&nbsp;</th>
                      <th style="width:90px; font-size:12px;">Grade</th>
                      <th style="width:90px; font-size:12px;">Points</th>
                      <th>&nbsp;</th>
                   </tr>
                </thead>
                <tbody id="gradeScaleBody" class="text-center">
                  @php $i=1; @endphp
                  @foreach($gradeProfile->allDetails() as $gradeDetails)
                   <tr class="item" id="row_{{$i}}">
                      <td class="min">
                         <input id="row_{{$i}}_min" name="{{$i}}[min]" value="{{$gradeDetails->min_per}}" maxlength="3" class="form-control" type="text" required>
                      </td>
                      <td>
                         <div>to</div>
                      </td>
                      <td class="max">
                         <input id="row_{{$i}}_max" name="{{$i}}[max]" value="{{$gradeDetails->max_per}}" maxlength="3" class="form-control" type="text" required>
                      </td>
                      <td>
                         <div>=</div>
                      </td>
                      <td class="grade">
                         <input id="row_{{$i}}_grade" name="{{$i}}[grade]" value="{{$gradeDetails->name}}" maxlength="20" class="form-control" type="text" required>
                      </td>
                      <td class="points">
                         <input id="row_{{$i}}_points" name="{{$i}}[points]" value="{{$gradeDetails->points}}" maxlength="3" class="form-control" type="text" required>
                      </td>
                      <td class="remove">
                        <input id="gr_row_{{$i}}" name="{{$i}}[id]" type="hidden" value="{{$gradeDetails->id}}">
                        @if($i>2)
                          <a id="row_{{$i}}_remove"><i class="fa fa-times"></i></a>
                        @endif
                      </td>
                   </tr>
                    @php $i++; @endphp
                   @endforeach
                </tbody>
                <tfoot id="footer">
                   <tr>
                    <td colspan="7" style="text-align:right;padding:8px 0;"><a id="addGradingScaleRow"><i class="fa fa-plus"></i> Add</a></td>
                   </tr>
                </tfoot>
             </table>
        </div>
      </div>
   </div>
   <div class="modal-footer">
      <button type="submit" class="btn btn-success pull-left">Submit</button>
      <a class="btn btn-default pull-right" href="#" data-dismiss="modal">Cancel</a>
   </div>
</form>

<script type ="text/javascript">
    $('#addGradingScaleRow').click(function(){
     // scale counter
      var gradeScaleCounter = parseInt($('#gradeCounter').val());
      // increase counter
      gradeScaleCounter++;
      // table row
      var tr = '<tr class="item" id="row_'+gradeScaleCounter+'"><td class="min"><input id="row_'+gradeScaleCounter+'_min" name="'+gradeScaleCounter+'[min]" maxlength="3" class="form-control" type="text"></td><td><div>to</div></td><td class="max"><input id="row_'+gradeScaleCounter+'_max" name="'+gradeScaleCounter+'[max]" maxlength="3" class="form-control" type="text"></td><td><div>=</div></td><td class="grade"><input id="row_'+gradeScaleCounter+'_grade" name="'+gradeScaleCounter+'[grade]" maxlength="20" class="form-control" type="text"></td><td class="points"><input id="row_'+gradeScaleCounter+'_points" name="'+gradeScaleCounter+'[points]" maxlength="3" class="form-control" type="text"></td><td class="remove"><input type="hidden" id="gr_row_'+gradeScaleCounter+'" name="'+gradeScaleCounter+'[id]" value="0"><a onclick="removeGradingScaleRow(this.id)" id="row_'+gradeScaleCounter+'_remove"><i class="fa fa-times"></i></a></td></tr>';
      // append row into the table
      $('#gradeScaleBody').append(tr);
      // update scale counter
      $('#gradeCounter').val(gradeScaleCounter);
    });


    $('tr.item td:last-child a').click(function(){

      var scaleRowId = $(this).attr('id').replace('_remove', '');
      // make delete list
      var gradeId = $("#gr_"+scaleRowId).val();
      // add the classSubject to the deletelist
      if(gradeId > 0){
        var deleteCounter = parseInt($('#gradeDeleteCounter').val());
        var deleteCount = deleteCounter+1;
        $('#gradeDeleteCounter').val(deleteCount);
        var deleteItem = '<input type="hidden" name="deleteList['+deleteCount+']" value="'+gradeId+'">';
        $("#gradeScaleBody").append(deleteItem);
      }
      // remove row
      $('#'+scaleRowId).remove();
      // scale counter
      var gradeScaleCounter = parseInt($('#gradeCounter').val());
      // increase counter
      gradeScaleCounter--;
      // update scale counter
      $('#gradeCounter').val(gradeScaleCounter);
      // reset row order
      sortingOrder();
    });

    // removing action
    function removeGradingScaleRow(id){
      var scaleRowId = id.replace('_remove', '');
      $('#'+scaleRowId).remove();
      // scale counter
      var gradeScaleCounter = parseInt($('#gradeCounter').val());
      // increase counter
      gradeScaleCounter--;
      // update scale counter
      $('#gradeCounter').val(gradeScaleCounter);
      // reset row order
      sortingOrder();
    }

    // sorting order
    function sortingOrder(){
      var mycount = 1;
      $("tr.item").each(function() {
        var rowId = $(this).attr('id');
        // rename id
        $(this).removeAttr('id');
        $(this).attr('id','row_'+mycount);
        //remane min id
        $('#row_'+mycount+' td.min input').removeAttr('id');
        $('#row_'+mycount+' td.min input').attr('id','row_'+mycount+'_min');
        //remane min name
        $('#row_'+mycount+' td.min input').removeAttr('name');
        $('#row_'+mycount+' td.min input').attr('name',mycount+'[min]');
        //remane max value name
        $('#row_'+mycount+' td.max input').removeAttr('id');
        $('#row_'+mycount+' td.max input').attr('id','row_'+mycount+'_max');
        //remane max name
        $('#row_'+mycount+' td.max input').removeAttr('name');
        $('#row_'+mycount+' td.max input').attr('name',mycount+'[max]');
        //remane grade value name
        $('#row_'+mycount+' td.grade input').removeAttr('id');
        $('#row_'+mycount+' td.grade input').attr('id','row_'+mycount+'_grade');
        //remane grade name
        $('#row_'+mycount+' td.grade input').removeAttr('name');
        $('#row_'+mycount+' td.grade input').attr('name',mycount+'[grade]');
        //remane grade value name
        $('#row_'+mycount+' td.remove a').removeAttr('id');
        $('#row_'+mycount+' td.remove a').attr('id','row_'+mycount+'_remove');

        //remane grade id
        $('#row_'+mycount+' td.remove input').removeAttr('id');
        $('#row_'+mycount+' td.remove input').attr('id','gr_row_'+mycount);
        //remane max name
        $('#row_'+mycount+' td.remove input').removeAttr('name');
        $('#row_'+mycount+' td.remove input').attr('name',mycount+'[id]');
        // increment
        mycount++;
      });
    }


      $('form').on('submit', function (e) {
          var td = '';
          e.preventDefault();

          $.ajax({
            type: 'post',
            url: '/academics/manage/assessments/grade/update/{{$gradeProfile->id}}',
            data: $('form').serialize(),
            datatype: 'application/json',

            beforeSend: function() {
              // statement
            },

            success: function (data) {
//                alert(JSON.stringify(data));

              if(data.length>0){
                $("#gradeBody").html('');
                // looping
                for(var i=0;i<data.length;i++){
                  // table rows
                  td +='<tr><td class="text-center">'+(i+1)+'</td><td>'+data[i].name+'</td><td class="text-center"><a href="/academics/manage/assessments/grade/edit/'+data[i].id+'" title="Edit" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><span class=" fa fa-pencil-square-o"></span></a><a href="/academics/manage/assessments/grade/delete/'+data[i].id+'" title="Delete" data-confirm="Are you sure you want to delete this item?" data-method="get"><span class="glyphicon glyphicon-trash"></span></a> </td></tr>';
                }
                // append tabe data
                $("#gradeBody").append(td);
                // hide modal
                $('#globalModal').modal('hide');
              }
            },

            error:function(){
              // statements
            }
          });

        });
</script>

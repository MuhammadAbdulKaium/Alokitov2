

    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h3 class="modal-title">
            <i class="fa fa-info-circle"></i> Personal Details
        </h3>
    </div>
    <!--modal-header-->
    @php
        $extraCol = 1;
    @endphp
   <div class="modal-body">
       <table class="table table-bordered">
           <thead>
                 <tr>
                     <td>
                         Subject Name

                     </td>
                     @if($criteriaUniqueIds)
                         @foreach($criteriaUniqueIds as $key=>$uniqueId)
                             <td>
                                 {{$criteriasAll[$uniqueId]->name}}
                             </td>


                         @endforeach

                     @endif
                     <td>
                         Total
                     </td>
                 </tr>

               @foreach ($subjects as $subject)
                   @php
                       if(isset($subjectMarks[$subject->id])){
                           $marks = json_decode($subjectMarks[$subject->id]->marks, 1);
                           $colspan = sizeof($marks['fullMarks'])+$extraCol;
                       }
                   @endphp
               <tr>
                   <th rowspan="1">{{ $subject->subject_name }}</th>
                   @isset($sheetData[$std_id][$subject->id])
                       @php
                           $marks = json_decode($subjectMarks[$subject->id]->marks, 1);
                       @endphp
                       @foreach ($marks['fullMarks'] as $key => $mark)
                           <td rowspan="1"><span style="color: {{ $sheetData[$std_id][$subject->id][$key]['color'] }}">{{ $sheetData[$std_id][$subject->id][$key]['mark'] }}</span></td>
                       @endforeach
                       <td rowspan="1" style="color: {{ ($sheetData[$std_id][$subject->id]['isFail'])?'red':'black' }}; font-weight: bold">{{ $sheetData[$std_id][$subject->id]['totalMark'] }}</td>
                   @endisset
               </tr>

               @endforeach

           <tr>

           </tr>
<!--           <tr>
               @foreach ($subjects as $subject)
                   @isset($subjectMarks[$subject->id])
                       @php
                           $marks = json_decode($subjectMarks[$subject->id]->marks, 1);
                           $conversionPoint = ($subjectMarks[$subject->id]->full_marks != 0)?$subjectMarks[$subject->id]->full_mark_conversion/$subjectMarks[$subject->id]->full_marks:0;
                       @endphp
                       @foreach ($marks['fullMarks'] as $key => $mark)
                           <th rowspan="1">
                               {{ substr($criterias[$key]->name, 0, 2) }} <br>
                               ({{ round($mark*$conversionPoint, 2) }})
                           </th>
                       @endforeach
                       <th rowspan="1">T</th>
                   @endisset
               @endforeach
           </tr>-->
           </thead>
           <tbody>


               <tr>
                   <td >Grand Total</td>
                   <td @if($criteriaUniqueIds) colspan="{{sizeof($criteriaUniqueIds)}}" @endif style="{{ ($sheetData[$std_id]['isFail'])?'color: red; font-weight: bold':'' }}">{{ $sheetData[$std_id]['grandTotalMark'] }}</td>

               </tr>
               <tr>
                   <td >Average</td>
                   <td @if($criteriaUniqueIds) colspan="{{sizeof($criteriaUniqueIds)}}" @endif style="{{ ($sheetData[$std_id]['isFail'])?'color: red; font-weight: bold':'' }}">{{ $sheetData[$std_id]['totalAvgMark'] }}</td>

               </tr>
               <tr>

                   <td >Parentage</td>
                   <td @if($criteriaUniqueIds) colspan="{{sizeof($criteriaUniqueIds)}}" @endif style="{{ ($sheetData[$std_id]['isFail'])?'color: red; font-weight: bold':'' }}">{{ $sheetData[$std_id]['totalAvgMarkPercentage'] }}</td>


               </tr>
               <tr>
                   <td >GPA</td>
                   <td   @if($criteriaUniqueIds) colspan="{{sizeof($criteriaUniqueIds)}}" @endif style="{{ ($sheetData[$std_id]['isFail'])?'color: red; font-weight: bold':'' }}">{{ $sheetData[$std_id]['gpa'] }}</td>

               </tr>



           </tbody>
       </table>
   </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-info">Update</button>
        <a class="btn btn-default pull-right" href="#" data-dismiss="modal">Cancel</a>
    </div>

<script type ="text/javascript">
    jQuery(document).ready(function () {
        jQuery('#dob').datepicker({format: 'yyyy-mm-dd'});




    });

</script>
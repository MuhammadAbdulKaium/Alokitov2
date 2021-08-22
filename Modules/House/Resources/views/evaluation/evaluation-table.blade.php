<table class="table table-bordered evaluation-table" style="text-align: center">
    <thead>
      <tr>
        <th rowspan="2">Subject</th>
        <th rowspan="2">SL</th>
        <th rowspan="2">Name of Events</th>
        <th rowspan="2">Block Point W</th>
        @foreach ($houses as $house)
            <th colspan="3">{{$house->name}}</th>
        @endforeach
      </tr>
      <tr>
        @foreach ($houses as $house)
            <th>Position</th>
            <th>Position WX</th>
            <th>Score WX</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
        {{-- Academics Part --}}
        @if ((sizeof($examWeightages) > 0 || sizeof($exams) > 0) && ($type == 1 || $type == ''))
            @php
                $totals = [];
            @endphp
            
            {{-- //For All Exams --}}
            @foreach ($exams as $exam)
                @php
                    $position = $exam->houseWisePosition($academicYearId, $semesterId);
                    $presentInWeigtages = $examWeightages->firstWhere('exam_id', $exam->id);
                @endphp
                @if (!$presentInWeigtages)
                <tr>
                    <td></td>
                    <td></td>
                    <td>{{$exam->exam_name}}</td>
                    <td></td>
                    @foreach ($houses as $house)
                        <td>{{$position[$house->id]['position']}}</td>
                        <td></td>
                        <td></td>
                    @endforeach
                </tr>
                @endif
            @endforeach

            {{-- //For Weightage Exams --}}
            @foreach ($examWeightages as $examWeightage)
                @php
                    $position = $examWeightage->exam->houseWisePosition($examWeightage->academic_year_id, $examWeightage->semester_id);
                @endphp
                <tr>
                    @if ($loop->index == 0)
                        <th rowspan="{{sizeof($examWeightages)+1}}">Academics - 36</th>
                    @endif
                    <td>{{$loop->index+1}}</td>
                    <td>{{$examWeightage->exam->exam_name}}</td>
                    <td>{{$examWeightage->mark}}</td>
                    @foreach ($houses as $house)
                        @php
                            if (isset($totals[$house->id])) {
                                $totals[$house->id] += $examWeightage->mark * $position[$house->id]['weightage'];
                            }else{
                                $totals[$house->id] = $examWeightage->mark * $position[$house->id]['weightage'];
                            }
                        @endphp
                        <td>{{$position[$house->id]['position']}}</td>
                        <td>{{$position[$house->id]['weightage']}}</td>
                        <td>{{$examWeightage->mark * $position[$house->id]['weightage']}}</td>
                    @endforeach
                </tr>
            @endforeach
            @if (sizeof($examWeightages) > 1)
                <tr>
                    <th></th>
                    <th>Total</th>
                    <th>36</th>
                    @foreach ($houses as $house)
                        <th></th>
                        <th></th>
                        <th>{{$totals[$house->id]}}</th>
                    @endforeach
                </tr>
            @endif            
        @endif

        {{-- Extra Curricular Part --}}
        @if ((sizeof($extraCurricularWeightages) > 0 || sizeof($extraCurriculars) > 0) && ($type == 2 || $type == ''))
            @php
                $totals = [];
            @endphp

            {{-- //For All Extra Curriculars --}}
            @foreach ($extraCurriculars as $extraCurricular)
                @php
                    $position = $extraCurricular->houseWisePosition($academicYearId, $semesterId);
                    $presentInWeigtages = $extraCurricularWeightages->firstWhere('performance_cat_id', $extraCurricular->id);
                @endphp
                @if (!$presentInWeigtages)
                    <tr>
                        <td></td>
                        <td></td>
                        <td>{{$extraCurricular->category_name}}</td>
                        <td></td>
                        @foreach ($houses as $house)
                            <td>{{$position[$house->id]['position']}}</td>
                            <td></td>
                            <td></td>
                        @endforeach
                    </tr>
                @endif
            @endforeach

            {{-- //For Weightage Extra Curriculars --}}
            @foreach ($extraCurricularWeightages as $extraCurricularWeightage)
                @php
                    $position = $extraCurricularWeightage->performanceCategory->houseWisePosition($extraCurricularWeightage->academic_year_id, $extraCurricularWeightage->semester_id);
                @endphp
                <tr>
                    @if ($loop->index == 0)
                        <th rowspan="{{sizeof($extraCurricularWeightages)+1}}">Extra-Curricular Activities - 33</th>
                    @endif
                    <td>{{$loop->index+1}}</td>
                    <td>{{$extraCurricularWeightage->performanceCategory->category_name}}</td>
                    <td>{{$extraCurricularWeightage->mark}}</td>
                    @foreach ($houses as $house)
                        @php
                            if (isset($totals[$house->id])) {
                                $totals[$house->id] += $extraCurricularWeightage->mark * $position[$house->id]['weightage'];
                            }else{
                                $totals[$house->id] = $extraCurricularWeightage->mark * $position[$house->id]['weightage'];
                            }
                        @endphp
                        <td>{{$position[$house->id]['position']}}</td>
                        <td>{{$position[$house->id]['weightage']}}</td>
                        <td>{{$extraCurricularWeightage->mark * $position[$house->id]['weightage']}}</td>
                    @endforeach
                </tr>
            @endforeach
            @if (sizeof($extraCurricularWeightages) > 1)
                <tr>
                    <th></th>
                    <th>Total</th>
                    <th>33</th>
                    @foreach ($houses as $house)
                        <th></th>
                        <th></th>
                        <th>{{$totals[$house->id]}}</th>
                    @endforeach
                </tr>
            @endif
        @endif


        {{-- Co Curricular Part --}}
        @if ((sizeof($coCurricularWeightages) > 0 || sizeof($coCurriculars) > 0) && ($type == 3 || $type == ''))
            @php
                $totals = [];
            @endphp

            {{-- //For All Co Curriculars --}}
            @foreach ($coCurriculars as $coCurricular)
                @php
                    $position = $coCurricular->houseWisePosition($academicYearId, $semesterId);
                    $presentInWeigtages = $coCurricularWeightages->firstWhere('performance_cat_id', $coCurricular->id);
                @endphp
                @if (!$presentInWeigtages)
                    <tr>
                        <td></td>
                        <td></td>
                        <td>{{$coCurricular->category_name}}</td>
                        <td></td>
                        @foreach ($houses as $house)
                            <td>{{$position[$house->id]['position']}}</td>
                            <td></td>
                            <td></td>
                        @endforeach
                    </tr>
                @endif
            @endforeach

            {{-- //For Weightage Co Curriculars --}}
            @foreach ($coCurricularWeightages as $coCurricularWeightage)
                @php
                    $position = $coCurricularWeightage->performanceCategory->houseWisePosition($coCurricularWeightage->academic_year_id, $coCurricularWeightage->semester_id);
                @endphp
                <tr>
                    @if ($loop->index == 0)
                        <th rowspan="{{sizeof($coCurricularWeightages)+1}}">Co-Curricular Activities - 31</th>
                    @endif
                    <td>{{$loop->index+1}}</td>
                    <td>{{$coCurricularWeightage->performanceCategory->category_name}}</td>
                    <td>{{$coCurricularWeightage->mark}}</td>
                    @foreach ($houses as $house)
                        @php
                            if (isset($totals[$house->id])) {
                                $totals[$house->id] += $coCurricularWeightage->mark * $position[$house->id]['weightage'];
                            }else{
                                $totals[$house->id] = $coCurricularWeightage->mark * $position[$house->id]['weightage'];
                            }
                        @endphp
                        <td>{{$position[$house->id]['position']}}</td>
                        <td>{{$position[$house->id]['weightage']}}</td>
                        <td>{{$coCurricularWeightage->mark * $position[$house->id]['weightage']}}</td>
                    @endforeach
                </tr>
            @endforeach
            @if (sizeof($coCurricularWeightages) > 1)
                <tr>
                    <th></th>
                    <th>Total</th>
                    <th>31</th>
                    @foreach ($houses as $house)
                        <th></th>
                        <th></th>
                        <th>{{$totals[$house->id]}}</th>
                    @endforeach
                </tr>
            @endif
        @endif
        
      
    </tbody>
  </table>
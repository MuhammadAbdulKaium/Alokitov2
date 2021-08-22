@extends('student::pages.student-profile.profile-layout')

@section('profile-content')
   <div class="panel ">
      <div class="panel-body">
         
         <div id="user-profile">
            <ul id="w2" class="nav-tabs margin-bottom nav">
               <li class="active"><a>House History</a></li>
               <li><a href="{{url('/student/profile/medical/history/'.$personalInfo->id)}}">Medical History</a></li>
            </ul>
         </div>

         <table class="table table-striped table-bordered" id="dataTable">
             <thead>
                 <tr>
                     <th>From</th>
                     <th>To</th>
                     <th>House</th>
                     <th>Floor No.</th>
                     <th>Room</th>
                     <th>Bed No.</th>
                 </tr>
             </thead>
             <tbody>
                @forelse ($houseHistories as $houseHistory)
                    @php
                        $house = $houses->firstWhere('id', $houseHistory['houseId']);
                        $room = $rooms->firstWhere('id', $houseHistory['roomId']);
                        $fromDate = Carbon\Carbon::parse($houseHistory['fromDate']);
                        $toDate = ($houseHistory['toDate'])?Carbon\Carbon::parse($houseHistory['toDate']):null;
                    @endphp
                    <tr>
                        <td>{{$fromDate->toFormattedDateString()}}</td>
                        <td>{{($toDate)?$toDate->toFormattedDateString():'Present'}}</td>
                        <td>{{$house->name}}</td>
                        <td>{{$houseHistory['floorNo']}}</td>
                        <td>{{$room->name}}</td>
                        <td>{{$houseHistory['bedNo']}}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10">
                            <div class="text-danger" style="text-align: center">Never assigned to any house!</div>
                        </td>
                    </tr>
                @endforelse
             </tbody>
         </table>

      </div>
   </div>
   <!--/responsive-->

@endsection

@section('scripts')
   <script type="text/javascript">
        $(document).ready(function (){
            
        });
   </script>
@endsection



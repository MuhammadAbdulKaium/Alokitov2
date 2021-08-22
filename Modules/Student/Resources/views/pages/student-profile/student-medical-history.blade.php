@extends('student::pages.student-profile.profile-layout')

@section('profile-content')
   <div class="panel ">
      <div class="panel-body">
         
         <div id="user-profile">
            <ul id="w2" class="nav-tabs margin-bottom nav">
                <li><a href="{{url('/student/profile/house/history/'.$personalInfo->id)}}">House History</a></li>
               <li class="active"><a>Medical History</a></li>
            </ul>
         </div>

         @foreach ($medicalHistories as $medicalHistory)
             <table class="table table-bordered">
                 <thead style="background: #008D4C; color: white">
                     <tr>
                        <th>Prescription ID: {{ $medicalHistory->id }}</th>
                        <th>
                            <span style="float: left">Details</span>
                            <span style="float: right">Date: {{ Carbon\Carbon::parse($medicalHistory->created_at)->format('d/m/Y') }}</span>
                        </th>
                     </tr>
                 </thead>
                 <tbody>
                     @php
                         $content = json_decode($medicalHistory->content, 1);
                     @endphp
                     @foreach ($content as $key => $items)
                         <tr>
                             <td>{{ Illuminate\Support\Str::title($key) }}</td>
                             <td>
                                 <ul>
                                     @foreach ($items as $item)
                                        @if ($key == 'investigations')
                                            <li>{{ $item['title'] }}</li>
                                        @elseif ($key == 'treatments')
                                            <li>
                                                {{ $item['drugName'] }} (Qty: {{ $item['quantity'] }})
                                                <div>{{ $item['interval'] }} - {{ $item['comment'] }} - {{ $item['days'] }}days (Till: {{ $item['endDate'] }})</div>
                                            </li>
                                        @elseif ($key == 'excuses')
                                            <li>{{ $item['startDate'] }} - {{ $item['endDate'] }} ({{ $item['days'] }}days): {{ $item['comment'] }}</li>
                                        @else
                                            <li>{{ $item }}</li>
                                        @endif
                                     @endforeach
                                 </ul>
                             </td>
                         </tr>
                     @endforeach
                 </tbody>
             </table>
         @endforeach
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



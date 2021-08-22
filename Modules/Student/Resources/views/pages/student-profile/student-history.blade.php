@extends('student::pages.student-profile.profile-layout')

@section('profile-content')
   <div class="panel ">
      <div class="panel-body">
         
         <div id="user-profile">
            <ul id="w2" class="nav-tabs margin-bottom nav">
               <li class=""><a href="{{url('/student/profile/house/history/'.$personalInfo->id)}}">House History</a></li>
               <li class=""><a href="{{url('/student/profile/medical/history/'.$personalInfo->id)}}">Medical History</a></li>
            </ul>
         </div>


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



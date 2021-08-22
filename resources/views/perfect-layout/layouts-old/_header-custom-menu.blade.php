
<div class="navbar-custom-menu">
   <ul class="nav navbar-nav">
      {{--<li class="dropdown user-link">--}}
      {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
      {{--<i class="fa fa-th"></i>--}}
      {{--</a>--}}
      {{--<ul class="dropdown-menu">--}}
      {{--<li>--}}
      {{--<ul class="menu">--}}
      {{--student profile--}}
      {{--@role(['student'])--}}
      {{--<li>--}}
      {{--<a href="{{url('/student/profile/personal/'.Auth::user()->student()->id)}}">--}}
      {{--<i class="fa fa-user fa-2x"></i>--}}
      {{--<h4>View Profile</h4>--}}
      {{--</a>--}}
      {{--</li>--}}
      {{--@endrole--}}

      {{--teacher profile--}}
      {{--@role(['teacher'])--}}
      {{--<li>--}}
      {{--<a href="{{url('/employee/profile/personal/'.Auth::user()->employee()->id)}}">--}}
      {{--<i class="fa fa-user fa-2x"></i>--}}
      {{--<h4>View Profile</h4>--}}
      {{--</a>--}}
      {{--</li>--}}
      {{--@endrole--}}
      {{--<li>--}}
      {{--<a href="#">--}}
      {{--<i class="fa fa-object-group fa-2x"></i>--}}
      {{--<h4>Assignment</h4>--}}
      {{--</a>--}}
      {{--</li>--}}
      {{--@role(['parent', 'student'])--}}
      {{--<li>--}}
      {{--<a href="#">--}}
      {{--<i class="fa fa-question-circle fa-2x"></i>--}}
      {{--<h4>Helpdesk</h4>--}}
      {{--</a>--}}
      {{--</li>--}}
      {{--@endrole--}}
      {{--<li>--}}
      {{--<a href="#">--}}
      {{--<i class="fa fa-list-alt fa-2x"></i>--}}
      {{--<h4>Leave Application</h4>--}}
      {{--</a>--}}
      {{--</li>--}}
      {{--<li>--}}
      {{--<a href="#">--}}
      {{--<i class="fa fa-book fa-2x"></i>--}}
      {{--<h4>Issued Books</h4>--}}
      {{--</a>--}}
      {{--</li>--}}
      {{--<li>--}}
      {{--<a href="#">--}}
      {{--<i class="fa fa-credit-card fa-2x"></i>--}}
      {{--<h4>Salary Slip</h4>--}}
      {{--</a>--}}
      {{--</li>--}}
      {{--<li>--}}
      {{--<a href="#">--}}
      {{--<i class="fa fa-check-square-o fa-2x"></i>--}}
      {{--<h4>Leave Reporting</h4>--}}
      {{--</a>--}}
      {{--</li>--}}
      {{--<li>--}}
      {{--<a href="#">--}}
      {{--<i class="fa fa-search fa-2x"></i>--}}
      {{--<h4>Search Books</h4>--}}
      {{--</a>--}}
      {{--</li>--}}
      {{--<li>--}}
      {{--<a href="/user/my-logins">--}}
      {{--<i class="fa fa-sign-in fa-2x"></i>--}}
      {{--<h4>My Logins</h4>--}}
      {{--</a>--}}
      {{--</li>--}}
      {{--</ul>--}}
      {{--</li>--}}
      {{--</ul>--}}
      {{--</li>--}}

      {{--@if($languages = \Modules\Setting\Entities\Language::all())--}}
      {{--<div class="dropdown">--}}
      {{--<button id="SelectLaguage" class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">--}}

      {{--{{Config::get('app.locale')}}--}}

      {{--<span class="caret"></span></button>--}}
      {{--<ul class="dropdown-menu">--}}
      {{--@foreach($languages as $language)--}}
      {{--<li><a class="select_language" id="{{$language->language_name}}" href="{{URL::to('setting/language/choose/'.$language->language_slug)}}">{{$language->language_name}}</a></li>--}}
      {{--@endforeach--}}
      {{--</ul>--}}
      {{--</div>--}}
      {{--@endif--}}

      @php $user = Auth::user(); @endphp
      @php $campusList = $user->userInfo()->get(); @endphp
      {{--user campus--}}
      @role(['admin'])
      <li class="dropdown" id="lang-select">
         <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="Select Campus">
            <span class="fa fa-university"></span>
         </a>
         <ul role="menu" class="dropdown-menu">
            <li class="dropdown-header text-bold">
               <big>Select Campus</big>
            </li>
            {{--campus looping--}}
            @foreach($campusList as $myCampus)
               @php $campus = $myCampus->campus() @endphp
               @if($campus)
                  <li>
                     <a class="{{session()->get('campus')==$campus->id?'bg-green-active':''}}" href="/setting/institute/campus/{{$campus->id}}">
                        {{$campus->name}}
                     </a>
                  </li>
               @endif
            @endforeach
         </ul>
      </li>
      @endrole

      <li class="dropdown" id="lang-select">
         <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="Select Language">
            <span class="fa fa-language fa-lg"></span>
         </a>
         <ul role="menu" class="dropdown-menu">
            <li class="dropdown-header text-bold">
               <big>Select Language</big>
            </li>

            @if($languages = \Modules\Setting\Entities\Language::all())
               {{--{{Config::get('app.locale')}}--}}
               @foreach($languages as $language)
                  <li><a class="select_language" id="{{$language->language_name}}" href="{{URL::to('setting/language/choose/'.$language->language_slug)}}">{{$language->language_name}}</a></li>
               @endforeach

            @endif
         </ul>
      </li>
      <!-- User Account: style can be found in dropdown.less -->
      {{--photo--}}
      @php $photo = null; @endphp
      @php $user = Auth::user(); @endphp

      {{--student profile--}}
      @role(['student'])
      @php $photo = $user->student()->singelAttachment("PROFILE_PHOTO"); @endphp
      @endrole
      {{--parent profile--}}
      @role(['parent'])
      {{--@php $photo = $user->parent()->singelAttachment("PROFILE_PHOTO"); @endphp--}}
      @endrole

      <li class="dropdown">
         <a href="#">
            <i class="fa fa-bell-o"></i>
         </a>
      </li>
      <li class="dropdown user user-menu">
         <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            @if($photo)
               <img class="user-image" src="{{URL::asset('assets/users/images/'.$photo->singleContent()->name)}}" alt="No Image" style="width:30px;height:30px">
            @endif
            <span class="hidden-xs">{{ Auth::user()->name }} ({{Auth::user()->roles()->first()->display_name}}) </span>
         </a>
         <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header">
               {{--<img class="img-circle" src="/user/image?name=default.png" alt="NI">--}}
               @if($photo)
                  <img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/'.$photo->singleContent()->name)}}" alt="No Image" style="width:100px;height:100px">
               @else
                  <img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:100px;height:100px">
               @endif
               <p style="font-size: 18px;">{{ Auth::user()->name }} <br/> ({{ Auth::user()->email }})
               </p>
            </li>

            <!-- Menu Footer-->
            <li class="user-footer">
               <div class="pull-left">
                  <a class="btn btn-default btn-flat" href="#" style="font-size:12px">Change Password</a>
               </div>
               <div class="pull-right">
                  <a class="btn btn-default btn-flat" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"style="font-size:12px">Sign out</a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                     {{ csrf_field() }}
                  </form>
               </div>
            </li>
         </ul>
      </li>
   </ul>
</div>

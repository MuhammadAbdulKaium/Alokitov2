
<div class="col-md-3" style="float: right">
   <div class="logo-button">
      <ul class="logo-nav logo-nav-right">
         @php $user = Auth::user(); @endphp
         @php $campusList = $user->userInfo()->get(); @endphp

         {{--@role(['admin'])--}}
         {{--<div class="btn-group dropdown-menus-open" style="color: #000 !important;">--}}
            {{--<li><a class="dropdown-toggle-campus" aria-expanded="false" title="Select Campus"><span class=" fa fa-institution"></span></a>--}}
               {{--<ul class="dropdown-menus menu-campus">--}}
                  {{--<li><a href="#">Select Campus</a></li>--}}
                  {{--@foreach($campusList as $myCampus)--}}
                     {{--@php $campus = $myCampus->campus() @endphp--}}
                     {{--@if($campus)--}}
                        {{--<li class="{{session()->get('campus')==$campus->id?'campus-active':''}}">--}}
                           {{--<a class="{{session()->get('campus')==$campus->id?'bg-green-active':''}}" href="/setting/institute/campus/{{$campus->id}}">--}}
                              {{--{{$campus->name}}--}}
                           {{--</a>--}}
                        {{--</li>--}}
                     {{--@endif--}}
                  {{--@endforeach--}}
               {{--</ul>--}}
         {{--</div>--}}
         {{--@endrole--}}

          @role(['admin'])
          <li class="dropdown" id="lang-select">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="Select Language">
                  <span class="fa fa-institution"></span>
              </a>
              <ul role="menu" class="dropdown-menu mini-dropdown">
                  <li class="dropdown-header text-bold drop-down-header">
                      <big>Select Campus</big>
                  </li>
                  <li><a href="#">Select Campus</a></li>
                  @foreach($campusList as $myCampus)
                      @php $campus = $myCampus->campus() @endphp
                      @if($campus)
                          <li class="{{session()->get('campus')==$campus->id?'campus-active':''}}">
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
                  <span class="fa fa-language"></span>
              </a>
              <ul role="menu" class="dropdown-menu mini-dropdown">
                  <li class="dropdown-header text-bold drop-down-header">
                      <big>Select Language</big>
                  </li>
                  @php $selectLanguage=App::getLocale() @endphp
                  @if($languages = \Modules\Setting\Entities\Language::all())
                      {{--{{Config::get('app.locale')}}--}}
                      @foreach($languages as $language)
                          @if($language->language_slug==$selectLanguage)
                              <li class="lang-active"><a  class="select_language "  id="{{$language->language_name}}" href="{{URL::to('setting/language/choose/'.$language->language_slug)}}">{{$language->language_name}}</a></li>
                          @else
                              <li><a  class="select_language "  id="{{$language->language_name}}" href="{{URL::to('setting/language/choose/'.$language->language_slug)}}">{{$language->language_name}}</a></li>

                          @endif
                      @endforeach

                  @endif
              </ul>
          </li>

         {{--<li><a href="#" class="pro-custom"><img src="{{URL::asset('template-2/img/profile.jpeg')}}" height="40px" width="40px" style="border-radius:50%" alt="profile" class="pro-pic"></a></li>--}}




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
              <a class="btn account dropdown-toggle" data-toggle="dropdown" href="index.html#" aria-expanded="true">
                 @if($photo)
                  <img src="{{URL::asset('assets/users/images/'.$photo->singleContent()->name)}}" height="40px" width="40px" style="border-radius:50%" alt="profile" class="pro-pic">
                  @else
                      <img src="{{URL::asset('assets/users/images/user-default.png')}}" height="40px" width="40px" style="border-radius:50%" alt="profile" class="pro-pic">
                  @endif
                  <span class="hidden-xs">{{ Auth::user()->name }} ({{Auth::user()->roles()->first()->display_name}}) </span>

              </a>
              <ul class="dropdown-menu mini-dropdown">
                  <li><a href="#"><i class="fa fa-user"></i> Profile</a></li>
                  <li><a href="#"><i class="fa fa-cog"></i> Settings</a></li>
                  <li><a href="#"><i class="fa fa-envelope"></i> Messages</a></li>
                  <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i> Logout</a></li>

                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      {{ csrf_field() }}
                  </form>
              </ul>
          </li>



          {{--<li class="dropdown user user-menu">--}}
              {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
                  {{--@if($photo)--}}
                      {{--<img class="user-image" src="{{URL::asset('assets/users/images/'.$photo->singleContent()->name)}}" alt="No Image" style="width:30px;height:30px">--}}
                  {{--@endif--}}
                  {{--<span class="hidden-xs">{{ Auth::user()->name }} ({{Auth::user()->roles()->first()->display_name}}) </span>--}}
              {{--</a>--}}
              {{--<ul class="dropdown-menu">--}}
                  {{--<!-- User image -->--}}
                  {{--<li class="user-header">--}}
                      {{--<img class="img-circle" src="/user/image?name=default.png" alt="NI">--}}
                      {{--@if($photo)--}}
                          {{--<img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/'.$photo->singleContent()->name)}}" alt="No Image" style="width:100px;height:100px">--}}
                      {{--@else--}}
                          {{--<img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:100px;height:100px">--}}
                      {{--@endif--}}
                      {{--<p style="font-size: 18px;">{{ Auth::user()->name }} <br/> ({{ Auth::user()->email }})--}}
                      {{--</p>--}}
                  {{--</li>--}}

                  {{--<!-- Menu Footer-->--}}
                  {{--<li class="user-footer">--}}
                      {{--<div class="pull-left">--}}
                          {{--<a class="btn btn-default btn-flat" href="#" style="font-size:12px">Change Password</a>--}}
                      {{--</div>--}}
                      {{--<div class="pull-right">--}}
                          {{--<a class="btn btn-default btn-flat" href="{{ route('logout') }}" onclick="event.preventDefault();--}}
                                                     {{--document.getElementById('logout-form').submit();"style="font-size:12px">Sign out</a>--}}

                      {{--</div>--}}
                  {{--</li>--}}
              {{--</ul>--}}
          {{--</li>--}}


      </ul>
   </div>
</div>

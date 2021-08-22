

<div class="navbar-custom-menu">
	<ul class="nav navbar-nav">
		<li class="dropdown user-link">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
				<i class="fa fa-th"></i>
			</a>
			<ul class="dropdown-menu">
				<li>
					<ul class="menu">
						<li>
							<a href="/user/my-logins">
								<i class="fa fa-sign-in fa-2x"></i>
								<h4>My Logins</h4>
							</a>
						</li>
						<li>
							<a href="#">
								<i class="fa fa-question-circle fa-2x"></i>
								<h4>Helpdesk</h4>
							</a>
						</li>
					</ul>
				</li>
			</ul>
		</li>
		<!-- User Account: style can be found in dropdown.less -->
		{{--<li class="dropdown">--}}
			{{--<a href="#">--}}
				{{--<i class="fa fa-bell-o"></i>--}}
			{{--</a>--}}
		{{--</li>--}}
		<li class="dropdown user user-menu">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
				<!-- <img class="user-image" src="/user/image?name=default.png" alt="NI">  -->
				<span class="hidden-xs">{{ Auth::user()->name }} </span>
			</a>
			<ul class="dropdown-menu">
				<!-- User image -->
				<li class="user-header">
					<!-- <img class="img-circle" src="/user/image?name=default.png" alt="NI"> -->
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

@extends('onlineacademics::layouts.onlineacademic')
<!-- page content -->
@section('page-content')
@if($ScheduledData->class_status==4)
    
 @php echo 'This Class is already Taken'; @endphp      


@else


<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{ asset('css/bootstrap3-wysihtml5.min.css') }}">
<style type="text/css">
    img{ max-width:100%;}
    .inbox_people {
        background: #f8f8f8 none repeat scroll 0 0;
        float: left;
        overflow: hidden;
        width: 45%; 
        border-right:1px solid #c4c4c4;
    }

    .topic_list {
        background: #f8f8f8 none repeat scroll 0 0;
        float: left;
        overflow: hidden;
        width: 100%; 
        border-right:1px solid #c4c4c4;
    }
    .inbox_msg {
        border: 1px solid #c4c4c4;
        clear: both;
        overflow: hidden;
    }
    .top_spac{ margin: 20px 0 0;}
    .recent_heading {
        width:40%;
        float: right;
        margin-right: 68px;
    }
    .srch_bar {
        display: inline-block;
        text-align: right;
        width: 30%; 
    }
    .headind_srch{
        padding:10px 29px 10px 20px;
        overflow:hidden;
        border-bottom:1px solid #c4c4c4;
    }
    .headind_srch1{
        padding:0px 70px 0px 40px;
        overflow:hidden;
        border-bottom:1px solid #c4c4c4;
    }
    .recent_heading h4 {
        color: black;
        font-size: 18px;
        margin: auto;
    }
    .srch_bar input{ 
    	border:1px solid #cdcdcd; 
    	border-width:0 0 1px 0; 
    	width:80%; 
    	padding:2px 0 4px 6px; 
    	background:none;
    }
    .srch_bar .input-group-addon button {
        background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
        border: medium none;
        padding: 0;
        color: #707070;
        font-size: 18px;
    }
    .srch_bar .input-group-addon { margin: 0 0 0 -27px;}
    .chat_ib h5{ font-size:15px; color:#464646; margin:0 0 8px 0;}
    .chat_ib h5 span{ font-size:13px; float:right;}
    .chat_ib p{ font-size:14px; color:#989898; margin:auto}
    .chat_img {
        float: left;
        width: 11%;
    }
   .chat_img1 {
        float: left;
        width: 10%;
    }
    .chat_ib {
        float: left;
        padding: 0 0 0 20px;
        width: 70%;
    }
    .chat_people{
        overflow:hidden;
        clear:both;
        margin: -7px;
    }
    .chat_list {
        border-bottom: 1px solid #c4c4c4;
        margin: 0;
        padding: 18px 16px 10px;
    }
    .inbox_chat {
        height: 550px;
        overflow-y: scroll;
    }
    .subject_list {
        height: 490px;
        overflow-y: scroll;
    }
    .active_chat{ background:#ebebeb;}
    .incoming_msg_img {
        display: inline-block;
        width: 6%;
    }
    .received_msg {
        display: inline-block;
        padding: 0 0 0 10px;
        vertical-align: top;
        width: 92%;
    }
    .received_withd_msg p {
        background: #ebebeb none repeat scroll 0 0;
        border-radius: 3px;
        color: #646464;
        font-size: 14px;
        margin: 0;
        padding: 5px 10px 5px 12px;
        width: 100%;
    }
    .time_date {
        color: #747474;
        display: block;
        font-size: 12px;
        margin: 8px 0 0;
    }
    .received_withd_msg { width: 57%;}
    .mesgs {
        float: left;
        width: 55%;
        padding-top: 0px;
    }
    .sent_msg p {
        background: #05728f none repeat scroll 0 0;
        border-radius: 3px;
        font-size: 14px;
        margin: 0; color:#fff;
        padding: 5px 10px 5px 12px;
        width:100%;
    }
    .outgoing_msg{ overflow:hidden; margin:26px 0 26px;}
    .sent_msg {
        float: right;
        width: 46%;
    }
    .input_msg_write input {
        background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
        border: medium none;
        color: #4c4c4c;
        font-size: 15px;
        min-height: 48px;
        width: 100%;
    }
    .type_msg {border-top: 1px solid #c4c4c4;position: relative;}
    .msg_send_btn {
        background: #05728f none repeat scroll 0 0;
        border: medium none;
        border-radius: 50%;
        color: #fff;
        cursor: pointer;
        font-size: 17px;
        height: 33px;
        position: absolute;
        right: 0;
        top: 11px;
        width: 33px;
    }
    .messaging { padding: 0 0 50px 0;}
    .msg_history {
        height: 516px;
        overflow-y: auto;
    }
    .online_icon{
        position: relative;
        height: 15px;
        width:15px;
        background-color: #4cd137;
        border-radius: 50%;
        bottom: 0.2em;
        border:1.5px solid white;
        left: 3.3em;
    }
    .topic{
        position: relative;
        height: 20px;
        width:20px;
        bottom: 0.2em;
        border:1.5px solid white;
        left: 8.2em;
    }
    div.absolute {
        position: absolute;
        width: 100%;
        bottom: 35px;
        right: 5px;
    }
	div.media-container{
		width:100% !important;
	}
	div.media-controls{
	/*	margin-left: 718px !important;*/
	}
	div.media-box{
		height: 100%;
        width: 100%;
	} 
    @media screen and (max-width: 600px) {
        .online_icon{
            position: relative;
            height: 15px;
            width:15px;
            background-color: #4cd137;
            border-radius: 50%;
            bottom: 0.2em;
            border:1.5px solid white;
            left: 1.0em;
        }
    }
    @media screen and (max-width: 992px) {
        .online_icon {
            left: .50em;
        }
    }
    .offline_icon{
        position: relative;
        height: 15px;
        width:15px;
        background-color:red;
        border-radius: 50%;
        bottom: 0.2em;
        border:1.5px solid white;
        left: 3.3em;
    }
    @media screen and (max-width: 992px) {
        .offline_icon {
            left: .50em;
        }
    }
    @media screen and (max-width: 992px) {
        .offline_icon {
            left: .50em;
        }
    }
    .Textonlybutton {
        background:none;
        border:none;
        margin:5px;
        padding:0;
    }

       .usercamera {
        background: #f8f8f8 none repeat scroll 0 0;
        float: left;
        overflow: hidden;
        width:100%; 
        border-right:1px solid #c4c4c4;
    }
</style>

<div class="col-md-12" style="margin-top: 0px ;padding-top: 0px" id="boardscreen">
    <div class="box box-solid">
        <div class="box-body">
            <div class="row">

                @if (\Session::has('success'))
                               <div class="alert alert-success">
                                  <ul>
                                      <li>{!! \Session::get('success') !!}</li>
                                    </ul>
                               </div>
                            @endif
                            
			 <form  id="myForm" action="{{url('onlineacademics/onlineacademic/onlineclass')}}"  method="post">
			    <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="col-sm-3">
                	@php
                    $currentDate = date('m-d-Y');
                    $currentTime = date('h:i');
                    @endphp
                <span>
                	<strong>
                	        {{ date('m/d/Y') }} --  @if(isset($ScheduledData)) {{$ScheduledData->class_routine_time}} -- @endif @if(isset($ScheduledData)) {{$ScheduledData->academic_class}} @endif -- @if(isset($ScheduledData)) {{$ScheduledData->academic_section}} @endif -- @if(isset($topic_info->class_subject_id))
									{{$topic_info->class_subject}}
                                    @elseif(isset($ScheduledData)) {{$ScheduledData->class_subject}} --
                                    {{$ScheduledData->class_teacher_name}} 
							@endif 
                   </strong>
				</span>	

			<div id="statuschange">
				<span>
					<strong> CLass Status:
					  </strong>
				</span>	 
				<span>
				  	
				  	@if(isset($ScheduledData)) 
                    <input type="hidden" id="class_status" name="class_status" value="{{ $ScheduledData->class_status }}">
                      @if($ScheduledData->class_status ==2 ) 
                    <strong style="color: red">{{ 'Ongoing' }}</strong> 
                         
                        @elseif($ScheduledData->class_status ==6 ) 
                        
                             <strong style="color: green">   {{ 'Started' }} </strong>
                        @endif 
                    @endif 
                 
                </span>
                <br/>	
				<span>
					<strong> Teacher Status :
					  </strong>
				</span>	 
				  <span>
				   
				  	@if(isset($ScheduledData)) 
                        
                         @if($ScheduledData->class_status ==2 ) 
                                 <strong style="color: red">{{ 'Not Live' }}</strong> 
                        @elseif($ScheduledData->class_status ==6 ) 
                        
                                <strong style="color: green"> {{ 'Live' }} </strong>
                        @endif 
                    @endif 
                 
                </span>	

                <span>
					<strong> CLass Start Time:
					  </strong>
				</span>	 
				  <span>
				  	<strong style="color: green"> 
				  	@if(isset($ScheduledData))                     
                        @if($ScheduledData->class_status ==6 ) 
                            @if(isset($ScheduledData->class_conduct_time))
                                {{ date('h:i a',strtotime($ScheduledData->class_conduct_time)) }}
                             @endif   
                        @else
                             {{ 'Not set' }}
                       @endif 
                   @endif 
                 </strong>
                </span>	
             </div>   
{{--               
                <span>
					<strong> Remaning Time:
					  </strong>
				</span>	 
				  <span>
				  	<strong style="color: green"> 
				  	00:30:00
                 </strong>
                </span>	
                <span>
					  <strong>
					    Online Students:
					  </strong>
				</span>	 
				  <span>
				  	<strong style="color: green"> 
				  	40
                 </strong>
                </span> --}}	  

            @role(['teacher','super-admin','admin'])
                <button class="btn right" type="button" style="text-align: center; color: green ; border: 1px solid black"  id="open-room" > Conduct
                </button>
            @endrole

            <button class="btn right"  type="button" style="display: none;"  id="open-room" > Conduct
            </button>

                <input type="hidden" id="room-id" value="abcdef" autocorrect=off autocapitalize=off size=20>

                <button  type="button" class="btn right" style="text-align: center; color: green ; border: 1px solid black"   id="join-room">Join Class</button>

					<div class="row" style="margin-top: 0px;padding-bottom: 0px">

						<div class="board" style="width: 100%; height:  550px; border:1px solid #C0C0C0"> 
							<a data-toggle="tab" href="#pcsharing" style="display: none">
                                <button type="button" class="pull-right Textonlybutton" style="margin-right: 10px"><strong>PC</strong>
                              </button>
                        </a>
							{{-- <a data-toggle="tab" href="#camera"><button type="button" class="pull-right Textonlybutton">
                                <strong>Camera</strong></button></a>
							<a data-toggle="tab" href="#board">
                                <button type="button" class="pull-right Textonlybutton" style="color: red"><strong>Board</strong></button></a> --}}
							
							<!--- New File Integration -->
							<style type="text/css">
								.wPaint-menu-holder.wPaint-menu-name-main {
									margin-top:7px!important;
								}
								.wPaint-menu-handle{
									display:none !important;
								}
								.wPaint-menu ui-draggable .wPaint-menu-alignment-horizontal{
									left: -15px !important;
									top: -50px !important;
									width:100% !important; /*450px*/
								}
								.wPaint-theme-classic .wPaint-menu-holder{
									left: 36px !important;
								}
								.wPaint-theme-standard .wPaint-menu-icon-img {
									width: 20px !important;
								}
								#wPaint{
									background-color: rgb(255,255,254) !important; //rgb(192,192,192) !important;
								}
								.wPaint-menu-alignment-horizontal{
									left: -35px !important;
									width: 100% !important;
								}
								.wPaint-theme-standard .wPaint-menu-alignment-horizontal .wPaint-menu-icon {
									margin: 4px 5px 4px 4px;
								}
								.wPaint-theme-classic .wPaint-menu-holder {
									text-align: center;
								}
							</style>
							<script type="text/javascript" src="{{ asset('lib/jquery.1.10.2.min.js') }}"></script>
							<div class="tab-content" id="fullscreen_board">
                                <div class="content-box" style="background:#ffffff; margin:30px auto 20px auto; height:425px;" >
                                    <section class="experiment recordrtc">
                                        <button id="btn-leave-room" type="button" disabled>Leave /or close the room</button>
                                         <div class="row">
                                               <div class="col-sm-12">
                                                  <div class="messaging" >
                                                    <div class="inbox_msg" >
                                                       <div class="usercamera">
                                                            <div class="inbox_chat">
                                                               <div id="videos-container"  style="width: 100%; height:180px;"  ></div>  
                                                             <footer><small id="send-message"></small>
                                                                 </footer>
                                                           </div>
                                                       </div>
                                                     </div>
                                                  </div>
                                              </div>
                                          </div>
                                            <!--</h2>-->    
                                       </section>
                                    </div>
								<!--Board html Start-->
                                {{-- zzzzzzzzzzzzzzzzzzz
								<div id="board" class="tab-pane fade in" >
									<div class="content-box">
										<!-- jQuery UI -->
										<script type="text/javascript" src="{{ asset('lib/jquery.ui.core.1.10.3.min.js') }}"></script>
										<script type="text/javascript" src="{{ asset('lib/jquery.ui.widget.1.10.3.min.js') }}"></script>
										<script type="text/javascript" src="{{ asset('lib/jquery.ui.mouse.1.10.3.min.js') }}"></script>
										<script type="text/javascript" src="{{ asset('lib/jquery.ui.draggable.1.10.3.min.js') }}"></script>
					
										<!-- wColorPicker -->
										<link rel="Stylesheet" type="text/css" href="{{ asset('lib/wColorPicker.min.css') }}"/>
										<script type="text/javascript" src="{{ asset('lib/wColorPicker.min.js') }}"></script>
					
										<!-- wPaint -->
										<link rel="Stylesheet" type="text/css" href="{{ asset('wPaint.min.css') }}"/>
										<script type="text/javascript" src="{{ asset('wPaint.min.js') }}"></script>
										<script type="text/javascript" src="{{ asset('plugins/main/wPaint.menu.main.min.js') }}"></script>
										<script type="text/javascript" src="{{ asset('plugins/text/wPaint.menu.text.min.js') }}"></script>
										<script type="text/javascript" src="{{ asset('plugins/shapes/wPaint.menu.main.shapes.min.js') }}"></script>
										<script type="text/javascript" src="{{ asset('plugins/file/wPaint.menu.main.file.min.js') }}"></script>

										<style type="text/css">
											.wPaint-canvas-bg{
												width:100%;
												/*height:100%;*/
											}
											.wPaint-canvas{
												width:100% !important;
												/*height:100%;*/
											}
											/*.wPaint-theme-classic{
												width:100%;
												height:1200px !important;
											}
											.wPaint-theme-standard{
												width:100%;
												height:1200px !important;
											}*/
										</style>
										
										<div id="wPaint" style="position:relative; width:100%; height:475px; background-color:#7a7a7a; margin:70px auto 20px auto;"></div>
										<button onclick="openFullscreen();">White Board Open in Fullscreen Mode</button>

										<script type="text/javascript">

											//Full Screen Area Start

											var elem = document.getElementById("fullscreen_board");
											function openFullscreen() {
												var d = {};
												if (elem.requestFullscreen) {
													elem.requestFullscreen();
												} else if (elem.pmozRequestFullScreen) { /* Firefox */
													elem.mozRequestFullScreen();
												} else if (elem.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
													elem.webkitRequestFullscreen();
												} else if (elem.msRequestFullscreen) { /* IE/Edge */
													elem.msRequestFullscreen();
												}
											}
								            //Full Screen Area End


											var images = [
											  '/test/uploads/wPaint.png',
											];
										
											function saveImg(image) {
											  var _this = this;
										
											  $.ajax({
												type: 'POST',
												url: '/test/upload.php',
												data: {image: image},
												success: function (resp) {
										
												  // internal function for displaying status messages in the canvas
												  _this._displayStatus('Image saved successfully');
										
												  // doesn't have to be json, can be anything
												  // returned from server after upload as long
												  // as it contains the path to the image url
												  // or a base64 encoded png, either will work
												  resp = $.parseJSON(resp);
										
												  // update images array / object or whatever
												  // is being used to keep track of the images
												  // can store path or base64 here (but path is better since it's much smaller)
												  images.push(resp.img);
										
												  // do something with the image
												  $('#wPaint-img').attr('src', image);
												}
											  });
											}
										
											function loadImgBg () {
										
											  // internal function for displaying background images modal
											  // where images is an array of images (base64 or url path)
											  // NOTE: that if you can't see the bg image changing it's probably
											  // becasue the foregroud image is not transparent.
											  this._showFileModal('bg', images);
											}
										
											function loadImgFg () {
										
											  // internal function for displaying foreground images modal
											  // where images is an array of images (base64 or url path)
											  this._showFileModal('fg', images);
											}
										
											// init wPaint
											$('#wPaint').wPaint({
											  menuOffsetLeft: -35,
											  menuOffsetTop: -50,
											  saveImg: saveImg,
											  loadImgBg: loadImgBg,
											  loadImgFg: loadImgFg
											});
										  </script>
									</div>
								</div> --}}
								<!--Board html End-->
								
								<!--Camera html Start-->

{{--                                 <div id="camera" class="tab-pane fade active">
                                    <div class="content-box" style="background:#ffffff; margin:30px auto 20px auto; height:425px;" >
                                    <section class="experiment recordrtc">
                                           <button id="btn-leave-room" disabled>Leave /or close the room</button>
                                     <div class="row">
                                   <div class="col-sm-12">
                                      <div class="messaging" >
                                        <div class="inbox_msg" >
                                           <div class="usercamera">
                                                <div class="inbox_chat">
                                                   <div id="videos-container"  style="width: 100%; height:180px;"  ></div>  
                                                 <footer><small id="send-message"></small>
                                                     </footer>
                                               </div>
                                           </div>
                                         </div>
                                      </div>
                                  </div>
                                            <!--</h2>-->    
                                       </section>
                                    </div>
                                </div>
 --}}
								<!--Camera html End-->
								
								<!--PC Sharing html Start-->	
								<div id="pcsharing" class="tab-pane fade" style="position:relative; width:100%; height:475px; background-color:#7a7a7a; margin:70px auto 20px auto;">
									<h3>PC Sharing...................</h3>
								</div>
								<!--PC Sharing html End-->		  
					
								<button type="button" class="pull-right Textonlybutton" style="font-size: 15px;display: none"><strong>RECORD</strong></button>
								<button type="button" class="pull-right Textonlybutton" style="display: none">|</button>
								<button type="button" class="Textonlybutton pull-right" style="color: red; font-size: 15px;display: none"> | <strong> SCREENSHOT</strong></button>
								<button type="button" class="pull-right Textonlybutton" style="font-size: 15px;display: none"><strong>SHARE ME</strong></button>
								<button type="button" class="pull-right Textonlybutton"></button>
							</div>
						</div>
					</div>
				</div>
                <div class="col-md-3">
                            <div class="topic_list">
                        <div class="headind_srch">
                            <div class="recent_heading">
                                <h4>Topic</h4>
                            </div>
                        </div>
                        <div class="subject_list">
                            @if(isset($topic_list))
                                @foreach($topic_list as $topic)
                                <div class="chat_list active_chat">
                                    <div class="chat_people">
                                        <div class="chat_img1">
                                            <input type="checkbox" name="topiclist[]" value="{{ $topic->class_topic}}">
                                        </div>
                                        <div class="chat_ib">
                                            <h5>{{ $topic->class_topic}} <span class="chat_date">
                                                <span class="topic">
                                                    <i class="fa fa-file-pdf-o" style="font-size: 17px" ></i>
                                                </span>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                            
                        </div>
                    </div>
                </div>
				<div class="col-sm-6" style="margin-left: 0px; padding: 0px;">
					<!--Chat box start -->
					<div class="row">
						<div class="col-sm-12">
							<div class="messaging" >
								<div class="inbox_msg" >
									<div class="inbox_people">
										<div class="headind_srch">
											<div class="recent_heading">
												<h4>Student List</h4>
											</div>
                                        </div>
                                        
										<div class="inbox_chat">
                                            @if(isset($campusid)) 
                                                @if($campusid==42)
                                                    <div id="studentlis">
                                                        <div class="chat_ib"><h5>MUHAMMAD AMIRUL</h5> </div>
                                                        <div class="chat_ib"><h5>MUHAMMAD MONJUR</h5> </div>
                                                        <div class="chat_ib"><h5>TABIR SALEH</h5> </div>
                                                        <div class="chat_ib"><h5>MUHAMMAD ASLAM</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAS RASHEDUR </h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD SAPUAN</h5> </div>
                                                        <div class="chat_ib"><h5>CHOWDHURY TAFSIR</h5> </div>
                                                        <div class="chat_ib"><h5>NURUL HUDA</h5> </div>
                                                        <div class="chat_ib"><h5>MD. IFTHAJ TANJIM</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD RAHIM</h5> </div>
                                                        <div class="chat_ib"><h5>JUBARAJ</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD</h5> </div>
                                                        <div class="chat_ib"><h5>SADIK ANJUM</h5> </div>
                                                        <div class="chat_ib"><h5>NION</h5> </div>
                                                        <div class="chat_ib"><h5>MARUF MONJU</h5> </div>
                                                        <div class="chat_ib"><h5>HRIDOY KANTI</h5> </div>
                                                        <div class="chat_ib"><h5>MD. MINHAZ</h5> </div>
                                                        <div class="chat_ib"><h5>MD. TARIKUL</h5> </div>
                                                        <div class="chat_ib"><h5>KAZI MOHAMMDA ABU</h5> </div>
                                                        <div class="chat_ib"><h5>MD. ABU</h5> </div>
                                                        <div class="chat_ib"><h5>S.M NABUL</h5> </div>
                                                        <div class="chat_ib"><h5>APURBA</h5> </div>
                                                        <div class="chat_ib"><h5>RAHADANUL ISLAM</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD EBRAHIM</h5> </div>
                                                        <div class="chat_ib"><h5>JITUL KAWSAR</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD HASIBUL</h5> </div>
                                                        <div class="chat_ib"><h5>ABRAR BIN</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD JUNAYED</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD MONJURUL</h5> </div>
                                                        <div class="chat_ib"><h5>MAHINUR </h5> </div>
                                                        <div class="chat_ib"><h5>ARFATUL ISLAM</h5> </div>
                                                        <div class="chat_ib"><h5>ARIFUL ISLAM</h5> </div>
                                                        <div class="chat_ib"><h5>MD. SANIYAT</h5> </div>
                                                        <div class="chat_ib"><h5>MD. ABDULLAH AL</h5> </div>
                                                        <div class="chat_ib"><h5>KAKON</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD OMOR</h5> </div>
                                                        <div class="chat_ib"><h5>AMIT</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD ABU</h5> </div>
                                                        <div class="chat_ib"><h5>MD. MEJBAHUL</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD</h5> </div>
                                                        <div class="chat_ib"><h5>MD. JARIF</h5> </div>
                                                        <div class="chat_ib"><h5>SAYDUL</h5> </div>
                                                        <div class="chat_ib"><h5>SAJIDUL ISLAM</h5> </div>
                                                        <div class="chat_ib"><h5>MD. IJTIHAD</h5> </div>
                                                        <div class="chat_ib"><h5>ASHRAF UDDIN</h5> </div>
                                                        <div class="chat_ib"><h5>MD. IBRAHIM</h5> </div>
                                                        <div class="chat_ib"><h5>MD. IBRAHIM</h5> </div>
                                                        <div class="chat_ib"><h5>MD. MONIRUL ISLAM</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD RAHED</h5> </div>
                                                        <div class="chat_ib"><h5>MINHAZ UDDIN</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD KAWSAR</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD KAWSAR</h5> </div>
                                                        <div class="chat_ib"><h5>KAWSAR</h5> </div>
                                                        <div class="chat_ib"><h5>MD. SAJJAT SAMIR</h5> </div>
                                                        <div class="chat_ib"><h5>MD. MISRAHUR RABBI</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD JUBAYEDUL</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD SHOHEL</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD RIAZ</h5> </div>
                                                        <div class="chat_ib"><h5>HASSAN ULLAH</h5> </div>
                                                        <div class="chat_ib"><h5>MD. MIJANUR RAHMAN</h5> </div>
                                                        <div class="chat_ib"><h5>MUHAMMAD MUSTAFA</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD ABU</h5> </div>
                                                        <div class="chat_ib"><h5>NISSAN</h5> </div>
                                                        <div class="chat_ib"><h5>OMAR</h5> </div>
                                                        <div class="chat_ib"><h5>MD. AFAS</h5> </div>
                                                        <div class="chat_ib"><h5>MD. ABDUL RAFI</h5> </div>
                                                        <div class="chat_ib"><h5>SHURAIPUR JAMAN</h5> </div>
                                                        <div class="chat_ib"><h5>SHEIKH SAFIUL</h5> </div>
                                                        <div class="chat_ib"><h5>SHAHADAT HOSSEN</h5> </div>
                                                        <div class="chat_ib"><h5>SAWKAT</h5> </div>
                                                        <div class="chat_ib"><h5>SAFIUL HASAN</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD MUBARAUK</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD </h5> </div>
                                                        <div class="chat_ib"><h5>SHAHIN</h5> </div>
                                                        <div class="chat_ib"><h5>MD. ISHTIAQ UDDIN</h5> </div>
                                                        <div class="chat_ib"><h5>SHAHRIAR</h5> </div>
                                                        <div class="chat_ib"><h5>MD. MIZAN</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD ARAFATUL</h5> </div>
                                                        <div class="chat_ib"><h5>MD. RAKIBUL</h5> </div>
                                                        <div class="chat_ib"><h5>MD. MAHIN ULLAH</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD </h5> </div>
                                                        <div class="chat_ib"><h5>MD. IMAM</h5> </div>
                                                        <div class="chat_ib"><h5>MAHMUDUL</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD NISAN</h5> </div>
                                                        <div class="chat_ib"><h5>SABBIR HOSEN</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD ROBIUL</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD ERFANUL</h5> </div>
                                                        <div class="chat_ib"><h5>MOHAMMAD DILSHAT</h5> </div>
                                                    </div>
                                                @else
                                                    <div id="studentlis">
                                                        @foreach($studentList  as $std)
                                                            <div class="chat_list">
                                                                <div class="chat_people">
                                                                    <div class="chat_ib">
                                                                        <h5>{{ $std->middle_name }} <span class="chat_date">
                                                                    @if($std->attendance_status==0)    
                                                                    <span class="offline_icon"> 
                                                                    </span>
                                                                    @else
                                                                    <span class="online_icon">  
                                                                    </span>
                                                                    @endif

                                                                        </span>
                                                                    </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach	
                                                    </div>	
                                                @endif
                                            @endif			
                                        </div>
									</div>
			
									<div class="mesgs" >
										<div class="headind_srch1" >
											<h4 style="text-align: center;">Chat-window</h4>
										</div>
										<div class="msg_history">
                                            <div id="chat-container">
                                            <div id="file-container"></div>
                                            <div class="chat-output"></div>
                                        </div>  

										</div>
                                               
					<div class="type_msg">
					     	<div class="input_msg_write">
						         <input type="text"  id="input-text-chat"  class="write_msg" placeholder="Type a message" />
									<button  id="share-file"  
                                     class="msg_send_btn"  type="button">
                                        <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
                                    </button>
							</div>
							<div class="row" >
								<div class="col-sm-3"  style="margin-right: 0px;  ">
									{{-- <button  class="btn  Textonlybutton" type="button" ><strong>Send To</strong></button> --}}
								</div>
								<div class="col-sm-5" style="margin-left: 0px;margin-right: 0px ;padding: 0px;" >
									{{-- <div class="form-group">
									<select  class="form-control" name="chat_topic" aria-required="true">
										<option value="">Every One</option>
									</select>
									<div class="help-block">
                                        
                                    </div>
									</div> --}}
												</div>
												<div class="col-sm-3" style="margin-left: 0px;margin-right: 0px ;padding: 0px;" >
												   <button type="button" class="btn  Textonlybutton" style="color: green"><strong>Send </strong></button>
												</div>
											</div>
										</div>
									</div>
								</div>

                            @role(['super-admin','admin','teacher'])
								<div class="row">
									<div class="col-sm-9">
								<div class="form-group" aria-required="true">
								<label class="control-label" for="year_name">Remarks</label>
								<input type="text" id="remark" class="form-control" name="remark" maxlength="60" placeholder="Enter Remarks" aria-required="true">
								<div class="help-block"></div>
								</div>
								</div>
                                <input type="hidden" name="scheduleid" value="{{ $ScheduledData->id}}">
									<div class="col-sm-3" style="margin-left: 0px;margin-right: 0px ;padding: 0px;" >
										<div style="margin-top: 31px;"></div>
										<button type="button" onclick="myFunction()"  class="btn  Textonlybutton" style="font-size: 20px" ><strong>End Class </strong></button>
									</div>
                                    
								</div>
                            @endrole

                            @role(['student'])
                                <div class="row">
                                    <div class="col-sm-9">
                                <div class="form-group" aria-required="true">
                                <label class="control-label" for="year_name" style="display:none; ">Remarks</label>
                                <input type="text" id="remark" style="display: none;" class="form-control" name="remark" maxlength="60" placeholder="Enter Remarks" aria-required="true">
                                <div class="help-block"></div>
                                </div>
                                </div>
                                <input type="hidden" name="scheduleid" value="{{ $ScheduledData->id}}">
                                    <div class="col-sm-3" style="margin-left: 0px;margin-right: 0px ;padding: 0px;" >
                                        <div style="margin-top: 31px;"></div>
                                        <button type="button" onclick="myFunction()"  class="btn  Textonlybutton" style="font-size: 20px;display: none;" ><strong>End Class </strong></button>
                                    </div>
                                    
                                </div>
                            @endrole

							</div>
						</div>
					</div>
				</div>"
			<!-- </form>	 -->
            </div>
        </div>
    </div>
</div>
<div id="time"></div>

@endsection


@section('page-script')
{{--<script>--}}
var elem = document.getElementById("boardscreen");
function openFullscreen() {
    if (elem.requestFullscreen) {
        elem.requestFullscreen();
    } else if (elem.mozRequestFullScreen) { /* Firefox */
        elem.mozRequestFullScreen();
    } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
        elem.webkitRequestFullscreen();
    } else if (elem.msRequestFullscreen) { /* IE/Edge */
        elem.msRequestFullscreen();
    }
}

$(document).ready(function(){
    $(".dsce").hide();
    $(".asce").click(function(){
        $(".asce").hide();
        $(".dsce").show();
    });
    $(".dsce").click(function(){
        $(".dsce").hide();
        $(".asce").show();
    }); 
});

$('.online_delete_class').click(function(e){
    del_id = $(this).attr('id');
    var tr = $(this).closest('tr');

    swal({
        title: "Are you sure?",
        text: "You want to delete Class Topic",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Yes, I am sure!',
        cancelButtonText: "No, cancel it!",
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function(isConfirm){
        if (isConfirm){
            $.ajax({
                url: "#"+ del_id,
                type: 'GET',
                cache: false,
                success:function(result){
                    if(result=='success') {
                        tr.fadeOut(1000, function () {
                            $(this).remove();
                        });
                        swal("Success!", "Class Topic deleted successfully", "success");
                    } else {
                        swal("Waining!", "Can't delete Class Topic", "warning");
                    }
                }
            });

        }else {
            swal("Cancelled", "Your Class Topic is safe :)", "error");
            e.preventDefault();
        }
    });
});
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $('#myTable').DataTable();
        $('.sdate').datepicker({
            autoclose: true
        });
        setTimeout(function(){
            $('.alert').hide();
            $('.alert-info').hide();
            $('.alert-danger').hide();
        },3000);

    // request for batch list using level id
    jQuery(document).on('change','.academicYear',function(){
        // console.log("hmm its change");

        // get academic year id
        var year_id = $(this).val();
        var div = $(this).parent();
        var op="";

        $.ajax({
            url: "{{ url('/academics/find/level') }}",
            type: 'GET',
            cache: false,
            data: {'id': year_id }, //see the $_token
            datatype: 'application/json',

            beforeSend: function() {
                console.log(year_id);

            },

            success:function(data){
                console.log('success');

                //console.log(data.length);
                op+='<option value="0" selected disabled>--- Select Level ---</option>';
                for(var i=0;i<data.length;i++){
                    // console.log(data[i].level_name);
                    op+='<option value="'+data[i].id+'">'+data[i].level_name+'</option>';
                }

                // set value to the academic secton
                $('.academicSection').html("");
                $('.academicSection').append('<option value="" selected disabled>--- Select Section ---</option>');

                // set value to the academic batch
                $('.academicBatch').html("");
                $('.academicBatch').append('<option value="" selected disabled>--- Select Class ---</option>');

                // set value to the academic batch
                $('.academicLevel').html("");
                $('.academicLevel').append(op);
            },

            error:function(){

            }
        });
    });

    // request for batch list using level id
    jQuery(document).on('change','.academicLevel',function(){
        // console.log("hmm its change");

        // get academic level id
        var level_id = $(this).val();
        var div = $(this).parent();
        var op="";

        $.ajax({
            url: "{{ url('/academics/find/batch') }}",
            type: 'GET',
            cache: false,
            data: {'id': level_id }, //see the $_token
            datatype: 'application/json',

            beforeSend: function() {
                // console.log(level_id);
            },

            success:function(data){
                console.log('success');

                //console.log(data.length);
                op+='<option value="" selected disabled>--- Select Class ---</option>';
                for(var i=0;i<data.length;i++){
                    op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                }

                // set value to the academic batch
                $('.academicBatch').html("");
                $('.academicBatch').append(op);

                // set value to the academic secton
                $('.academicSection').html("");
                $('.academicSection').append('<option value="0" selected disabled>--- Select Section ---</option>');
            },

            error:function(){

            }
        });
    });

    // request for section list using batch id
    jQuery(document).on('change','.academicBatch',function(){
        console.log("hmm its change");

        // get academic level id
        var batch_id = $(this).val();
        var div = $(this).parent();
        var op="";

        $.ajax({
            url: "{{ url('/academics/find/section') }}",
            type: 'GET',
            cache: false,
            data: {'id': batch_id }, //see the $_token
            datatype: 'application/json',

            beforeSend: function() {
                console.log(batch_id);
            },

            success:function(data){
                console.log('success');

                //console.log(data.length);
                op+='<option value="" selected disabled>--- Select Section ---</option>';
                for(var i=0;i<data.length;i++){
                    op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                }

                // set value to the academic batch
                $('.academicSection').html("");
                $('.academicSection').append(op);
            },

            error:function(){

            },
        });
    });

    // request for section list using batch id
    jQuery(document).on('change','.academicSection',function(){
        // get academic level id
        var section_id  = $(this).val();
        var class_id    = $('#batch').val();
        var div = $(this).parent();
        var op="";

        $.ajax({
            url: "{{ url('/academics/find/subjcet') }}",
            type: 'GET',
            cache: false,
            data: {'class_id': class_id, 'section_id': section_id}, //see the $_token
            datatype: 'application/json',

            beforeSend: function() {
                console.log(class_id,section_id);
            },

            success:function(data){
                console.log('success');

                console.log(data);

                op+='<option value="" selected disabled>--- Select Subject ---</option>';
                for(var i=0;i<data.length;i++){
                    op+='<option value="'+data[i].id+'">'+data[i].sub_name+'</option>';
                }

                // set value to the academic batch
                $('.academicSubject').html("");
                $('.academicSubject').append(op);
            },

            error:function(){

            },
        });
    });
	
	// request for teacher list using subject id
    jQuery(document).on('change','.academicSubject',function(){
        // get academic level id
        var class_id    	= $('#batch').val();
		var section_id    	= $('#section').val();
		var subject_id  	= $(this).val();
        var div = $(this).parent();
        var op="";

        $.ajax({
            url: "{{ url('/onlineacademics/find/teacher') }}",
            type: 'GET',
            cache: false,
            data: {'class_id': class_id, 'section_id': section_id, 'subject_id': subject_id}, //see the $_token
            datatype: 'application/json',

            beforeSend: function() {
                console.log(class_id,section_id,subject_id);
            },

            success:function(data){
                console.log('success');

                console.log(data);

                op+='<option value="" selected disabled>--- Select Teacher ---</option>';
                for(var i=0;i<data.length;i++){
                    op+='<option value="'+data[i].id+'">'+data[i].first_name+'</option>';
                }

                // set value to the academic batch
                $('.academicTeacher').html("");
                $('.academicTeacher').append(op);
            },

            error:function(){

            },
        });
    });

    // request for section list using batch id
    jQuery(document).on('change','.academicTopic',function(){
        // get academic level id
        var section_id  = $('#section').val();
        var class_id    = $('#batch').val();
        var teacher_id  = $('#teacher_id').val();
        var div = $(this).parent();
        var op="";

        $.ajax({
            url: "{{ url('/onlineacademics/onlineacademic/find/topic') }}",
            type: 'GET',
            cache: false,
            data: {'section_id': section_id, 'class_id': class_id, 'teacher_id': teacher_id}, //see the $_token
            datatype: 'application/json',

            beforeSend: function() {
                console.log(section_id,class_id,teacher_id);
            },

            success:function(data){
                console.log('success');

                console.log(data);

                op+='<option value="" selected disabled>--- Select Topic ---</option>';
                for(var i=0;i<data.length;i++){
                    op+='<option value="'+data[i].id+'">'+data[i].sub_topic+'</option>';
                }

                // set value to the academic batch
                $('.academicSubjectTopic').html("");
                $('.academicSubjectTopic').append(op);
            },

            error:function(){

            },
        });
    });

    // Tequest For Topic Schedule List Status Changed
    jQuery(document).on('click','.topic_schedule',function(){
        var ScheduleVal = $('#topic_scheule_name').val();
        alert('Value :'+ScheduleVal);
        // get academic level id
        var section_id  = $(this).val();
        var class_id    = $('#batch').val();
        var div = $(this).parent();
        var op="";
        /*
        $.ajax({
            url: "{{ url('/academics/find/subjcet') }}",
            type: 'GET',
            cache: false,
            data: {'class_id': class_id, 'section_id': section_id}, //see the $_token
            datatype: 'application/json',

            beforeSend: function() {
                console.log(class_id,section_id);
            },

            success:function(data){
                console.log('success');

                console.log(data);

                op+='<option value="" selected disabled>--- Select Subject ---</option>';
                for(var i=0;i<data.length;i++){
                    op+='<option value="'+data[i].id+'">'+data[i].sub_name+'</option>';
                }

                // set value to the academic batch
                $('.academicSubject').html("");
                $('.academicSubject').append(op);
            },

            error:function(){

            },
        });
        */
    });  

});


//New Integration Add to Sayed Start[28/06/2020]
$('#academic_year').on('change', function()
{
    //alert(this.value); //or alert($(this).val());
	var AY = $(this).find("option:selected").text();
	document.getElementById('academic_year_id').innerHTML = " Academic Year: " + AY;
	//alert(AY); //or alert($(this).val());
});

$('#academic_level').on('change', function()
{
	var AL = $(this).find("option:selected").text();
	document.getElementById('academic_level_id').innerHTML = " Academic Level: " + AL;
});

$('#batch').on('change', function()
{
	var BATCH = $(this).find("option:selected").text();
	document.getElementById('academic_class_id').innerHTML = " Class: " + BATCH;
});

$('#section').on('change', function()
{
	var SECTION = $(this).find("option:selected").text();
	document.getElementById('academic_section_id').innerHTML = " Section: " + SECTION;
});

$('#teacher_id').on('change', function()
{
	var TEACHER = $(this).find("option:selected").text();
	document.getElementById('academic_teacher_id').innerHTML =  TEACHER;
});

$('#attendance_date').on('change', function()
{
	//var selected = $(this).val();
    //alert(selected);
	var ClassDate = $(this).val();
	document.getElementById('academic_class_date').innerHTML =  "Date :" + ClassDate;
});


</script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{ asset('js/bootstrap3-wysihtml5.all.min.js') }}"></script>
<script>
  jQuery(function () {
    // Summernote
   jQuery('#textarea').wysihtml5();
  })
</script>
<script>
window.enableAdapter = true; // enable adapter.js

// ......................................................
// .......................UI Code........................
// ......................................................
document.getElementById('open-room').onclick = function() {
    disableInputButtons();
   
        var class_status =$('#class_status').val();
        var scheduleid = "{{ $ScheduledData->class_scheduled_id  }}";
        var teacher_id = "{{ $ScheduledData->class_teacher_id   }}";

        $.ajax({
            url: "{{ url('onlineacademics/onlineclass/onlineclass_conducts') }}",
            type: 'GET',
            cache: false,
            data: {'class_status': class_status,'scheduleid':scheduleid,'teacher_id':teacher_id }, //see the $_token
           datatype: 'application/json',
            beforeSend: function() {
            },

            success:function(data){
                 $('#statuschange').html(data);
                // set value to the academic secton
            },

            error:function(){

            }
        });

   window.open('https://v3demo.mediasoup.org/?roomId='+scheduleid);
};

document.getElementById('join-room').onclick = function() {
    disableInputButtons();
  

    var class_status =$('#class_status').val();
        var scheduleid = "{{ $ScheduledData->id  }}";
		
		 
         var class_scheduled_id = "{{ $ScheduledData->class_scheduled_id  }}";

        $.ajax({
            url: "{{ url('onlineacademics/onlineclass/onlineclass_conducts_std_teach') }}",
            type: 'GET',
            cache: false,
            data: {'class_status': class_status,'scheduleid':scheduleid,'class_scheduled_id':class_scheduled_id }, //see the $_token
           datatype: 'application/json',
            beforeSend: function() {
            },

            success:function(data){
                 $('#studentlis').html(data);
                // set value to the academic secton
            },

            error:function(){

            }
        });

    window.open('https://v3demo.mediasoup.org/?roomId='+class_scheduled_id);    


};


document.getElementById('btn-leave-room').onclick = function() {
    this.disabled = true;

    if (connection.isInitiator) {
        // use this method if you did NOT set "autoCloseEntireSession===true"
        // for more info: https://github.com/muaz-khan/RTCMultiConnection#closeentiresession
        connection.closeEntireSession(function() {
            document.querySelector('h1').innerHTML = 'Entire session has been closed.';
        });
    } else {
        connection.leave();
    }
};

// ......................................................
// ................FileSharing/TextChat Code.............
// ......................................................

 var today = new Date();

var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
var dateTime = date+' '+time;



document.getElementById('share-file').onclick = function() {
    var fileSelector = new FileSelector();
    fileSelector.selectSingleFile(function(file) {
        connection.send(file);
    });
};

document.getElementById('input-text-chat').onkeyup = function(e) {
    e.preventDefault(); 
    if (e.keyCode != 13) return;

    // removing trailing/leading whitespace
    this.value = this.value.replace(/^\s+|\s+$/g, '');
    if (!this.value.length) return;
connection.send("<div class='outgoing_msg'><div class='sent_msg'>{{ auth()->user()->name}} :"+ dateTime + ' '+'<p>'+ this.value +'</p>' 
  +'</div>'+'</div>'+'</div>');

appendDIV("<strong style='color:green'>{{ auth()->user()->name}}</strong> :"+ dateTime + ' ' +this.value);

    // connection.send(this.value);
    // appendDIV(this.value);
     this.value = '';
};



var chatContainer = document.querySelector('.chat-output');

function appendDIV(event) {
    var div = document.createElement('div');
    div.innerHTML = event.data || event;
    chatContainer.insertBefore(div, chatContainer.firstChild);
    div.tabIndex = 0;
    div.focus();

    document.getElementById('input-text-chat').focus();
}

// ......................................................
// ..................RTCMultiConnection Code.............
// ......................................................

var connection = new RTCMultiConnection();

// by default, socket.io server is assumed to be deployed on your own URL
connection.socketURL = '';

// comment-out below line if you do not have your own socket.io server
// connection.socketURL = 'https://rtcmulticonnection.herokuapp.com:443/';

connection.socketMessageEvent = 'audio-video-file-chat-demo';

connection.enableFileSharing = true; // by default, it is "false".

connection.session = {
     audio: true,
     video: true,
     data: true
};

connection.sdpConstraints.mandatory = {
    OfferToReceiveAudio: true,
    // OfferToReceiveVideo: true
};

// https://www.rtcmulticonnection.org/docs/iceServers/
// use your own TURN-server here!
connection.iceServers = [{
    'urls': [
        'stun:stun.l.google.com:19302',
        'stun:stun1.l.google.com:19302',
        'stun:stun2.l.google.com:19302',
        'stun:stun.l.google.com:19302?transport=udp',
    ]
}];

connection.videosContainer = document.getElementById('videos-container');
connection.onstream = function(event) {
    event.mediaElement.removeAttribute('src');
    event.mediaElement.removeAttribute('srcObject');

    var video = document.createElement('video');
    video.controls = true;
    if(event.type === 'local') {
        video.muted = true;
    }
    video.srcObject = event.stream;

    var width = "150px";
    var mediaElement = getHTMLMediaElement(video, {
        title: event.userid,
        // buttons: ['mute-audio', 'mute-video', 'full-screen', 'volume-slider'],
        width: width,
        showOnMouseEnter: false
    });

    connection.videosContainer.appendChild(mediaElement);

    setTimeout(function() {
        mediaElement.media.play();
    }, 5000);

    mediaElement.id = event.streamid;
};

connection.onstreamended = function(event) {
    var mediaElement = document.getElementById(event.streamid);
    if (mediaElement) {
        mediaElement.parentNode.removeChild(mediaElement);
    }
};

connection.onmessage = appendDIV;
connection.filesContainer = document.getElementById('file-container');

connection.onopen = function() {
    document.getElementById('share-file').disabled = false;
    document.getElementById('input-text-chat').disabled = false;
    document.getElementById('btn-leave-room').disabled = false;

    document.querySelector('h1').innerHTML = 'You are connected with: ' + connection.getAllParticipants().join(', ');
};

connection.onclose = function() {
    if (connection.getAllParticipants().length) {
        document.querySelector('h1').innerHTML = 'You are still connected with: ' + connection.getAllParticipants().join(', ');
    } else {
        document.querySelector('h1').innerHTML = 'Seems session has been closed or all participants left.';
    }
};

connection.onEntireSessionClosed = function(event) {
    document.getElementById('share-file').disabled = true;
    document.getElementById('input-text-chat').disabled = true;
    document.getElementById('btn-leave-room').disabled = true;

    document.getElementById('open-or-join-room').disabled = false;
    document.getElementById('open-room').disabled = false;
    document.getElementById('join-room').disabled = false;
    document.getElementById('room-id').disabled = false;

    connection.attachStreams.forEach(function(stream) {
        stream.stop();
    });

    // don't display alert for moderator
    if (connection.userid === event.userid) return;
    document.querySelector('h1').innerHTML = 'Entire session has been closed by the moderator: ' + event.userid;
};

connection.onUserIdAlreadyTaken = function(useridAlreadyTaken, yourNewUserId) {
    // seems room is already opened
    connection.join(useridAlreadyTaken);
};

function disableInputButtons() {
    
    document.getElementById('open-room').disabled = true;
    document.getElementById('join-room').disabled = true;
    document.getElementById('room-id').disabled = true;
}

// ......................................................
// ......................Handling Room-ID................
// ......................................................

// function showRoomURL(roomid) {
//     var roomHashURL = '#' + roomid;
//     var roomQueryStringURL = '?roomid=' + roomid;

//     var html = '<h2>Unique URL for your room:</h2><br>';

//     html += 'Hash URL: <a href="' + roomHashURL + '" target="_blank">' + roomHashURL + '</a>';
//     html += '<br>';
//     html += 'QueryString URL: <a href="' + roomQueryStringURL + '" target="_blank">' + roomQueryStringURL + '</a>';

//     var roomURLsDiv = document.getElementById('room-urls');
//     roomURLsDiv.innerHTML = html;

//     roomURLsDiv.style.display = 'block';
// }

(function() {
    var params = {},
        r = /([^&=]+)=?([^&]*)/g;

    function d(s) {
        return decodeURIComponent(s.replace(/\+/g, ' '));
    }
    var match, search = window.location.search;
    while (match = r.exec(search.substring(1)))
        params[d(match[1])] = d(match[2]);
    window.params = params;
})();

var roomid = '';
if (localStorage.getItem(connection.socketMessageEvent)) {
    roomid = localStorage.getItem(connection.socketMessageEvent);
} else {
    roomid = connection.token();
}
document.getElementById('room-id').value = "{{ $ScheduledData->class_scheduled_id }}";
document.getElementById('room-id').onkeyup = function() {
    localStorage.setItem(connection.socketMessageEvent, this.value);
};

var hashString = location.hash.replace('#', '');
if (hashString.length && hashString.indexOf('comment-') == 0) {
    hashString = '';
}

var roomid = params.roomid;
if (!roomid && hashString.length) {
    roomid = hashString;
}

if (roomid && roomid.length) {
    document.getElementById('room-id').value = roomid;
    localStorage.setItem(connection.socketMessageEvent, roomid);

    // auto-join-room
    (function reCheckRoomPresence() {
        connection.checkPresence(roomid, function(isRoomExists) {
            if (isRoomExists) {
                connection.join(roomid);
                return;
            }

            setTimeout(reCheckRoomPresence, 5000);
        });
    })();

    disableInputButtons();
}
</script>

<script>
function myFunction() {
  document.getElementById("myForm").submit();
  
}
</script>

@endif


<!-- New WebRtc Connection Start -->
@endsection



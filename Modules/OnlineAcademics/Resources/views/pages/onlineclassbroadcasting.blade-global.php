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
        width: 45%; border-right:1px solid #c4c4c4;
    }

    .topic_list {
        background: #f8f8f8 none repeat scroll 0 0;
        float: left;
        overflow: hidden;
        width: 100%; border-right:1px solid #c4c4c4;
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


.tooltip {
  position: relative;
  display: inline-block;
  border-bottom: 1px dotted black;
}

.tooltip .tooltiptext {
  visibility: hidden;
  width: 120px;
  background-color: black;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;
  
  /* Position the tooltip */
 position: absolute;
  z-index: 1;
  bottom: 100%;
  left: 50%;
  margin-left: -60px;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
}




    .srch_bar input{ border:1px solid #cdcdcd; border-width:0 0 1px 0; width:80%; padding:2px 0 4px 6px; background:none;}
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
          width: 20%;
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
        margin-left: 718px !important;
    }
    div.media-box{
        height:417px !important;
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
</style>

<div class="col-md-12" style="margin-top: 0px ;padding-top: 0px" id="boardscreen">
    <div class="box box-solid">
        <div class="box-body">
            <div class="row">
                <div class="col-sm-5">
                    <div class="row">
                     <div class="col-sm-12">
                          @if (\Session::has('success'))
                               <div class="alert alert-success">
                                  <ul>
                                      <li>{!! \Session::get('success') !!}</li>
                                    </ul>
                               </div>
                            @endif

                                @php
                                $currentDate = date('m-d-Y');
                                $currentTime = date('h:i');
                                @endphp
                                
                           <span>
                                <strong>
                                    {{ date('m/d/Y') }} --  @if(isset($ScheduledData)) {{$ScheduledData->class_routine_time}} -- @endif @if(isset($ScheduledData)) {{$ScheduledData->academic_class}} @endif -- @if(isset($ScheduledData)) {{$ScheduledData->academic_section}} @endif -- @if(isset($topic_info->class_subject_id))
                                            {{$topic_info->class_subject}}
                                            @elseif(isset($ScheduledData)) {{$ScheduledData->class_subject}} 
                                    @endif 
                               </strong>
                           </span>  


 <form  id="myForm" action="{{url('onlineacademics/onlineacademic/onlineclass')}}"  method="post">

                    <strong>Teacher Name: {{$ScheduledData->class_teacher_name}}

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
                            <strong> Class Start Time:
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

                 </strong>


         @role(['teacher','super-admin','admin'])
        <button class="btn right" type="button" style="text-align: center; color: green ; border: 1px solid black"  id="open-or-join" > Conduct
        </button>
        @endrole
   
        <button  type="button" class="btn right joinstudent" style="text-align: center; color: green ; border : 1px solid black"   id="open-or-join" >
            Join Class
      </button>
            

            

             </div> 
                </div>    
                    <div class="row" style="margin-top: 0px;padding-bottom: 0px">
                        <div class="board" style="width: 100%; height:  550px; border:1px solid #C0C0C0"> 
                            <a data-toggle="tab" href="#camera">
                                <button type="button" class="pull-right Textonlybutton"  
                               id="share-screen" style="margin-right: 10px">
                                <strong>PC</strong></button>
                            </a>
                            <a data-toggle="tab" href="#camera">
                            <button type="button" class="pull-right Textonlybutton"  id="share-video"
                                ><strong>Camera</strong></button>
                            </a>
                            <a data-toggle="tab" href="#board">
                                <button type="button" class="pull-right Textonlybutton" style="color: red"><strong>Board</strong></button>
                            </a>
                            
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
                            <div class="tab-content">
                                <!--Board html Start-->
                                <div id="board" class="tab-pane fade " >
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
                    
                                        <div id="wPaint" style="position:relative; width:510px; height:475px; background-color:#7a7a7a; margin:70px auto 20px auto;"></div>  
                                        <script type="text/javascript">
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
                                </div>
                         <!--Board html End-->

                        <!--Camera html Start-->
                        <div id="camera" class="tab-pane fade in active" style="margin: 4px">
                           <input type="hidden"  id="broadcast-id"  value="abcdef" autocorrect=off autocapitalize=off size=20>
                                    <div class="content-box" style="position:relative; width:100%; height:475px; background:#ffffff; margin:30px auto 20px auto;">
                                        <section class="experiment recordrtc">
                                            <!--<h2 class="header" style="margin: 0;">-->
                                            <video style="height: 100%;width: 100%"
                                             id="video-preview" controls loop></video>
                                        </section>
                                    </div>
                                </div>
                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2" style="margin-right: 0px; padding:0px; margin-top:0px">
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
                <div class="col-sm-5" style="margin-left: 0px; padding: 0px;">
                        <!--   Chat box start  wit-->
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

                                      </div>    

 
                                        </div>
                
                                     <div class="mesgs">
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
                                                <div class="col-sm-6" style="margin-left: 0px;margin-right: 0px ;padding: 0px;" >
                                                </div>
                                                <div class="col-sm-6" style="margin-left: 0px;margin-right: 0px ;padding: 0px;" >
                                                
                                                </div>
                                            </div>
                                   </div>
                                                        
                                </div>

                            </div>
                        </div>
                     </div>
                    </div>

                     @role(['super-admin','admin','teacher'])
                      <form  id="myForm" action="{{url('onlineacademics/onlineacademic/onlineclass')}}"  method="post">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                         


                                <div class="row">
                                    <div class="col-sm-9">
                                <div class="form-group" aria-required="true">
                                <label class="control-label" for="year_name">Remarks</label>
    <input type="text"  id="remark" class="form-control" name="remark" maxlength="60" placeholder="Enter Remarks" aria-required="true">
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
                            </form>
                            @endrole


                    </div>
            </div>
        </div>
    </div>
</div>
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

    });  

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

<!-- Live Broadcasting start heree  -->

<script src="https://rtcmulticonnection.herokuapp.com:443/node_modules/fbr/FileBufferReader.js"></script>
<script src="https://rtcmulticonnection.herokuapp.com:443/node_modules/webrtc-adapter/out/adapter.js"></script>
<script src="https://rtcmulticonnection.herokuapp.com:443/dist/RTCMultiConnection.min.js"></script>
<script src="https://rtcmulticonnection.herokuapp.com:443/node_modules/webrtc-adapter/out/adapter.js"></script>
<script src="https://rtcmulticonnection.herokuapp.com:443/socket.io/socket.io.js"></script>



<script>

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



appendDIV("<div class='received_msg' style='color:green'> <div class='received_withd_msg'>{{ auth()->user()->name}}</strong> :"
    + dateTime + ' '+'<p>' +this.value+'</p>'+ '</div>'+'</div>');

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

// recording is disabled because it is resulting for browser-crash
// if you enable below line, please also uncomment above "RecordRTC.js"
var enableRecordings = false;
 // by default, it is "false".
var connection = new RTCMultiConnection();

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

// its mandatory in v3
connection.enableScalableBroadcast = true;

// each relaying-user should serve only 1 users
connection.maxRelayLimitPerUser = 1;

// we don't need to keep room-opened
// scalable-broadcast.js will handle stuff itself.
connection.autoCloseEntireSession = true;
connection.enableFileSharing = true;
// by default, socket.io server is assumed to be deployed on your own URL
connection.socketURL = 'https://rtcmulticonnection.herokuapp.com:443/';

// comment-out below line if you do not have your own socket.io server
// connection.socketURL = 'https://rtcmulticonnection.herokuapp.com:443/';

connection.socketMessageEvent = 'scalable-media-broadcast-demo';

// document.getElementById('broadcast-id').value = connection.userid;

// user need to connect server, so that others can reach him.
connection.connectSocket(function(socket) {
    socket.on('logs', function(log) {
        document.querySelector('h1').innerHTML = log.replace(/</g, '----').replace(/>/g, '___').replace(/----/g, '(<span style="color:red;">').replace(/___/g, '</span>)');
    });

    // this event is emitted when a broadcast is already created.
    socket.on('join-broadcaster', function(hintsToJoinBroadcast) {
        console.log('join-broadcaster', hintsToJoinBroadcast);

        connection.session = hintsToJoinBroadcast.typeOfStreams;
        connection.sdpConstraints.mandatory = {
            OfferToReceiveVideo: !!connection.session.video,
            OfferToReceiveAudio: !!connection.session.audio
        };
        connection.broadcastId = hintsToJoinBroadcast.broadcastId;
        connection.join(hintsToJoinBroadcast.userid);
    });

    socket.on('rejoin-broadcast', function(broadcastId) {
        console.log('rejoin-broadcast', broadcastId);

        connection.attachStreams = [];
        socket.emit('check-broadcast-presence', broadcastId, function(isBroadcastExists) {
            if (!isBroadcastExists) {
                // the first person (i.e. real-broadcaster) MUST set his user-id
                connection.userid = broadcastId;
            }

            socket.emit('join-broadcast', {
                broadcastId: broadcastId,
                userid: connection.userid,
                typeOfStreams: connection.session
            });
        });
    });

    socket.on('broadcast-stopped', function(broadcastId) {
        // alert('Broadcast has been stopped.');
        // location.reload();
        console.error('broadcast-stopped', broadcastId);
        alert('This broadcast has been stopped.');
    });

    // this event is emitted when a broadcast is absent.
    socket.on('start-broadcasting', function(typeOfStreams) {
        console.log('start-broadcasting', typeOfStreams);

        // host i.e. sender should always use this!
        connection.sdpConstraints.mandatory = {
            OfferToReceiveVideo: false,
            OfferToReceiveAudio: false
        };
        connection.session = typeOfStreams;

        // "open" method here will capture media-stream
        // we can skip this function always; it is totally optional here.
        // we can use "connection.getUserMediaHandler" instead
        connection.open(connection.userid);
    });
});

window.onbeforeunload = function() {
    // Firefox is ugly.
    document.getElementById('open-or-join').disabled = false;
};

var videoPreview = document.getElementById('video-preview');

connection.onstream = function(event) {
    if (connection.isInitiator && event.type !== 'local') {
        return;
    }

    connection.isUpperUserLeft = false;
    videoPreview.srcObject = event.stream;
    videoPreview.play();

    videoPreview.userid = event.userid;

    if (event.type === 'local') {
        videoPreview.muted = true;
    }

    if (connection.isInitiator == false && event.type === 'remote') {
        // he is merely relaying the media
        connection.dontCaptureUserMedia = true;
        connection.attachStreams = [event.stream];
        connection.sdpConstraints.mandatory = {
            OfferToReceiveAudio: false,
            OfferToReceiveVideo: false
        };

        connection.getSocket(function(socket) {
            socket.emit('can-relay-broadcast');

            if (connection.DetectRTC.browser.name === 'Chrome') {
                connection.getAllParticipants().forEach(function(p) {
                    if (p + '' != event.userid + '') {
                        var peer = connection.peers[p].peer;
                        peer.getLocalStreams().forEach(function(localStream) {
                            peer.removeStream(localStream);
                        });
                        event.stream.getTracks().forEach(function(track) {
                            peer.addTrack(track, event.stream);
                        });
                        connection.dontAttachStream = true;
                        connection.renegotiate(p);
                        connection.dontAttachStream = false;
                    }
                });
            }

            if (connection.DetectRTC.browser.name === 'Firefox') {
                // Firefox is NOT supporting removeStream method
                // that's why using alternative hack.
                // NOTE: Firefox seems unable to replace-tracks of the remote-media-stream
                // need to ask all deeper nodes to rejoin
                connection.getAllParticipants().forEach(function(p) {
                    if (p + '' != event.userid + '') {
                        connection.replaceTrack(event.stream, p);
                    }
                });
            }

            // Firefox seems UN_ABLE to record remote MediaStream
            // WebAudio solution merely records audio
            // so recording is skipped for Firefox.
            if (connection.DetectRTC.browser.name === 'Chrome') {
                repeatedlyRecordStream(event.stream);
            }
        });
    }

    // to keep room-id in cache
    localStorage.setItem(connection.socketMessageEvent, connection.sessionid);
};

// ask node.js server to look for a broadcast
// if broadcast is available, simply join it. i.e. "join-broadcaster" event should be emitted.
// if broadcast is absent, simply create it. i.e. "start-broadcasting" event should be fired.
document.getElementById('open-or-join').onclick = function() {
    var broadcastId = document.getElementById('broadcast-id').value="{{ $ScheduledData->class_scheduled_id  }}";
    if (broadcastId.replace(/^\s+|\s+$/g, '').length <= 0) {
        alert('Please enter broadcast-id');
        document.getElementById('broadcast-id').focus();
        return;
    }

   window.open('http://www.google.com');


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



    document.getElementById('open-or-join').disabled = false;


    document.getElementById('open-or-join').onclick = function() {

         alert("It has been click");

    disableInputButtons();
    connection.join(document.getElementById('broadcast-id').value="{{ $ScheduledData->class_scheduled_id  }}");

   

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


};



    connection.extra.broadcastId = broadcastId;

    connection.session = {
        audio: true,
        // video: true,
        oneway: true,
        data: true
    };

document.getElementById('share-screen').onclick = function() {
    this.disabled = true;
    connection.mediaConstraints.video = true;
    connection.addStream({
        screen: true,
        oneway: true,

    });

   
    connection.removeTrack(stream.getVideoTracks()[0]);


};


document.getElementById('share-video').onclick = function() {
    this.disabled = true;
    connection.mediaConstraints.video = true;
    connection.addStream({
        // video: true,
        oneway: true,
    
    });


    


    // connection.removeStream({
    //     screen: true,
    //     oneway: true
    // });

};



connection.onmessage = appendDIV;
connection.filesContainer = document.getElementById('file-container');



    connection.getSocket(function(socket) {
        socket.emit('check-broadcast-presence', broadcastId, function(isBroadcastExists) {
            if (!isBroadcastExists) {
                // the first person (i.e. real-broadcaster) MUST set his user-id
                connection.userid = broadcastId;
            }

            console.log('check-broadcast-presence', broadcastId, isBroadcastExists);

            socket.emit('join-broadcast', {
                broadcastId: broadcastId,
                userid: connection.userid,
                typeOfStreams: connection.session
            });
        });
    });
};

connection.onstreamended = function() {};

connection.onleave = function(event) {
    if (event.userid !== videoPreview.userid) return;

    connection.getSocket(function(socket) {
        socket.emit('can-not-relay-broadcast');

        connection.isUpperUserLeft = true;

        if (allRecordedBlobs.length) {
            // playing lats recorded blob
            var lastBlob = allRecordedBlobs[allRecordedBlobs.length - 1];
            videoPreview.src = URL.createObjectURL(lastBlob);
            videoPreview.play();
            allRecordedBlobs = [];
        } else if (connection.currentRecorder) {
            var recorder = connection.currentRecorder;
            connection.currentRecorder = null;
            recorder.stopRecording(function() {
                if (!connection.isUpperUserLeft) return;

                videoPreview.src = URL.createObjectURL(recorder.getBlob());
                videoPreview.play();
            });
        }

        if (connection.currentRecorder) {
            connection.currentRecorder.stopRecording();
            connection.currentRecorder = null;
        }
    });
};

var allRecordedBlobs = [];

function repeatedlyRecordStream(stream) {
    if (!enableRecordings) {
        return;
    }

    connection.currentRecorder = RecordRTC(stream, {
        type: 'video'
    });

    connection.currentRecorder.startRecording();

    setTimeout(function() {
        if (connection.isUpperUserLeft || !connection.currentRecorder) {
            return;
        }

        connection.currentRecorder.stopRecording(function() {
            allRecordedBlobs.push(connection.currentRecorder.getBlob());

            if (connection.isUpperUserLeft) {
                return;
            }

            connection.currentRecorder = null;
            repeatedlyRecordStream(stream);
        });
    }, 30 * 1000); // 30-seconds
};

function disableInputButtons() {
    document.getElementById('open-or-join').disabled = false;
    document.getElementById('broadcast-id').disabled = false;
}

// ......................................................
// ......................Handling broadcast-id................
// ......................................................



var broadcastId = '';
if (localStorage.getItem(connection.socketMessageEvent)) {
    broadcastId = localStorage.getItem(connection.socketMessageEvent);
} else {
    broadcastId = connection.token();
}
var txtBroadcastId = document.getElementById('broadcast-id');
txtBroadcastId.value = broadcastId;
txtBroadcastId.onkeyup = txtBroadcastId.oninput = txtBroadcastId.onpaste = function() {
    localStorage.setItem(connection.socketMessageEvent, this.value);
};

// below section detects how many users are viewing your broadcast

connection.onNumberOfBroadcastViewersUpdated = function(event) {
    if (!connection.isInitiator) return;

    document.getElementById('broadcast-viewers-counter').innerHTML = 'Number of broadcast viewers: <b>' + event.numberOfBroadcastViewers + '</b>';
};
</script>


<script>
function myFunction() {
  document.getElementById("myForm").submit();
  
}


 $('#remark').bind('keypress keydown keyup', function(e){
       if(e.keyCode == 13) { e.preventDefault(); }
    });

</script>

@endif

@endsection



@extends('layouts.master')
<!-- page content -->
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-plus-square"></i>SMS Group</h1>
        <ul class="breadcrumb">
            <li><a href="{{URL::to('/home')}}"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="{{URL::to('/communication/sms/group')}}">Group SMS</a></li>
        </ul>
    </section>
    <section class="content">
        @if(Session::has('success'))
        <div class="alert-success alert-auto-hide alert fade in" id="w0-success-0" style="opacity: 423.642;">
            <button aria-hidden="true" class="close" data-dismiss="alert" type="button">×</button>
            <h4><i class="icon fa fa-check"></i>{{ Session::get('success') }}</h4>
        </div>
        @elseif(Session::has('warning'))
        <div class="alert-warning alert-auto-hide alert fade in" id="w0-success-0" style="opacity: 423.642;">
            <button aria-hidden="true" class="close" data-dismiss="alert" type="button">×</button>
            <h4><i class="icon fa fa-check"></i>{{ Session::get('warning') }}</h4>
        </div>
        @endif
        <div class="panel panel-default">
            <div class="panel-body">
                <div>
                    <ul class="nav-tabs margin-bottom nav" id="">
                        <li @if($page == "teacher") class="active" @endif  id="#">
                        <a href="{{url('/communication/sms/group/teacher')}}">Teacher </a>
                        </li>
                        <li @if($page == "student") class="active" @endif  id="#">
                        <a href="{{url('/communication/sms/group/student')}}">Student</a>
                        </li>
                        <li @if($page == "stuff") class="active" @endif  id="#">
                        <a href="{{url('/communication/sms/group/stuff')}}">Staff</a>
                        </li>
                        <li @if($page == "parent") class="active" @endif  id="#">
                        <a href="{{url('/communication/sms/group/parent')}}">Parent</a>
                        </li>
                        <li @if($page == "custom-sms") class="active" @endif  id="#">
                            <a href="{{url('/communication/sms/group/custom-sms')}}">Custom SMS</a>
                        </li>

                    </ul>
                    <!-- page content div -->
                    @yield('page-content')
                </div>
            </div>
        </div>
    </section>
</div>

<!-- global modal -->
<div aria-hidden="true" aria-labelledby="esModalLabel" class="modal" id="globalModal" role="dialog" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="loader">
                    <div class="es-spinner">
                        <i class="fa fa-spinner fa-pulse fa-5x fa-fw">
                        </i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="exampleModalLongTitle">Important Notification</h4>
        </div>
        <div class="modal-body">
            <p>
                <div><b>As per attached (BTRC) guideline for SMS broadcast please flow the below instruction from now onward.</b></div>
                <br><br>
                <div>1. নিয়ম অনুযায়ী, টারগেটিং/এপিআই/নাম্বারলিস্ট এর সকল ধরণের প্রোমোশনাল/ গ্রিটিংস এসএমএস অবশ্যই বাংলায় ( ইউনিকোড) হতে হবে। 
                    শুধুমাত্র মেশিন জেনারেটেড এসএমএস / নোটিফিকেশন ( উদাহরণঃ এটিএম কার্ড OTP ) ,ছাড়া সব ধরণের এসএমএস অবশ্যই বাংলা ( ইউনিকোড)
                     ব্যবহার করতে আহবান এবং সতর্ক করা হচ্ছে। অন্যথায় একাউন্ট স্থগিত হবে এবং ইউজার/গ্রাহক/ক্লায়েন্ট এর দায়ভার বহন করবেন। ধন্যবাদ।</div>
                <br>
                <div>Dear User/Client, Greetings! As per regulations, all kinds of promotional/greetings SMS have to be in Bangla (Unicode) for
                     both Campaign and API. Except Only machine-generated SMS/notification (example: ATM card OTP etc.), all other SMS content 
                     must be in Bangla and all are requested to strictly follow this regulation. Otherwise, the SMS account will be blocked and 
                     the User/Client will bear the responsibility or any damage caused due to violation of this regulation. Thanks.</div>
                <br>
                <div>2. Content must have to vet from BTRC before broadcast and Share the vetting content to MNO's.</div>
                <br>
                <div>Please take immediate action and comply with BTRC guideline.</div>
                <br><br>
                <div class="text-warning"><a href="https://bangladeshsms.com/assets/BTRC_Letter_Regarding_SMS.pdf" target="_blank">
                    From_BTRC_Regarding_Circulation_of_SMS_in_Bengali_language.pdf</a></div>
                <br>
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Agree</button>
        </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{URL::asset('js/charts/chart.min.js')}}"></script>
<script src="{{URL::asset('js/tokenInput.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.alert-auto-hide').fadeTo(7500, 500, function () {
            $(this).slideUp('slow', function () {
                $(this).remove();
            });
        });

        console.log("HEllo");
        $('#exampleModalLong').modal('show');

            @yield('page-script')
    });
</script>

@endsection

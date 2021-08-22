
@extends('reports::layouts.report-layout')

<!-- page content -->
@section('page-content')
    <!-- grading scale -->
    <div class="col-md-12">
        <ul class="nav nav-pills">
            <li class="my-tab active"><a data-toggle="tab" href="#testimonial-en">Testimonial (en)</a></li>
            <li class="my-tab"><a data-toggle="tab" href="#testimonial-bn">Testimonial (bn)</a></li>
            <li class="my-tab"><a data-toggle="tab" href="#transfer_certificate">Transfer Certificate (TC)</a></li>
            <li class="my-tab"><a data-toggle="tab" href="#testimonial_nine_ten">Testimonial Class-9-10</a></li>
        </ul>
        {{--<hr/>--}}
        <br/>
        <div class="tab-content">
            {{--institute module--}}
            <div id="testimonial-en" class="tab-pane fade in active">
                @include('reports::pages.includes.testimonials')
            </div>
            {{--institute module--}}
            <div id="testimonial-bn" class="tab-pane fade in">
                @include('reports::pages.includes.testimonials-bn')
            </div>
            {{--role permission--}}
            <div id="transfer_certificate" class="tab-pane fade in">
                @include('reports::pages.includes.transfer-certificate')
            </div>

            <div id="testimonial_nine_ten" class="tab-pane fade in">
                @include('reports::pages.includes.class-section-testimonial')
            </div>

            {{-- data table container --}}
            <div id="certificate_container_row"></div>
        </div>
    </div>
@endsection

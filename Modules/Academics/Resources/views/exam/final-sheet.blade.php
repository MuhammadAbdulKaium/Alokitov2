@extends('layouts.master')

@section('styles')
    <style>
        th
        {
            vertical-align: bottom;
            text-align: center;
        }

        th span
        {
            -ms-writing-mode: tb-rl;
            -webkit-writing-mode: vertical-rl;
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            white-space: nowrap;
        }
    </style>
@endsection
@section('content')

    <div class="content-wrapper">
        <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Exam |<small>Mark Entry</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/academics/default/index">Academics</a></li>
                <li>SOP Setup</li>
                <li>Exam</li>
                <li class="active">Exam Mark Entry</li>
            </ul>
        </section>
        <section class="content">
            @if(Session::has('message'))
                <p class="alert alert-success alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                                                                                              style="text-decoration:none" data-dismiss="alert"
                                                                                              aria-label="close">&times;</a>{{ Session::get('message') }}</p>
            @elseif(Session::has('alert'))
                <p class="alert alert-warning alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
            @elseif(Session::has('errorMessage'))
                <p class="alert alert-danger alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
            @endif
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title" style="line-height: 40px"><i class="fa fa-tasks"></i> Exam Mark Entry </h3>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-bordered text-center">
                       <thead>
                       <tr>
                           <th rowspan="3" ><span>YEAR</span></th>
                           <th rowspan="1" colspan="3">Physical Fitness</th>
                           <th rowspan="1" colspan="7">Personal Traits</th>
                           <th rowspan="1" colspan="3">Academic</th>
                           <th rowspan="1" colspan="6">Co-Curricular</th>
                           <th rowspan="1" colspan="12">Extra-Curricular</th>
                           <th rowspan="3"><span>Monthly Fee</span></th>
                       </tr>
                       <tr>
                          <th rowspan="1">1</th>
                          <th rowspan="1">2</th>
                          <th rowspan="1">3</th>

                           <th rowspan="1">1</th>
                           <th rowspan="1">2</th>
                           <th rowspan="1">3</th>
                           <th rowspan="1">4</th>
                           <th rowspan="1">5</th>
                           <th rowspan="1">6</th>
                           <th rowspan="1">7</th>

                           <th rowspan="1">1</th>
                           <th rowspan="1">2</th>
                           <th rowspan="1">3</th>

                           <th rowspan="1">1</th>
                           <th rowspan="1">2</th>
                           <th rowspan="1">3</th>
                           <th rowspan="1">4</th>
                           <th rowspan="1">5</th>
                           <th rowspan="1">6</th>

                           <th rowspan="1">1</th>
                           <th rowspan="1">2</th>
                           <th rowspan="1">3</th>
                           <th rowspan="1">4</th>
                           <th rowspan="1">5</th>
                           <th rowspan="1">6</th>
                           <th rowspan="1">7</th>
                           <th rowspan="1">8</th>
                           <th rowspan="1">9</th>
                           <th rowspan="1">10</th>
                           <th rowspan="1">11</th>
                           <th rowspan="1">12</th>



                       </tr>
                       <tr >
                           <th  rowspan="1"><span>Height (Inch/cm)</span></th>
                           <th class='rotate' rowspan="1"><span>Weight(kgs/lbs)</span></th>
                           <th class='rotate' rowspan="1"><span>Obese(Kgs/lbs)</span></th>

                           <th class='rotate' rowspan="1"><span>Integrity</span></th>
                           <th class='rotate' rowspan="1"><span>Discipline</span></th>
                           <th class='rotate' rowspan="1"><span>Leadership</span></th>
                           <th class='rotate' rowspan="1"><span>Responsibility</span></th>
                           <th class='rotate' rowspan="1"><span>Self confidence</span></th>
                           <th class='rotate' rowspan="1"><span>Health and Hygiene</span></th>
                           <th class='rotate' rowspan="1"><span>Language Proficiency</span></th>

                           <th class='rotate' rowspan="1"><span>JSC Result</span></th>
                           <th class='rotate' rowspan="1"><span>SSC Result</span></th>
                           <th class='rotate' rowspan="1"><span>Last Exam Result</span></th>

                           <th class='rotate' rowspan="1"><span>Olympiad</span></th>
                           <th class='rotate' rowspan="1"><span>Debate(Bangal and English)</span></th>
                           <th class='rotate' rowspan="1"><span>Extempore(Bangal and English)</span></th>
                           <th class='rotate' rowspan="1"><span>Recitation(Bangal and English)</span></th>
                           <th class='rotate' rowspan="1"><span>Music</span></th>
                           <th class='rotate' rowspan="1"><span>Acting</span></th>

                           <th class='rotate' rowspan="1"><span>Swimming</span></th>
                           <th class='rotate' rowspan="1"><span>Squash</span></th>
                           <th class='rotate' rowspan="1"><span>Football</span></th>
                           <th class='rotate' rowspan="1"><span>Cricket</span></th>
                           <th class='rotate' rowspan="1"><span>Basketball</span></th>
                           <th class='rotate' rowspan="1"><span>Volleyball</span></th>
                           <th class='rotate' rowspan="1"><span>Lawn Tennis</span></th>
                           <th class='rotate' rowspan="1"><span>Indoor  Games</span></th>
                           <th class='rotate' rowspan="1"><span>Obstacle  Course</span></th>
                           <th class='rotate' rowspan="1"><span>Cross  Country</span></th>
                           <th class='rotate' rowspan="1"><span>Cycling</span></th>
                           <th class='rotate' rowspan="1"><span>Athletics</span></th>




                       </tr>
                       </thead>
                        <tbody>
                        <tr>
                            <td>2021</td>
                            <td>5</td>
                            <td>80</td>
                            <td>20</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12" id="std_list_container_row">




                </div>
            </div>
        </section>
    </div>
@endsection



{{-- Scripts --}}
@section('scripts')

@stop
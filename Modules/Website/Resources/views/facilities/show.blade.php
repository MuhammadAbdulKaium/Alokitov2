{{--@if($user->hasRole(['super-admin']) || $user->hasRole(['admin']))--}}

{{--    <link href="{{ URL::asset('css/jquery-ui.min.css') }}" rel="stylesheet" type="text/css"/>--}}

{{--    <div class="modal-dialog" >--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-header">--}}
{{--                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>--}}
{{--                <h4 class="modal-title">Facility</h4>--}}
{{--            </div>--}}
{{--            <div class="modal-body" style="overflow:auto">--}}
{{--                <table id="" class="table table-bordered table-hover table-striped">--}}
{{--                        <tr>--}}
{{--                            <th class="col-lg-4">Image</th>--}}
{{--                            <td><img height="80" src="/images/{{$facility->file}}" alt=""></td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <th class="col-lg-4">Title</th>--}}
{{--                            <td>{{$facility->name}}</td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <th class="col-lg-4">Description</th>--}}
{{--                            <td><textarea class="form-control">{{$facility->description}}</textarea></td>--}}
{{--                        </tr>--}}

{{--                </table>--}}
{{--            </div>--}}

{{--            <div class="modal-footer">--}}
{{--                <a data-dismiss="modal" class="btn btn-primary" type="button" href="{{ url('website/extra/edit', [$typeArray[0], $facility->id]) }}"  data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"> Edit </a>--}}
{{--                <a data-dismiss="modal" class="btn btn-default" type="button"> Close </a>--}}
{{--            </div>--}}

{{--        </div>--}}
{{--    </div>--}}

{{--    <script src="{{URL::asset('js/jquery-ui.min.js')}}" type="text/javascript"></script>--}}

{{--@else--}}
{{--    <h1>YOU DO NOT HAVE PERMISSION FOR THIS PAGE!</h1>--}}
{{--@endif--}}







@if($user->hasRole(['super-admin']) || $user->hasRole(['admin']))

    @extends('layouts.master')

@section('content')
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage |<small>Facility</small></h1>
            <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/academics/default/index">Website</a></li>
                <li class="active">Facility</li>
            </ul>
        </section>
        <section class="content">

            <style>
                .responsive {
                    text-align: center;
                }
                .gallery {
                    width: 100% !important;
                    height: 300px;
                    margin: 19px 0px;
                }

                .gallery img {
                    width: 100%;
                    height: 100% !important;
                }
                div.gallery {
                    border: 1px solid #ccc;
                }

                div.gallery:hover {
                    border: 1px solid #777;
                }

                div.gallery img {
                    width: 100%;
                    height: auto;
                }

                div.desc {
                    padding: 15px;
                    text-align: center;
                }

                * {
                    box-sizing: border-box;
                }

                .clearfix:after {
                    content: "";
                    display: table;
                    clear: both;
                }

                img {
                    float: left;
                    width:  100px;
                    height: 100px;
                    object-fit: cover;
                }
            </style>

            <body>

            <div>
                <h3 class="text-center">{{$facility->name}}
                    <a href="{{ url('website/extra/edit', [$typeArray[0], $facility->id]) }}"  class="btn btn-primary btn-xs" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg" data-content="update">Edit
                        <i class="fa fa-edit"></i>
                    </a>
                </h3>

            </div>

            <div>
                <a href="{{ url('website/extra/facility-image-add', $facility->id) }}"  class="btn btn-success btn-xs" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg" data-content="update">Add Image
                    <i class="fa fa-edit"></i>
                </a>
            </div>

            <div class="row">
                @if($photos[0] != "")
                    @foreach($photos as $key => $photo)
                        <div class="col-md-3">
                            <div class="responsive">
                                <div class="gallery">
                                    <a target="_blank" href="/images/{{$photo}}">
                                        <img src="/images/{{$photo}}">
                                    </a>
                                </div>
                                <a href="{{ url('website/extra/facility-image/delete', [$facility->id, $key]) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-content="delete"> Delete
                                    <i class="fa fa-trash-o"></i>
                                </a>
                            </div>

                        </div>
                    @endforeach
                @else
                    <h5 class="text-center">There Is No Photo</h5>
                @endif


                <div class="clearfix"></div>
            </div>

            <div>
                <h4>{{$facility->description}}</h4>
            </div>

            </body>

        </section>
    </div>

    <div class="modal"  id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="loader">
                        <div class="es-spinner">
                            <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@else
    <h1>YOU DO NOT HAVE PERMISSION FOR THIS PAGE!</h1>
@endif











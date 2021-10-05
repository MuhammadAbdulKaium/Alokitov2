@if($user->hasRole(['super-admin']) || $user->hasRole(['admin']))

    @extends('layouts.master')

@section('content')
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage |<small>Gallery</small></h1>
            <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/academics/default/index">Website</a></li>
                <li class="active">Gallery</li>
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
                    <h3 class="text-center">{{$album->name}}</h3>
                </div>
                <div>
                    <a href="{{ url('website/image/edit', $id) }}"  class="btn btn-primary btn-xs" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg" data-content="update">Edit
                        <i class="fa fa-edit"></i>
                    </a>
                </div>

                <div class="row">
                    @if(count($photos) > 0)
                        @foreach($photos as $key => $photo)
                    <div class="col-md-3">
                        <div class="responsive">
                            <div class="gallery">
                                <a target="_blank" href="/images/{{$photo}}">
                                    <img src="/images/{{$photo}}">
                                </a>
                            </div>
                            <a href="{{ url('website/image/delete', [$id, $key]) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-content="delete"> Delete
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </div>
                    </div>
                        @endforeach
                    @else
                        <h1>This Album Is Empty</h1>
                    @endif

                    <div class="clearfix"></div>
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





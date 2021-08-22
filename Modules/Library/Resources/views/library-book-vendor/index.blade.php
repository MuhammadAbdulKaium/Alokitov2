@extends('library::layouts.master')
@section('section-title')
        <h1>
            <i class="fa fa-th-list"></i> Manage |<small>Book Vendor </small>        </h1>
        <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/library/default/index">Library</a></li>
            <li class="active">Book Vendor</li>
        </ul>


@endsection
<!-- page content -->
@section('page-content')
    <!-- grading scale -->
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-plus-square"></i> Create Book Vendor
            </h3>
        </div>


        <form id="bookVendor" action="/library/library-book-vendor/store" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="book_vendor_id" @if(!empty($bookVendorProfile))  value="{{$bookVendorProfile->id}}" @endif>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group field-libraryvendormaster-lvm_name required">
                                <label class="control-label" for="libraryvendormaster-lvm_name">Name</label>
                                <input id="libraryvendormaster-lvm_name" class="form-control" required name="name" maxlength="150" aria-required="true" type="text"  @if(!empty($bookVendorProfile->name))  value="{{$bookVendorProfile->name}}" @endif >
                                <p class="help-block help-block-error"></p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group field-libraryvendormaster-lvm_address required">
                                <label class="control-label" for="libraryvendormaster-lvm_address">Address</label>
                                <textarea id="libraryvendormaster-lvm_address" class="form-control" required name="address" rows="2"> @if(!empty($bookVendorProfile->address)) {{$bookVendorProfile->address}} @endif</textarea>
                                <p class="help-block help-block-error"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group field-libraryvendormaster-lvm_contact_no required">
                                <label class="control-label" for="libraryvendormaster-lvm_contact_no required">Contact No</label>
                                <input id="libraryvendormaster-lvm_contact_no" required class="form-control" name="contact_no" maxlength="35" type="text"  @if(!empty($bookVendorProfile->contact_no))  value="{{$bookVendorProfile->contact_no}}" @endif>
                                <p class="help-block help-block-error"></p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group field-libraryvendormaster-lvm_email required">
                                <label class="control-label" for="libraryvendormaster-lvm_email required">Email Id</label>
                                <input id="libraryvendormaster-lvm_email"  required class="form-control" name="email" maxlength="65" type="text" @if(!empty($bookVendorProfile->email))  value="{{$bookVendorProfile->email}}" @endif >
                                <p class="help-block help-block-error"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-create">Create</button>	<a class="btn btn-default btn-create" href="/library/library-vendor-master/index">Cancel</a>
                </div>
                <!-- /.box-footer-->
            </form>
        </div>
        <div class="box box-solid">
            @if($bookVendors->count())
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-search"></i> View Vendors
                </h3>
            </div>
            <div class="box-body table-responsive">
                <div id="p0" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="10000">
                    <div id="w2" class="grid-view">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><a href="/library/library-vendor-master/index?sort=lvm_name" data-sort="lvm_name">Name</a></th>
                                <th><a href="/library/library-vendor-master/index?sort=lvm_address" data-sort="lvm_address">Address</a></th>
                                <th><a href="/library/library-vendor-master/index?sort=lvm_contact_no" data-sort="lvm_contact_no">Contact No</a></th>
                                <th><a href="/library/library-vendor-master/index?sort=lvm_email" data-sort="lvm_email">Email Id</a></th>
                                <th class="action-column">&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($bookVendors as $bookVendor)
                            <tr data-key="1">
                                <td>{{$bookVendor->id}}</td>
                                <td>{{$bookVendor->name}}</td>
                                <td>{{$bookVendor->address}}</td>
                                <td>{{$bookVendor->contact_no}}</td>
                                <td><a href="mailto:">{{$bookVendor->email}}</a></td>
                               <td>
                                   <a href="{{URL::to("/library/library-book-vendor/edit",$bookVendor->id)}}"  class="btn btn-primary btn-xs" title="Update" aria-label="Update" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a>
                                   <a  id="{{$bookVendor->id}}" class="btn btn-danger btn-xs delete_book_vendor" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><span class="glyphicon glyphicon-trash"></span></a>

                               </td>

                            </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @else

                <div class="container" style="margin-top: 20px">
                    <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642; margin-top: 20px">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="fa fa-warning"></i></i> No result found. </h5>
                    </div>
                </div>

                @endif

            <!-- /.box-body -->
        </div>

@endsection

@section('page-script')

    // Book Category ajax request
    $('.delete_book_vendor').click(function(){
        var tr = $(this).closest('tr'),
            del_id = $(this).attr('id');

        $.ajax({
            url: "/library/library-book-vendor/delete/"+ del_id,
            type: 'GET',
            cache: false,
            success:function(result){
                tr.fadeOut(1000, function(){
                    $(this).remove();
                });
            }
        });
    });



@endsection


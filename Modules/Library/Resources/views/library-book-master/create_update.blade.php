@extends('library::layouts.master')
@section('section-title')
        <h1>
            <i class="fa fa-plus-square"></i> @if(!empty($bookProfile)) Update Book @else Add Book @endif        </h1>
        <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/library/default/index">Library</a></li>
            <li><a href="/library/library-book-master/index">Manage Books</a></li>
            <li class="active">Add Book</li>
        </ul>


@endsection
<!-- page content -->
@section('page-content')
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-plus-square"></i> @if(!empty($bookProfile)) Update Book @else Create Book @endif
            </h3>
        </div>

        <form id="book" action="/library/library-book/store" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="book_id" @if(!empty($bookProfile->id)) value="{{$bookProfile->id}}" @endif >

            <div class="box-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group field-librarybookdetails-lbd_title required">
                                <label class="control-label" for="librarybookdetails-lbd_title">Book Name</label>
                                <input id="librarybookdetails-lbd_title" class="form-control" name="name" maxlength="150" aria-required="true" type="text" @if(!empty($bookProfile->name)) value="{{$bookProfile->name}}" @endif>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group field-librarybookdetails-lbd_book_category required">
                                <label class="control-label" for="librarybookdetails-lbd_book_category">Book Category</label>
                                <select id="librarybookdetails-lbd_book_category" class="form-control" name="book_category" aria-required="true">
                                    <option value="">--- Select Category ---</option>
                                    @if($bookCategorys->count()>0)
                                            @foreach($bookCategorys as $bookCategory)
                                                @if(!empty($bookProfile->book_category_id))
                                                    @if($bookCategory->id==$bookProfile->book_category_id)
                                                        <option selected="selected" value="{{$bookCategory->id}}" >{{$bookCategory->name}}</option>
                                                        @else
                                                             <option value="{{$bookCategory->id}}" >{{$bookCategory->name}}</option>
                                                        @endif
                                                @else
                                               <option value="{{$bookCategory->id}}" >{{$bookCategory->name}}</option>
                                                @endif
                                        @endforeach
                                        @endif
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group field-librarybookdetails-lbd_book_type required">
                                <label class="control-label">Book Type</label>
                                <input name="LibraryBookDetails[lbd_book_type]" value="" type="hidden">
                                <div id="librarybookdetails-lbd_book_type" aria-required="true">
                                    <label><input name="book_type" value="0" type="radio" @if(!empty($bookProfile->book_type)==0) checked   @endif > General</label>
                                    <label><input name="book_type" value="1" type="radio" @if(!empty($bookProfile->book_type)==1) checked   @endif> Barcoded</label>
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group field-librarybookdetails-lbd_subtitle">
                                <label class="control-label" for="librarybookdetails-lbd_subtitle">Subtitle</label>
                                <input id="librarybookdetails-lbd_subtitle" class="form-control" name="subtitle" maxlength="100" type="text" @if(!empty($bookProfile->subtitle)) value="{{$bookProfile->subtitle}}" @endif>
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group field-librarybookdetails-lbd_isbn_no">
                                <label class="control-label" for="librarybookdetails-lbd_isbn_no">ISBN No.</label>
                                <input id="librarybookdetails-lbd_isbn_no" class="form-control" name="isbn_no" maxlength="13" type="text" @if(!empty($bookProfile->isbn_no)) value="{{$bookProfile->isbn_no}}" @endif>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group field-librarybookdetails-lbd_author">
                                <label class="control-label" for="librarybookdetails-lbd_author">Author</label>
                                <input id="librarybookdetails-lbd_author" class="form-control" name="author" maxlength="70" type="text" @if(!empty($bookProfile->author)) value="{{$bookProfile->author}}" @endif>
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group field-librarybookdetails-lbd_cupboard">
                                <label class="control-label" for="librarybookdetails-lbd_cupboard">Book Shelf</label>
                                <select id="libraryBookSelfId" class="form-control" name="book_shelf">
                                    <option value="">--- Select Category ---</option>
                                    @if($bookShelfs->count()>0)
                                        @foreach($bookShelfs as $bookShelf)
                                            @if(!empty($bookProfile->book_shelf_id))

                                            @if($bookShelf->id==$bookProfile->book_shelf_id)
                                                 <option selected="selected" value="{{$bookShelf->id}}">{{$bookShelf->name}}</option>

                                            @endif
                                                <option value="{{$bookShelf->id}}">{{$bookShelf->name}}</option>

                                                @else

                                                <option value="{{$bookShelf->id}}">{{$bookShelf->name}}</option>

                                            @endif

                                        @endforeach

                                    @endif

                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            @if(!empty($bookProfile->cup_board_shelf_id))
                            <input type="hidden" value="{{$bookProfile->cup_board_shelf_id}}" id="cup_book_id">
                            @endif
                            <div class="form-group field-librarybookdetails-lbd_cupboard_self">
                                <label class="control-label" for="librarybookdetails-lbd_cupboard_self">Cupboard Self</label>
                                <select id="librarybookd_cupboard_self" class="form-control" name="cup_board_shelf">
                                    <option value="" disabled="true" selected="true">--- Select Cup Board Shelf ---</option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group field-librarybookdetails-lbd_edition">
                                <label class="control-label" for="librarybookdetails-lbd_edition">Edition</label>
                                <input id="librarybookdetails-lbd_edition" class="form-control" name="edition" maxlength="15" type="text" @if(!empty($bookProfile->edition)) value="{{$bookProfile->edition}}" @endif>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group field-librarybookdetails-lbd_publisher">
                                <label class="control-label" for="librarybookdetails-lbd_publisher">Publisher</label>
                                <input id="librarybookdetails-lbd_publisher" class="form-control" name="publisher" maxlength="15" type="text" @if(!empty($bookProfile->publisher)) value="{{$bookProfile->publisher}}" @endif>
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group field-librarybookdetails-lbd_book_cost">
                                <label class="control-label" for="librarybookdetails-lbd_book_cost">Book Cost</label>
                                <input id="librarybookdetails-lbd_book_cost" class="form-control" name="book_cost" maxlength="50" type="text" @if(!empty($bookProfile->book_cost)) value="{{$bookProfile->book_cost}}" @endif>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group field-librarybookdetails-lbd_book_vendor">
                                <label class="control-label" for="librarybookdetails-lbd_book_vendor">Book Vendor</label>
                                <select id="librarybookdetails-lbd_book_vendor" class="form-control" name="book_vendor">
                                    <option value="">--- Select Vendor ---</option>
                                    @if($bookVendors->count()>0)
                                        @foreach($bookVendors as $bookVendor)
                                            @if(!empty($bookProfile->book_vendor_id))
                                                @if($bookVendor->id==$bookProfile->book_vendor_id)

                                                <option selected="selected" value="{{$bookVendor->id}}">{{$bookVendor->name}}</option>

                                                @else
                                            <option value="{{$bookVendor->id}}">{{$bookVendor->name}}</option>
                                                @endif

                                            @else
                                                <option value="{{$bookVendor->id}}">{{$bookVendor->name}}</option>
                                            @endif
                                        @endforeach

                                    @endif
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        @if(empty($bookProfile))
                            <div class="col-sm-3">
                                <div class="form-group field-librarybookdetails-lbd_book_copy required">
                                    <label class="control-label" for="librarybookdetails-lbd_book_copy">Copy</label>
                                    <input id="librarybookdetails-lbd_book_copy" class="form-control" name="copy" maxlength="3" aria-required="true" type="text">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        @endif

                        <div class="col-sm-3">
                            <div class="form-group field-librarybookdetails-lbd_remarks">
                                <label class="control-label" for="librarybookdetails-lbd_remarks">Remarks</label>
                                <textarea id="librarybookdetails-lbd_remarks" class="form-control" name="remark" rows="2">@if(!empty($bookProfile->remark)) {{$bookProfile->remark}} @endif</textarea>
                                <div class="help-block"></div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-create">Create</button>
                    <a class="btn btn-default btn-create" href="/library/library-book-master/index">Cancel</a>
                </div>
            </form>
        </div>




@endsection

@section('page-script')

    $("#libraryBookSelfId").on('change',function(){
        var book_shelf_id= $(this).val();
        var op="";

    $.ajax({

    url: '/library/find/library-cupboard-shelf/'+book_shelf_id,
    type: 'GET',
    cache: false,
    success:function(data){
            op+='<option value="0" selected disabled>--- Select Cup Board Shelf ---</option>';
                for(var i=0;i<data.length;i++){
            // console.log(data[i].level_name);
                op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
        }
        // set value to the librarybookd_cupboard_self
        $('#librarybookd_cupboard_self').html("");
        $('#librarybookd_cupboard_self').append(op);
    },

    error:function(data){
        alert(JSON.stringify(data));
     }
    });
    })


    @if(!empty($bookProfile))
            var book_shelf_id= $("#libraryBookSelfId").val();
            var cup_book_id=$("#cup_book_id").val();
            {{--alert(cup_book_id);--}}
            var op="";
            $.ajax({

                url: '/library/find/library-cupboard-shelf/'+book_shelf_id,
                type: 'GET',
                cache: false,
                success:function(data){
                    op+='<option value="0" selected disabled>--- Select Cup Board Shelf ---</option>';
                    for(var i=0;i<data.length;i++){
                        // console.log(data[i].level_name);
                    if((data[i].id)==cup_book_id) {
                         op+='<option selected="selected" value="'+data[i].id+'">'+data[i].name+'</option>';

                        }
                        op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                    }
                    // set value to the librarybookd_cupboard_self
                    $('#librarybookd_cupboard_self').html("");
                    $('#librarybookd_cupboard_self').append(op);
                },

                error:function(data){
                    alert(JSON.stringify(data));
                }
            });

    @endif



@endsection


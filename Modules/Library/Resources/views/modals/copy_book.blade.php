<div class="modal-dialog">
    <div class="modal-content">
        <form id="add" action="/library/library-book-master/add-more-copy/store" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="book_id" value="{{$bookId}}">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <p>
                </p><h4>
                    <i class="fa fa-plus-square"></i>
                    Add More Copy  </h4>
                <p></p>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group field-librarybookdetails-add_copy required">
                            <label class="control-label" for="librarybookdetails-add_copy">No. of Copies</label>
                            <input id="librarybookdetails-add_copy" class="form-control" name="copy" aria-required="true" type="text">

                            <div class="help-block"></div>
                        </div>    	</div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success pull-left">Add</button>    <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
            </div>

        </form>
</div>
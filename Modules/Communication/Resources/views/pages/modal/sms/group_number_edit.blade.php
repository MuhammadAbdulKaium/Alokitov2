
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Group Update View </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <form  method="post" id="add-group-form" action="/communication/group/number/update">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="group_id" value="{{$groupProfile->id}}">
                            <div class="form-group">
                                <label for="group_name">Group Name :</label>
                                <input type="text" name="group_name" value="{{$groupProfile->group_name}}" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="pwd">Add Mobile No:</label>
                                <div class="input-group control-group after-add-field" style="width: 100%">
                                    <div class="control-group input-group" style="margin-top:10px">
                                        <input class="form-control " type="number" name="phone_number[]" placeholder="add mobile number">
                                        <div class="input-group-btn">
                                            <button class="btn btn-success add-field" type="button"><i class="glyphicon glyphicon-plus"></i> </button>
                                        </div>
                                    </div>
                                </div>
                                @if(!empty($groupNumberList) && ($groupNumberList->count()>0))
                                    @foreach($groupNumberList as $number)
                                        <input type="hidden" value="{{$number->id}}" name="all_old_number_id[]">
                                        <div class="input-group control-group" style="width: 100%">
                                            <div class="control-group input-group" style="margin-top:10px">
                                                <input type="hidden" value="{{$number->id}}" name="old_number_id[]">
                                                <input class="form-control "  type="number" name="old_number[]" value="{{$number->mobile_number}}" placeholder="add mobile number">
                                                <div class="input-group-btn">
                                                    <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                            </div>

                            <button type="submit" class="btn btn-success">Submit</button>
                        </form>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>




        <!-- Copy Fields-These are the fields which we get through jquery and then add after the above input,-->
        <div class="get-fields hide">
            <div class="control-group input-group" style="margin-top:10px">
                <input class="form-control " type="number"  name="phone_number[]" placeholder="add mobile number">
                <div class="input-group-btn">
                    <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> </button>
                </div>
            </div>
        </div>



        <script>

            //here first get the contents of the div with name class get-fields and add it to after "after-add-field" div class.
            $(".add-field").click(function(){
                var html = $(".get-fields").html();
                $(".after-add-field").after(html);
            });

            //here it will remove the current value of the remove button which has been pressed
            $("body").on("click",".remove",function(){
                $(this).parents(".control-group").remove();
            });




        </script>



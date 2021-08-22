<form action="{{url('/setting/admin/campus/store')}}" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}" />
    <input type="hidden" name="institute" value="{{$instituteProfile->id}}" />
    {{--modal header--}}
    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title"><i class="fa fa-university"></i> Add Campus <b>( {{$instituteProfile->institute_name}} )</b></h4>
    </div>
    {{--modal-body--}}
    <div class="modal-body">

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group required">
                    <label for="name">Name:</label>
                    <input id="name" type="text" class="form-control" name="name" required />
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="campus_code">Alias:</label>
                    <input id="campus_code" type="text" class="form-control" name="campus_code" required />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="country">Country</label>
                    <select id="country" class="form-control country" name="country" required>
                        <option value="" selected disabled>--- Select Country ---</option>
                        {{--checking Country list--}}
                        @if($countryList->count()>0)
                            {{--state list looping--}}
                            @foreach($countryList as $country)
                                <option value="{{$country->id}}">{{$country->name}}</option>
                            @endforeach
                        @endif
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="state">State</label>
                    <select id="state" class="form-control state" name="state" required>
                        <option value="" selected disabled>--- Select State ---</option>
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="city">City</label>
                    <select id="city" class="form-control city" name="city" required>
                        <option value="" selected disabled>--- Select City ---</option>
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea id="address" type="text" class="form-control" name="address" required></textarea>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="admin-name">Campus Admin Name:</label>
                    <input id="admin-name" type="text" class="form-control" name="admin-name" required />
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="admin-email">Campus Admin Email:</label>
                    <input id="admin-email" type="email" class="form-control" name="email" required />
                </div>
            </div>
        </div>
    </div>

    {{--modal-footer--}}
    <div class="modal-footer">
        <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
        <button class="btn btn-primary pull-left" type="submit">Submit</button>
    </div>
</form>

<script>
    $(document).ready(function(){

        // request for state list using country id
        jQuery(document).on('change','.country',function(){
            // get country id
            var country_id = $(this).val();
            var op="";

            $.ajax({
                url: "{{ url('/setting/find/state/') }}",
                type: 'GET',
                cache: false,
                data: {'id': country_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    // console.log(level_id);
                },
                success:function(data){
                    op+='<option value="" selected disabled>--- Select State ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                    }
                    // set value to the state
                    $('.state').html("");
                    $('.state').append(op);
                    // clear and set value to the city
                    $('.city').html("");
                    $('.city').append('<option value="" selected disabled>--- Select City ---</option>');
                },
                error:function(){
                    //
                }
            });
        });

        // request for city list using state id
        jQuery(document).on('change','.state',function(){
            // get state id
            var state_id = $(this).val();
            var op="";

            $.ajax({
                url: "{{ url('/setting/find/city/') }}",
                type: 'GET',
                cache: false,
                data: {'id': state_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    // console.log(level_id);
                },
                success:function(data){
                    op+='<option value="" selected disabled>--- Select City ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                    }
                    // set value to the academic batch
                    $('.city').html("");
                    $('.city').append(op);
                },
                error:function(){
                    //
                }
            });
        });
    });
</script>
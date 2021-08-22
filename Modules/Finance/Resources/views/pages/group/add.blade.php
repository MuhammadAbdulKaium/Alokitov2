@extends('finance::layouts.master')
@section('section-title')

    <h1>
        <i class="fa fa-search"></i>Finance
    </h1>
    <ul class="breadcrumb">
        <li>
            <a href="/">
                <i class="fa fa-home"></i>Home
            </a>
        </li>
        <li>
            <a href="/library/default/index">Finacne</a>
        </li>
        <li class="active">Manage Account</li>
    </ul>

@endsection

<!-- page content -->
@section('page-content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <!-- ./col -->
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Create Group</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @if(Session::has('message'))
                            <div class="alert alert-success" role="alert">
                                {{ Session::get('message') }}</div>
                        @endif
                        <div class="groups add form">
                            <form action="{{URL::to('/finance/accounts/groups/store')}}" method="post" accept-charset="utf-8">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class='row'>
                                    <div class='col-xs-5'>
                                        <div class="form-group">
                                            <label for="parent_id">Parent Group</label><br>
                                            {{--<select name="parent_id" class="form-control select2-box" id="GroupParentId" onchange="getNumber()">--}}

                                                {{ Form::select('parent_id', $parentsGroups, null, ['class' => 'form-control','onclick' => 'getNumber()','id'=>'GroupParentId']) }}
                                            {{--</select>--}}
                                        </div>
                                    </div>
                                    <div class='col-xs-2'>
                                        <div class="form-group">
                                            <label for="code">Group Code</label>
                                            <input type="text" required name="code" value="" class="form-control" id="g_code" />
                                        </div>
                                    </div>
                                    <div class='col-xs-5'>
                                        <div class="form-group">
                                            <label for="name">Group Name</label>
                                            <input type="text" required name="name" value="" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group required" id="AffectsGross">
                                    <label for="affects_gross">Affects</label>
                                    <br>
                                    <input type="radio" name="affects_gross" value="1" checked="checked" id="affects_gross" style="margin:10px" /> Gross Profit & Loss
                                    <br>
                                    <input type="radio" name="affects_gross" value="0" id="affects_gross" style="margin:10px" /> Net Profit & Loss <span class="help-block">Note: Changes to whether it affects Gross or Net Profit & Loss is reflected in final Profit & Loss statement.</span></div>
                                <div class="form-group">
                                    <input type="submit" name="submit" value="Submit" class="btn btn-primary pull-right" />
                                    <span class="link-pad"></span><a href="https://otsglobal.org/accountant/accounts/index" class="btn btn-default pull-right" style="margin-right: 5px;">Cancel</a></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
    </section>
    <!-- /.content -->
@endsection @section('page-script')

    <script>
        $(".select2-box").select2();
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            /**
             * On changing the parent group select box check whether the selected value
             * should show the "Affects Gross Profit/Loss Calculations".
             */
            $('#GroupParentId').change(function() {
                if ($(this).val() == '3' || $(this).val() == '4') {
                    $('#AffectsGross').show();
                } else {
                    $('#AffectsGross').hide();
                }
            });
            $('#GroupParentId').trigger('change');
            $("#GroupParentId").select2({width:'100%'});


        });

//        function getNumber(x) {
//            var id = $("#GroupParentId option:selected").val()
//            $.ajax({
//                type:"POST",
//                url: "https://otsglobal.org/accountant/" + "groups/getNextCode",
//                data: { id }
//            }).done(function(msg){
//                console.log(msg);
//                $('#g_code').val(msg);
//            });
//
//        }
    </script>

@endsection
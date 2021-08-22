
@extends('admin::layouts.master')

@section('styles')
    <!-- DataTables -->
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{ Module::asset('admin:css/style.css') }}" />
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage | <small>Subscription</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">Subscription Management</a></li>
                <li class="active">Institute Subscription Payment List</li>
            </ul>
        </section>
        <section class="content">
            @if(Session::has('success'))
                <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                </div>
            @elseif(Session::has('alert'))
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('alert') }} </h4>
                </div>
            @elseif(Session::has('warning'))
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
                </div>
            @endif

            <div class="panel panel-default">
                <div class="panel-body">
                    <div>
                        <ul id="assessmentNav" class="nav-tabs margin-bottom nav">
                            <li @if($page == "bill-info") class="active" @endif id="tab-setup"><a href="/admin/bills/bill-info">Billing Info</a></li>
                            <li @if($page == "manage-bill") class="active" @endif id="tab-assessment"><a href="/admin/bills/manage-bill">Bill Management</a></li>
                            <li @if($page == "subscription-management") class="active" @endif id="tab-assessment"><a href="/admin/bills/subscription-management">Subscription Management</a></li>
                            <li @if($page == "bill-reports") class="active" @endif id="tab-reportcard"><a href="/admin/bills/bill-reports">Reports</a></li>
                        </ul>
                        <!-- page content div -->
                        @yield('page-content')
                    </div>
                </div>
                <div class="box box-solid">
                    <div class="et">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-search"></i> Institute Subscription Payment List</h3>
                            <!--<div class="box-tools">
                                <a class="btn btn-success btn-sm" href="{{url('admin/billing/info/create')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">
                                    <i class="fa fa-plus-square"></i> Add Billing Info
                                </a>
                            </div> -->
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive {{ $page }}">
                                    <form id="subscription_management_transaction" method="POST" action="{{'/admin/bills/subscription-management'}}">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}" />
                                        <table id="example1" class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th scope="col" width="2%">SL</th>
                                                    <th scope="col" class="text-center" width="2%"></th>
                                                    <th scope="col" width="10%">Institute name</th>
                                                    <th scope="col" colspan="21" width="86%">
                                                        <table class="table table-bordered" width="100%">
                                                            <tr>
                                                                <th scope="col" width="2%"></th>
                                                                <th scope="col" width="9%">Campus name</th>
                                                                <th scope="col" width="5%" class="text-center">Deposited</th>
                                                                <th scope="col" width="3%" class="text-center">year</th>
                                                                <th scope="col" width="5%" class="text-center">Month</th>
                                                                <th scope="col" width="4%" class="text-center">Students</th>
                                                                <th scope="col" width="3%" class="text-center">Rate</th>
                                                                <th scope="col" width="5%" class="text-center">Amount</th>
                                                                <th scope="col" width="5%" class="text-center">Accepted Amount</th>
                                                                <th scope="col" width="5%" class="text-center">SMS Amount</th>
                                                                <th scope="col" width="5%" class="text-center">Accepted SMS Amount</th>
                                                                <th scope="col" width="6%">Old Dues</th>
                                                                <th scope="col" width="5%" class="text-center">Month Total</th>
                                                                <th scope="col" width="6%" class="text-center">Paid Amount</th>
                                                                <th scope="col" width="5%" class="text-center">New Dues</th>
                                                                <th scope="col" width="5%" class="text-center">Paid On</th>
                                                                <th scope="col" width="5%" class="text-center">Status</th>
                                                                <th scope="col" width="4%" class="text-center">SMS</th>
                                                                <th scope="col" width="4%" class="text-center">E-mail</th>
                                                                <th scope="col" width="4%" class="text-center">Invoice</th>
                                                                <th scope="col" width="5%" class="text-center">Details</th>
                                                            </tr>
                                                        </table>
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @php $x = 1; @endphp
                                                @foreach($instituteList as $inst)
                                                    @if($allBillingInfoByCurrMonYrs->where('institute_id', $inst->id)->first())
                                                        <tr @if(($x%2) != 0) class="form sm-odd" @else class="form" @endif>
                                                            <td width="2%">{{ $x }}</td>

                                                            <td width="2%">
                                                                @php 
                                                                    $imgSrc = url('/').'/assets/users/images/'.$inst->logo;
                                                                @endphp
        
                                                                @if($imgSrc)
                                                                    <img src="{{ $imgSrc }}" class="img-fluid" alt="&nbsp;" />
                                                                @else

                                                                @endif
                                                            </td>

                                                            <th scope="row" width="10%">{{$inst->institute_name}}</th>
                                                            <td colspan="21" width="86%">
                                                                <table class="table table-bordered table-responsive table-hover" width="100%">
                                                                    @php $y = 1; @endphp
                                                                    @foreach($inst->campus() as $cam)
                                                                        @foreach($allBillingInfoByCurrMonYrs as $key=>$value)
                                                                            @if(($inst->id===$value->institute_id) && ($cam->id===$value->campus_id))
                                                                                <tr class="subform">
                                                                                    @if(array_key_exists($cam->id, $allBillingInfoArrayListByCampus)==TRUE)
                                                                                        @php $billingInfoByCampus = $allBillingInfoArrayListByCampus[$cam->id]; @endphp
                                                                                        <td width="2%">
                                                                                            <div class="form-check">
                                                                                                <label for="chkbox_{{ $x }}_{{ $y }}" style="display:inline;"></label>
                                                                                                <input type="checkbox" class="form-check-input chkbox" id="chkbox_{{ $x }}_{{ $y }}" name="chkbox[{{ $x }}][{{ $y }}]" value={{ $value->subscriptionManagementTransaction->id }} />
                                                                                            </div>
                                                                                        </td>

                                                                                        <td width="9%">
                                                                                            {{ $cam->name }} <br />
                                                                                            <em>(Created at: {{ date('d-m-Y', strtotime($cam->created_at)) }})</em>
                                                                                        </td>

                                                                                        <td width="5%" class="text-center">                                                                                                                                                                                                                                                                                                            
                                                                                            @if($billingInfoByCampus['deposited'])
                                                                                                {{ $billingInfoByCampus['deposited'] }}
                                                                                            @else
                                                                                                -
                                                                                            @endif
                                                                                        </td>

                                                                                        <td width="3%" class="text-center">
                                                                                            @if($billingInfoByCampus['year'])
                                                                                                {{ $billingInfoByCampus['year'] }} 
                                                                                            @else
                                                                                                -
                                                                                            @endif  
                                                                                        </td>

                                                                                        <td width="5%" class="text-center">
                                                                                            @if($billingInfoByCampus['month'])
                                                                                                {{ $billingInfoByCampus['month'] }}  
                                                                                            @else
                                                                                                -
                                                                                            @endif 
                                                                                        </td>

                                                                                        <td width="4%" class="text-center">
                                                                                            @if($cam->student->count())
                                                                                                {{ $cam->student->count() }}
                                                                                            @else
                                                                                                -
                                                                                            @endif
                                                                                        </td>

                                                                                        <td width="3%" class="text-center">
                                                                                            @if($value->rate_per_student)
                                                                                                {{ $value->rate_per_student }}
                                                                                            @else
                                                                                                -
                                                                                            @endif
                                                                                            
                                                                                        </td>

                                                                                        <td width="5%" class="text-center">
                                                                                            @if($value->total_amount)
                                                                                                {{ $value->total_amount }}
                                                                                            @else
                                                                                                -
                                                                                            @endif
                                                                                            
                                                                                        </td>

                                                                                        <td width="5%" class="text-center">
                                                                                            @if($value->accepted_amount)
                                                                                                {{ $value->accepted_amount }}
                                                                                            @else
                                                                                                -
                                                                                            @endif
                                                                                            
                                                                                        </td>

                                                                                        <td width="5%" class="text-center">
                                                                                            @if($value->total_sms_price)
                                                                                                {{ $value->total_sms_price }}
                                                                                            @else
                                                                                                -
                                                                                            @endif
                                                                                            
                                                                                        </td>

                                                                                        <td width="5%" class="text-center">
                                                                                            @if($value->accepted_sms_price)
                                                                                                {{ $value->accepted_sms_price }}
                                                                                            @else
                                                                                                -
                                                                                            @endif
                                                                                            
                                                                                        </td>

                                                                                        <td width="6%" class="text-center old_dues">
                                                                                            @if($value->subscriptionManagementTransaction->old_dues)
                                                                                                <div class="form-group">
                                                                                                    <label for="olddues_{{ $value->id }}" style="display:inline;"></label>
                                                                                                    <input id="olddues_{{ $value->id }}" type="number" placeholder="00.00" class="form-control olddues" name="olddues[{{ $x }}][{{ $y }}]" value="{{ $value->subscriptionManagementTransaction->old_dues }}" readonly />
                                                                                                </div>
                                                                                            @else
                                                                                                <div class="form-group">
                                                                                                    <label for="olddues_{{ $value->id }}" style="display:inline;"></label>
                                                                                                    <input id="olddues_{{ $value->id }}" type="number" placeholder="00.00" class="form-control olddues" name="olddues[{{ $x }}][{{ $y }}]" value="" readonly />
                                                                                                </div>
                                                                                            @endif
                                                                                            
                                                                                        </td>

                                                                                        <td width="5%" class="text-center monthly_total_charge">
                                                                                            @if($value->subscriptionManagementTransaction->monthly_total_charge)
                                                                                                {{ $value->subscriptionManagementTransaction->monthly_total_charge }}
                                                                                            @else
                                                                                                -
                                                                                            @endif
                                                                                            
                                                                                        </td>

                                                                                        <td width="6%" class="text-center">
                                                                                            @if($value->subscriptionManagementTransaction->paid_amount)
                                                                                                <div class="form-group">
                                                                                                    <label for="paidamount_{{ $value->id }}" style="display:inline;"></label>
                                                                                                    <input id="paidamount_{{ $value->id }}" type="number" placeholder="00.00" class="form-control paidamount" name="paidamount[{{ $x }}][{{ $y }}]" value="{{ $value->subscriptionManagementTransaction->paid_amount }}" readonly />
                                                                                                </div>
                                                                                            @else
                                                                                                <div class="form-group">
                                                                                                    <label for="paidamount_{{ $value->id }}" style="display:inline;"></label>
                                                                                                    <input id="paidamount_{{ $value->id }}" type="number" placeholder="00.00" class="form-control paidamount" name="paidamount[{{ $x }}][{{ $y }}]" value="" readonly />
                                                                                                </div>
                                                                                            @endif
                                                                                            
                                                                                        </td>

                                                                                        <td width="5%" class="text-center new_dues">
                                                                                            @if($value->subscriptionManagementTransaction->new_dues)
                                                                                                {{ $value->subscriptionManagementTransaction->new_dues }}
                                                                                            @else
                                                                                                -
                                                                                            @endif
                                                                                            
                                                                                        </td>

                                                                                        <td width="5%" class="text-center paid_on">
                                                                                            @if($value->subscriptionManagementTransaction->paid_on)
                                                                                                {{ date("d M y", strtotime($value->subscriptionManagementTransaction->paid_on)) }}
                                                                                            @else
                                                                                                -
                                                                                            @endif
                                                                                            
                                                                                        </td>

                                                                                        <td width="5%" class="text-center status">
                                                                                            @if($value->subscriptionManagementTransaction->status)
                                                                                                @if($value->subscriptionManagementTransaction->status == "pending")
                                                                                                    <strong class="text-danger">Pending</strong>
                                                                                                @elseif ($value->subscriptionManagementTransaction->status == "processed")
                                                                                                    <strong class="text-success">Processed</strong>
                                                                                                @elseif ($value->subscriptionManagementTransaction->status == "paid")
                                                                                                    <strong class="text-success">Paid</strong>
                                                                                                @elseif ($value->subscriptionManagementTransaction->status == "due")
                                                                                                    <strong class="text-danger">Due</strong>
                                                                                                @elseif ($value->subscriptionManagementTransaction->status == "paid_due")
                                                                                                    <strong class="text-success">Paid</strong> & 
                                                                                                    <strong class="text-danger">Due</strong>
                                                                                                @else
                                                                                                    -
                                                                                                @endif
                                                                                            @else
                                                                                                -
                                                                                            @endif
                                                                                            
                                                                                        </td>

                                                                                        <td width="4%" class="text-center sms">
                                                                                            @if($value->subscriptionManagementTransaction->sms)
                                                                                                @if($value->subscriptionManagementTransaction->sms == "yes")
                                                                                                    <strong class="text-success">Yes</strong>
                                                                                                @elseif ($value->subscriptionManagementTransaction->sms == "no")
                                                                                                    <strong class="text-danger">No</strong>
                                                                                                @else
                                                                                                    -
                                                                                                @endif
                                                                                            @else
                                                                                                -
                                                                                            @endif
                                                                                            
                                                                                        </td>

                                                                                        <td width="4%" class="text-center email">
                                                                                            @if($value->subscriptionManagementTransaction->email)
                                                                                                @if($value->subscriptionManagementTransaction->email == "yes")
                                                                                                    <strong class="text-success">Yes</strong>
                                                                                                @elseif ($value->subscriptionManagementTransaction->email == "no")
                                                                                                    <strong class="text-danger">No</strong>
                                                                                                @else
                                                                                                    -
                                                                                                @endif
                                                                                            @else
                                                                                                -
                                                                                            @endif
                                                                                            
                                                                                        </td>

                                                                                        <td width="4%" class="text-center invoices">
                                                                                            @if($value->subscriptionManagementTransaction->invoice)
                                                                                                @if($value->subscriptionManagementTransaction->invoice == "yes")
                                                                                                    <strong class="text-success">Yes</strong>
                                                                                                @elseif ($value->subscriptionManagementTransaction->invoice == "no")
                                                                                                    <strong class="text-danger">No</strong>
                                                                                                @else
                                                                                                    -
                                                                                                @endif
                                                                                            @else
                                                                                                -
                                                                                            @endif
                                                                                            
                                                                                        </td>

                                                                                        <td width="5%" class="text-center">Details</td>
                                                                                    @endif
                                                                                    @php $y++; @endphp
                                                                                </tr>
                                                                            @endif
                                                                        @endforeach
                                                                    @endforeach
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        @php $x++; @endphp
                                                    @endif                                       
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="form-group">
                                            <button id="subscription_management_submit_btn" class="btn btn-success pull-right" type="submit">Process</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{--global modal--}}
            <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" id="modal-content">
                        <div class="modal-body" id="modal-body">
                            <div class="loader">
                                <div class="es-spinner">
                                    <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <!-- DataTables -->
    <script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- datatable script -->
    <script>
        $(document).ready(function () {
            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": true
            });

            $('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });
        });
        
        $(".subform .olddues").each(function() {
            $(this).dblclick(function(){
                $(this).prop("readonly", false)
            });

            var typingTimer;
            var doneTypingInterval = 5000;

            $(this).on('keyup', function () {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(doneTyping($(this)), doneTypingInterval);
            });

            $(this).on('keydown', function () {
                clearTimeout(typingTimer);
            });

            function doneTyping ($obj) {
                var billingInfoID = $obj.attr('id');
                var str = [];
                str = billingInfoID.match(/([^_]+)/g);
                var oldDuesValue = $obj.val();
                setOldDues(str[1], oldDuesValue, $obj);
            }

            $(this).on("mouseleave", function() {
                $(this).prop("readonly", true);
            });
        });

        function setOldDues(billingInfoID, oldDuesValue, $obj) {
            $.ajax({
                url: "{{ url('/api/institute/olddues') }}",
                type: 'POST',
                cache: false,
                data: {'billing_info_ID': billingInfoID, 'old_dues_value': oldDuesValue, '_token': '{{csrf_token()}}'}, //see the $_token
                datatype: 'application/json',

                // on success
                success: function (data) {

                    var oldDues = Number(data[0]).toFixed(2);
                    var monthlyTotalCharge = Number(data[1]).toFixed(2);
                    var newDues = data[2];
                    var status = data[3];
                    var email = data[4];

                    $obj.parents('.subform').find(".olddues").html(oldDues);
                    $obj.parents('.subform').find(".monthly_total_charge").html(monthlyTotalCharge);

                    if(newDues == null) {
                        $obj.parents('.subform').find(".new_dues").html('-');
                    } else {
                        $obj.parents('.subform').find(".new_dues").html(Number(data[2]).toFixed(2));
                    }

                    if(status == null) {
                        $obj.parents('.subform').find(".status").html('-');
                    } else {
                        if(status == "pending") {
                            $obj.parents('.subform').find(".status").html('<strong class="text-danger">Pending</strong>');
                        }
                        
                        else if(status == "paid") {
                            $obj.parents('.subform').find(".status").html('<strong class="text-success">Paid</strong>');
                        }

                        else if(status == "paid_due") {
                            $obj.parents('.subform').find(".status").html('<strong class="text-success">Paid</strong> & <strong class="text-danger">Due</strong>');
                        }

                        else if(status == "due") {
                            $obj.parents('.subform').find(".status").html('<strong class="text-danger">Due</strong>');
                        }

                        else {
                            $obj.parents('.subform').find(".status").html('<strong class="text-success">Processed</strong>');                           
                        }
                    }

                    if(email == null) {
                        $obj.parents('.subform').find(".email").html('-');
                    } else if(email == "yes") {
                        $obj.parents('.subform').find(".email").html('<strong class="text-success">Yes</strong>');
                    } else {
                        $obj.parents('.subform').find(".email").html('<strong class="text-danger">No</strong>');                           
                    }
                },

                // on error
                error : function (data) {
                    //alert('Unable to load data form server');
                    swal("Error", 'Unable to load data form server', "error");
                }
            });
        }

        $(".subform .paidamount").each(function() {
            $(this).dblclick(function(){
                $(this).prop("readonly", false)
            })

            var paidAmountID = $(this).attr('id');
            var str = [];
            str = paidAmountID.match(/([^_]+)/g);
            var paidAmountValue;
            $(this).on('keypress',function(e) {
                paidAmountValue = $(this).val();
                if(e.which == 13) {
                    setPaidAmount(str[1], paidAmountValue, $(this));
                }
            });

            $(this).mouseleave(function(){
                $(this).prop("readonly", true)   
            })
        });

        function setPaidAmount(billingInfoID, paidAmountValue, $obj) {
            $.ajax({
                url: "{{ url('/api/institute/paidamount') }}",
                type: 'POST',
                cache: false,
                data: {'billing_info_ID': billingInfoID, 'paid_amount_value': paidAmountValue, '_token': '{{csrf_token()}}'}, //see the $_token
                datatype: 'application/json',

                // on success
                success: function (data) {
                    var paidAmount = Number(data[0]).toFixed(2);
                    var newDues = data[1];
                    var paidOn = data[2];
                    var status = data[3];

                    $obj.parents('.subform').find(".paidamount").html(paidAmount);

                    if(newDues == null) {
                        $obj.parents('.subform').find(".new_dues").html('-');
                    } else {
                        $obj.parents('.subform').find(".new_dues").html(Number(data[1]).toFixed(2));
                    }
                    
                    if(paidOn == null) {
                        $obj.parents('.subform').find(".paid_on").html('-');
                    } else {
                        $obj.parents('.subform').find(".paid_on").html(paidOn);
                    }

                    if(status == null) {
                        $obj.parents('.subform').find(".status").html('-');

                    } else {

                        if(status == "paid") {
                            $obj.parents('.subform').find(".status").html('<strong class="text-success">Paid</strong>');
                        }

                        else if(status == "paid_due") {
                            $obj.parents('.subform').find(".status").html('<strong class="text-success">Paid</strong> & <strong class="text-danger">Due</strong>');
                        }

                        else {
                            $obj.parents('.subform').find(".status").html('<strong class="text-danger">Due</strong>');                        
                        }
                    }
                },

                // on error
                error : function (data) {
                    //alert('Unable to load data form server');
                    swal("Error", 'Unable to load data form server', "error");
                }
            });
        }
            
    </script>
@endsection

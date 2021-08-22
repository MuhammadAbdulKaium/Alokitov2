@extends('layouts.master')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-plus-square"></i> Vendor Details  
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="#">Inventory</a></li>
            <li><a href="#">SOP Setup</a></li>
            <li><a href="#">Purchase</a></li>
            <li class="active">Vendor</li>
        </ul>
    </section>

    <section class="content">                
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Category:: </label>
                            <div class="col-sm-9">
                                {{($vendorInfo->category_id==1)?'Sundry Creditors':''}}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Type:: </label>
                            <div class="col-sm-9">
                                {{($vendorInfo->type==1)?'General':''}}
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading">General</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="name" class="col-sm-3 control-label">Name:: </label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->name}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Picture:</label>
                                        <div class="col-sm-9">
                                             @if(!empty($vendorInfo->image))
                                             <img src="{{url('assets/inventory/vendor_image/'.$vendorInfo->image)}}" style="height: 70px" width="70px" align="center" alt="Vendor logo">
                                             @else
                                               {{'no image'}}
                                             @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">GL Code:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->gl_code}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">Home</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="name" class="col-sm-3 control-label p-r-0">Home Address:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->home_address}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">City:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->home_city}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">State:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->home_state}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="name" class="col-sm-3 control-label p-r-0">Zip Code:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->home_zip_code}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Mobile:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->home_mobile}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Phone:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->home_phone}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="name" class="col-sm-3 control-label p-r-0">Fax:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->home_fax}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Web:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->home_web}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="birth_date_show" class="col-sm-3 control-label">Birth Date:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->birth_date_show}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="anniversary" class="col-sm-3 control-label">Anniversary:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->anniversary_date}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="panel panel-default">
                      <div class="panel-heading">Business</div>
                      <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="name" class="col-sm-3 control-label p-r-0">Company:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->business_company_name}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Job Title:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->business_job_title}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Department:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->business_department}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Address:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->business_address}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">State:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->business_state}}
                                        </div>
                                    </div>
                                </div>


                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="name" class="col-sm-3 control-label p-r-0">Zip Code:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->business_zip_code}}
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="business_country" class="col-sm-3 control-label p-r-0">Country:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->business_country}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="business_fax" class="col-sm-3 control-label p-r-0">Fax:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->business_fax}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="business_phone" class="col-sm-3 control-label">Phone:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->business_phone}}
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="business_web" class="col-sm-3 control-label">Web:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->business_web}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="opening_balance" class="col-sm-3 control-label">Opening Balance:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->opening_balance}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="opening_balance" class="col-sm-3 control-label">Opening Balance type:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->opening_balance_type}}
                                        </div>
                                    </div>
                                </div>


                            </div>

                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="credit_priod" class="col-sm-3 control-label">Credit period:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->credit_priod}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="credit_limit" class="col-sm-3 control-label">Credit Limit:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->credit_limit}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Bill tracking:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->bill_tracking}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="price_cate_id" class="col-sm-3 control-label">Price label:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->price_cate_name}}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="maintaining_cost_center" class="col-sm-3 control-label">Maintain Cost center:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->maintaining_cost_center}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                      <div class="panel-heading">Commission</div>
                      <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="commission_type" class="col-sm-3 control-label">Type:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->commission_type}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="commission_value" class="col-sm-3 control-label">Value:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->commission_value}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="bill_by_bill" class="col-sm-3 control-label">Bill By Bill:</label>
                                        <div class="col-sm-9">
                                            {{$vendorInfo->bill_by_bill}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                      </div>
                    </div>

                    <div class="panel panel-default">
                      <div class="panel-heading">Terms & Conditions</div>
                      <div class="panel-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="10%">#</th>
                                        <th>Text</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inventory_vendor_terms_condition as $key => $v)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>
                                            {{$v->term_condition}}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                      </div>
                    </div>

                </div>
            </div>
            <div class="box-footer">
                <a class="btn btn-default" href="{{url('inventory/vendor')}}">Back</a>
            </div>
        </div>
    </section>
</div>
@endsection






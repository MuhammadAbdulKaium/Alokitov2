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
    @php
        $functionCore= new \App\Http\Controllers\Helpers\Accounting\FunctionCore;
    @endphp

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">

            <div class="col-lg-12 col-xs-12">

                <!-- small box -->
                <div class="row">
                    <div class="col-xs-6">
                        <div class="box box-widget widget-user-2">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-green text-center">
                                @if(!empty(getInstituteProfile()))
                                    {{--institute logo--}}
                                    <img src="{{url('assets/users/images/'.getInstituteProfile()->logo)}}" style="height: 70px" width="70px" align="center" class="brand-logo" alt="Alokito logo">
                                @else
                                    {{--redireting--}}
                                    {{URL::to('/ ')}}
                                @endif
                            </div>
                            <div class="box-footer no-padding">
                                <ul class="nav nav-stacked">
                                    <li>
                                        <a>
                                          Financial Account Name:
                                            <span class="pull-right">
                          {{$account->account_name}}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a>
                                            Financial Year
                                            <span class="pull-right">
                          {{date('d-m-Y',strtotime($account->f_year_start))}} to {{date('d-m-Y',strtotime($account->f_year_start))}}    </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a>
                                            Email
                                            <span class="pull-right">
                         {{$account->email}}                        </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a>
                                            Address                        <span class="pull-right">
                          {{$account->address}}                        </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a>
                                            Status                        <span class="pull-right badge bg-green">Active</span>                      </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                         <div class="box box-solid">
                             <h4> Net Worth</h4>
                             <table>
                                 <tr>
                                     <td id="today_income"><strong>Today Income</strong></td>
                                     <td><strong>{{$today_total_income}}</strong></td>
                                 </tr>
                                 <tr>
                                     <td id="today_expense"><strong>Today Expense</strong></td>
                                     <td><strong>{{$today_total_expense}}</strong></td>
                                 </tr>
                                 <tr>
                                     <td id="month_income"><strong>Monthly Income</strong></td>
                                     <td><strong>{{$monthly_total_income}}</strong></td>
                                 </tr>
                                 <tr>
                                     <td id="month_expense"><strong>Monthly Expense</strong></td>
                                     <td><strong>{{$monthly_total_expense}}</strong></td>
                                 </tr>
                             </table>
                         </div>

                    </div>
                    <div class="col-xs-6">
                        <div class="box box-info box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title" style="color: white;">Balance Summary</h3>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <!-- /.box-tools -->
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body" style="display: block;">
                                <div id="balance_summary" style="height: 350px;"></div>

                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>

                </div>

            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
    <script src="https://otsglobal.org/accountant/assets/plugins/echarts/echarts.min.js"></script>

    <script type="text/javascript">
        var c_month = 'May';
        var c_year = '2019';
        var ib_graph_primary_color = '#2196f3';
        var ib_graph_secondary_color = '#eb3c00';

        pie_options = {
            title : {
                text: 'Balance Summary',
                x:'center'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                left: 'left',
                data: ['Assets','Liabilities and Owners Equity','Income','Expense']
            },
            series : [
                {
                    name: 'Balance Summary',
                    type: 'pie',
                    radius : '55%',
                    center: ['50%', '60%'],
                    data:[
            {value:<?php echo $accsummary['assets_total'] ?>, name:'Assets'},
            {value:<?php echo $accsummary['liabilities_total']; ?>, name:'Liabilities and Owners Equity'},
            {value:<?php echo $accsummary['income_total']; ?>, name:'Income'},
            {value:<?php echo $accsummary['expense_total']; ?>, name:'Expense'},
                  ],
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }
            ]
        };
        var pie = echarts.init(document.getElementById('balance_summary'));
        pie.setOption(pie_options);

    </script>

@endsection

@section('page-script')




@endsection



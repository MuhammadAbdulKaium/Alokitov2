@extends('layouts.master')
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{URL::asset('css/datepicker3.css')}}">
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class = "fa fa-eye" aria-hidden="true"></i> Accounting
            </h1>
            <ul class="breadcrumb">
                <li><a href="#"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="{{url('/accounting')}}">Accounting</a></li>
                <li><a href="{{url('/accounting/accfyear')}}">Financial Year</a></li>
                <li><a href="#">Financial Year Closing</a></li>
            </ul>
        </section>
        <section class="content">
            <div class="row">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Financial Year Closing</h3>
                                <div class="box-tools">
                                    <a class="btn btn-success btn-sm" href="{{url('accounting/accfyear/closeYear')}}">Close Year</a>
                                </div>
                            </div>
                            <div class="box-body table-responsive">
                                <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                                    <div id="w1" class="grid-view">
                                        <table id="myTable" class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th style="text-align: center">Account Head</th>
                                                {{--<th style="text-align: center">Dr Total</th>
                                                <th style="text-align: center">Cr Total</th>--}}
                                                <th style="text-align: center">Closing Balance</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {{--@php $i=1 @endphp--}}
                                            @foreach($accHeads as $accHead)
                                                <tr style="font-weight: 600" >
                                                    {{--<td>{{ $i++ }}</td>--}}
                                                    <td>{{$accHead->chart_name}}</td>
                                                    {{--<td style="text-align: right">{{ abs($accHead->sumDrCalc($accHead->id)) }}</td>
                                                    <td style="text-align: right">{{ abs($accHead->sumCrCalc($accHead->id)) }}</td>--}}
                                                    <td style="text-align: right">{{ abs($accHead->sumCalc($accHead->id)) }}</td>
                                                </tr>
                                                @if(count($accHead->childs))
                                                    @include('accounting::pages.accFYear.manageChildTB',['childs' => $accHead->childs])
                                                @endif
                                            @endforeach
                                            </tbody>
                                            {{--<tfoot>
                                            <tr style="font-weight: 600">
                                                <td  style="text-align: right"> Total</td>
                                                <td  style="text-align: right">
                                                    {{
                                                    abs($accHead->sumDrCalc(1)) +
                                                    abs($accHead->sumDrCalc(2)) +
                                                    abs($accHead->sumDrCalc(3)) +
                                                    abs($accHead->sumDrCalc(4))
                                                    }}
                                                </td>
                                                <td  style="text-align: right">{{
                                                    abs($accHead->sumCrCalc(1)) +
                                                    abs($accHead->sumCrCalc(2)) +
                                                    abs($accHead->sumCrCalc(3)) +
                                                    abs($accHead->sumCrCalc(4))
                                                    }}</td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            </tfoot>--}}
                                        </table>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        {{--data will sit here--}}
    </div>

    <script type = "text/javascript">
        function modalLoad(rowId) {
            var token = "{{ csrf_token() }}";
            var dataSet = '_token='+token+'&id='+rowId;
            $.ajax({
                url: "{{ url('accounting/accvoucherentry/approve')}}",
                type: 'post',
                data: dataSet,
                beforeSend: function () {
                }, success: function (data) {
                    $('#myModal').html(data);
                }
            });
        }



        function searchDataLoad() {
            var token = "{{ csrf_token() }}";
            var date = $('#datepicker').val();
            var dataSet = '_token='+token+'&date='+date;
            alert(dataSet);

        }
    </script>

    <!-- jQuery 2.2.3 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <!-- <script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script> -->
    <!-- Bootstrap 3.3.6 -->
    <script src="{{URL::asset('js/bootstrap.min.js')}}"></script>
    <!-- Select2 -->
    <script src="{{URL::asset('js/select2.full.min.js')}}"></script>
    <!-- InputMask -->
    <script src="{{URL::asset('js/jquery.inputmask.js')}}"></script>
    <script src="{{URL::asset('js/jquery.inputmask.date.extensions.js')}}"></script>

    <!-- bootstrap datepicker -->
    <script src="{{URL::asset('js/bootstrap-datepicker.js')}}"></script>

    <script>
        $(function () {
            //Date picker
            $('#datepicker').datepicker({
                autoclose: true
            });
        });

    </script>
@endsection

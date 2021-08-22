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

            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Entry Details</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div>
                            Number : {{$entry[0]->number}}
                            <br>
                            @if($entry[0]->entrytype_id==1)
                                Receipt
                            @elseif($entry[0]->entrytype_id==2)
                                Payment
                            @endif
                            <br>
                            <br>
                            <table class="stripped">
                                <tbody>
                                <tr>
                                    <th>Dr/Cr</th>
                                    <th>Ledger</th>
                                    <th>Dr Amount (৳)</th>
                                    <th>Cr Amount (৳)</th>
                                    <th>Narration</th>
                                </tr>
                                @foreach ($curEntryitems as $row => $entryitem)
                                <tr>
                                    <td>
                                        @if ($entryitem['dc'] == 'D')
                                            Dr
                                        @else
                                            Cr
                                        @endif

                                    </td>
                                    <td>{{$entryitem['ledger_name']}}</td>
                                    <td>
                                        @if($entryitem['dc'] == 'D')
                                        {{ $entryitem['dr_amount']}}
                                            @endif
                                    </td>
                                    <td>  @if($entryitem['dc'] == 'C')
                                            {{ $entryitem['cr_amount']}}
                                        @endif</td>
                                    <td>{{$entryitem['narration']}}</td>
                                </tr>
                               @endforeach
                                </tbody>
                            </table>

                            <p> Notes: {{ $entry[0]->notes}}</p>

                            <a href="{{URL::to('/finance/accounts/entries/print', $entry[0]->id)}}" class="btn btn-primary">Print View</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
    </section>
    <!-- /.content -->
@endsection

@section('page-script')




@endsection



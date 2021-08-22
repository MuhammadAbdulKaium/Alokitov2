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
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Entries</h3>
                        <!-- Split button -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-plus-square"></i> Add Entry </button>
                            <ul class="dropdown-menu">
                                @foreach($entriesTypeList as $entryType)
                                    <li><a href="{{URL::to('finance/accounts/entries/add',$entryType->label)}}">{{$entryType->name}}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @if(!empty($entries))
                            <table class="stripped">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Number</th>
                                    <th>Ledger</th>
                                    <th>Type</th>
                                    <th>Tag</th>
                                    <th>Debit Amount</th>
                                    <th>Credit Amount</th>
                                    <th>View</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($entries as $entry)
                                    <tr>
                                        <td>{{$entry->date}}</td>
                                        <td>{{$entry->number}}</td>
                                        <td>{{$entry->entryLedgers($entry->id)}}</td>
                                        <td>
                                            @if($entry->entrytype_id==1)
                                                Receipt
                                            @elseif($entry->entrytype_id==2)
                                                Payment
                                            @endif

                                        </td>
                                        <td></td>
                                        <td>Dr {{$entry->dr_total}}</td>
                                        <td>Cr {{$entry->cr_total}}</td>
                                        <td><a href="{{URL::to('/finance/accounts/entries/view', $entry->id)}}"> View</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {!! $entries->render() !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@section('page-script')




@endsection



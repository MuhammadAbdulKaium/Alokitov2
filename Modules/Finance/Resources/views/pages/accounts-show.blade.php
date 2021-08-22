\@extends('finance::layouts.master')
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


    <div class="box box-body">
        <div style="padding:25px;">
            <div class="box-body table-responsive" id="chart_tree">
                <style type="text/css">
                    /************** TABLES ***************/
                    table {
                        width: 100%;
                    }

                    th {
                        border-bottom: 2px solid #555;
                        text-align: left;
                        font-size: 16px;
                    }

                    th a {
                        display: block;
                        padding: 2px 4px;
                        text-decoration: none;
                    }

                    th a.asc:after {
                        content: ' ⇣';
                    }

                    th a.desc:after {
                        content: ' ⇡';
                    }

                    table tr td {
                        padding: 4px;
                        text-align: left;
                    }

                    table.stripped tr td {
                        border-bottom:1px solid #DDDDDD;
                        border-top:1px solid #DDDDDD;
                    }

                    table.stripped tr:hover {
                        background-color: #FFFF99;
                    }

                    table.stripped .tr-ledger {

                    }

                    table.stripped .tr-group {
                        font-weight: bold;
                    }

                    table.extra tr td {
                        padding: 6px;
                    }

                    table.stripped .tr-root-group {
                        background-color: #F3F3F3;
                        color: #754719;
                    }

                </style>
                <table class="table stripped table-hover">
                    <tbody>
                    <tr>
                        <th>Account name</th>
                        <th>Type</th>
                        <th>O/P Balance(OMR)</th>
                        <th>C/L Balance(OMR)</th>
                        <th>Actions</th>
                    </tr>


                    @php $parentGroupList=$chartOfAccount['parent']; @endphp

                    @foreach($parentGroupList as $key=>$parentGroup)
                    <tr class="tr-group tr-root-group">
                        <td class="td-group">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$parentGroup['name']}}</td>
                        <td>Group</td>
                        <td>0.00</td>
                        <td>0.000</td>
                        <td class="td-actions"></td>
                    </tr>

                    {{--child gorup--}}
                    @php $childGroupList=array_key_exists('child',$parentGroup)?$parentGroup['child']:[]; @endphp
                    {{--List Loop--}}
                    @foreach($childGroupList as $chiledKey=>$childGroup)
                        {{--{{dd($childGroup['ledger'])}}--}}

                        {{--Child Gorup Details--}}
                    <tr class="tr-group">
                        <td class="td-group">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$childGroup['name']}}</td>
                        <td>Group</td>
                        <td>0.000</td>
                        <td>0.00</td>
                        <td class="td-actions">
                            <a href="/finance/group/edit/{{$chiledKey}}"  class="btn btn-success">
                                <i class="fa fa-edit"></i>
                            </a>&nbsp;&nbsp;
                            <a href="/finance/group/delete/{{$chiledKey}}"   class="btn btn-danger">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </td>
                    </tr>


                        @php $ledgerList = array_key_exists('ledger', $childGroup)?$childGroup['ledger']:[]; @endphp
                        @foreach($ledgerList as  $key=>$ledger)

                    <tr class="tr-ledger">
                        <td class="td-ledger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="#">{{$ledger['name']}}</a>
                        </td>
                        <td>Ledger</td>
                        <td> 0.00
                        </td>
                        <td>0.00</td>
                        <td class="td-actions">
                            <a href="/finance/ledger/edit/{{$key}}"  class="btn btn-success">
                                <i class="fa fa-edit"></i>
                            </a>&nbsp;&nbsp;
                            <a href="/finance/ledger/delete/{{$key}}"   class="btn btn-danger">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </td>
                    </tr>

                        @endforeach
                        {{--@endif--}}
                    @endforeach
                    {{--@endif--}}
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('page-script')
@endsection
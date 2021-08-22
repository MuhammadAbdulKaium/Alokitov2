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
    <div class="box box-body">
        <h2>Manage Accounts</h2>
        <div id="actionlinks" style="padding-bottom:20px;">
            <a href="/finance/accounts/create" class="btn btn-primary">Create Account</a>
        </div>
        <div>
            <div role="alert" class="alert alert-warning">Please make sure no user is actively using any account before deleting it.</div>
        </div>
        <table class="table table-bordered">
            <tbody>
            <tr>
                <th>
                    <a href="/finance/wzaccounts/index/sort:label/direction:desc" class="asc">Account ID</a>
                </th>
                <th>
                    <a href="/finance/wzaccounts/index/sort:label/direction:desc" class="asc">Email</a>
                </th>
                <th>
                    <a href="/finance/wzaccounts/index/sort:label/direction:desc" class="asc">Financial Year Start</a>
                </th>
                <th>
                    <a href="/finance/wzaccounts/index/sort:label/direction:desc" class="asc">Financial Year End</a>
                </th>
                <!--
                    <th></th><th></th><th></th><th></th>
                    -->
                <th>Actions</th>
            </tr>

            @foreach($accountList as $account)

            <tr>
                <td>{{$account->account_id}}</td>
                <td>{{$account->email}} </td>
                <td>{{$account->f_year_start}}</td>
                <td>{{$account->f_year_end}}</td>
                <td>
                    <form action="/finance/wzaccounts/delete/1" name="post_5c763180769cb" id="post_5c763180769cb" style="display:none;" method="post">
                        <input type="hidden" name="_method" value="POST">
                        <input type="hidden" name="data[_Token][key]" value="84b7f28fe16bc47276c3d5dbffc315f9ea43d357" id="Token1307599279">
                        <div style="display:none;">
                            <input type="hidden" name="data[_Token][fields]" value="1031cb68a1f401f034471be643f76847fd458a70%3A" id="TokenFields1471435990">
                            <input type="hidden" name="data[_Token][unlocked]" value="" id="TokenUnlocked2025713890">
                        </div>
                    </form>
                    <a href="#" class="no-hover" onclick="if (confirm('Are you sure you want to delete the account config ?')) { document.post_5c763180769cb.submit(); } event.returnValue = false; return false;">
                        <i class="glyphicon glyphicon-trash"></i> Delete
                    </a>
                </td>
            </tr>

                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('page-script')
@endsection
@extends('library::layouts.master')
@section('section-title')
    <h1>
        <i class="fa fa-th-list"></i> Manage |<small>Fine </small>        </h1>
    <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
        <li><a href="/library/default/index">Library</a></li>
        <li class="active">Fine</li>
    </ul>


@endsection
<!-- page content -->
@section('page-content')
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-plus-square"></i> Take Fine            </h3>
        </div>

        @php
        $startDate = \Carbon\Carbon::parse($issueBookProfile->issue_date);
                              $endDate   = \Carbon\Carbon::parse($issueBookProfile->due_date);
                              $dateDiff = $current->diff($endDate)->format("%a");
                              $fine=($issueBookProfile->daily_fine)*($dateDiff);

        @endphp
        <form method="post" action="{{url('library/library-borrow-transaction/return-book-with-fine-manual')}}/{{$issueBookProfile->id}}">
            {{ csrf_field() }}
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group field-libraryfinemaster-lfm_holder_type required">
                            <label class="control-label" for="libraryfinemaster-lfm_holder_type">Holder Type</label>
                            <select id="libraryborrowtransaction-lbt_holder_type" class="form-control" @if(!empty($issueBookProfile)) disabled @endif name="holder_type" aria-required="true">
                                <option value="">--- Select Holder Type ---</option>

                                <option value="1"  @if(!empty($issueBookProfile) && $issueBookProfile->holder_type=="1")  selected @endif >Student</option>
                                <option value="2"  @if(!empty($issueBookProfile) && $issueBookProfile->holder_type=="2")  selected @endif >Employee</option>
                            </select>

                            <div class="help-block"></div>
                        </div>      </div>
                    <div class="col-sm-6" id="name">
                        <div class="form-group field-libraryborrowtransaction-name required">
                            <label class="control-label" for="libraryborrowtransaction-name">Holder Name</label>
                            <input class="form-control" id="employee" name="payer_name" type="text" @if(!empty($issueBookProfile)) value="{{$issueBookProfile->student()->first_name.' '.$issueBookProfile->student()->middle_name.' '.$issueBookProfile->student()->last_name}}" disabled @endif  placeholder="Type Student Name">
                            <input id="std_id" name="holder_id" type="hidden" value=""/>
                            <div class="help-block"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group field-libraryfinemaster-lfm_amount required">
                            <label class="control-label" for="libraryfinemaster-lfm_amount">Amount</label>
                            <input id="libraryfinemaster-lfm_amount" class="form-control" name="LibraryFineMaster[lfm_amount]" maxlength="10" aria-required="true" type="text" value="{{$fine}}">

                            <div class="help-block"></div>
                            <input type="hidden" name="LibraryFineMaster[lfm_total_amount]" value="{{$fine}}">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group field-libraryfinemaster-lfm_remarks">
                            <label class="control-label" for="libraryfinemaster-lfm_remarks">Remarks</label>
                            <textarea id="libraryfinemaster-lfm_remarks" class="form-control" name="LibraryFineMaster[lfm_remarks]" rows="3">{{$issueBookProfile->remarks}}</textarea>

                            <div class="help-block"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-create">Save</button>
            </div>
        </form>
        {{--                <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-1" tabindex="0" style="display: none;"></ul>--}}

    </div>
@endsection

@section('page-script')




@endsection


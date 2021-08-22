@extends('layouts.master')
<!-- page content -->
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class = "fa fa-envelope-o" aria-hidden="true"></i> Admission Letters        </h1>
            <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/admission/default/index">Enquiry</a></li>
                <li class="active">Admission Letters</li>
            </ul>    </section>
        <section class="content">
            <div class="box box-solid">
                <div class="box-body table-responsive">
                    <p class="text-right">
                        <a class="btn btn-success" href="/admission/admission-letter/create"><i class="fa fa-plus-square"></i> Create</a>        </p>
                    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-replace-state data-pjax-timeout="10000">           <div id="w1" class="grid-view"><div class="summary">Showing <b>1-1</b> of <b>1</b> item.</div>
                            <table class="table table-striped table-bordered"><thead>
                                <tr><th>#</th><th><a href="/admission/admission-letter/index?sort=admission_letter_title" data-sort="admission_letter_title">Letter Title</a></th><th class="action-column">&nbsp;</th></tr><tr id="w1-filters" class="filters"><td>&nbsp;</td><td><input type="text" class="form-control" name="AdmissionLetterSearch[admission_letter_title]"></td><td>&nbsp;</td></tr>
                                </thead>
                                <tbody>
                                <tr data-key="1"><td>1</td><td>Admission Letter</td><td><a href="/admission/admission-letter/view?id=1" title="View" aria-label="View" data-pjax="0"><span class="glyphicon glyphicon-eye-open"></span></a> <a href="/admission/admission-letter/update?id=1" title="Update" aria-label="Update" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/admission/admission-letter/delete?id=1" title="Delete" aria-label="Delete" data-pjax="0" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td></tr>
                                </tbody></table>
                        </div>      </div>   </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')


@endsection

@extends('layouts.master')
<!-- page content -->
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-eye"></i> Admission Letter        </h1>
            <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/admission/default/index">Enquiry</a></li>
                <li><a href="/admission/admission-letter/index">Admission Letters</a></li>
                <li class="active">Admission Letter</li>
            </ul>    </section>
        <section class="content">
            <div class="box box-solid">
                <div class="box-body table-responsive">
                    <p class="text-right">
                        <a class="btn btn-default" href="/admission/admission-letter/index"><i class="fa fa-chevron-left"></i> Back</a>          <a class="btn btn-info" href="/admission/admission-letter/update?id=1"><i class="fa fa-pencil-square-o"></i> Update</a>          <a class="btn btn-danger" href="/admission/admission-letter/delete?id=1" data-confirm="Are you sure you want to delete this item?" data-method="post"><i class="fa fa-trash-o"></i> Delete</a>    </p>
                    <table id="w0" class="table table-striped table-bordered detail-view"><tr><th>Letter Title</th><td>Admission Letter</td></tr>
                        <tr><th>Letter Content</th><td><p>Dear&nbsp;{title} {name},</p>
                                <p>We are Happy to inform you that your application for the course&nbsp;{applyCourse} In batch&nbsp;{applyBatch} with registration no&nbsp;{regNo} has been approved.</p>
                                <p>&nbsp;</p>
                                <p>{instituteName}</p>
                                <p>{instituteAddress}</p>
                                <p>{instituteEmail}</p>
                                <p>{instituteWebsite}</p>
                            </td></tr>
                        <tr><th>Created By</th><td>chloe@edusec.org</td></tr>
                        <tr><th>Created At</th><td>Sep 12, 2016, 1:49:35 PM</td></tr>
                        <tr><th>Updated By</th><td>chloe@edusec.org</td></tr>
                        <tr><th>Updated At</th><td>Sep 12, 2016, 1:49:35 PM</td></tr></table>   </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')


@endsection

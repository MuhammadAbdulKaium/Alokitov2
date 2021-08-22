@extends('finance::layouts.master')
@section('section-title')
    <h1>
        <i class="fa fa-search"></i>Finance
    </h1>
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
        <li><a href="/library/default/index">Finacne</a></li>
        <li class="active">Manage Account</li>
    </ul>
@endsection
<!-- page content -->
@section('page-content')
    <div class="box box-body">
        <div style="padding:25px;">
            <table class="table stripped">
                <tbody><tr>
                    <th><a href="/finance/entrytypes/index/sort:label/direction:asc">Label</a></th>
                    <th><a href="/finance/entrytypes/index/sort:name/direction:asc">Name</a></th>
                    <th><a href="/finance/entrytypes/index/sort:description/direction:asc">Description</a></th>
                    <th><a href="/finance/entrytypes/index/sort:prefix/direction:asc">Prefix</a></th>
                    <th><a href="/finance/entrytypes/index/sort:suffix/direction:asc">Suffix</a></th>
                    <th><a href="/finance/entrytypes/index/sort:zero_padding/direction:asc">Zero Padding</a></th>
                    <th>Actions</th>
                </tr>
                <tr>
                    <td>receipt</td>
                    <td>Receipt</td>
                    <td>Received in Bank account or Cash account</td>
                    <td>CDL</td>
                    <td></td>
                    <td>0</td>
                    <td>
                        <a href="/finance/entrytypes/edit/1" class="no-hover"><i class="glyphicon glyphicon-edit"></i> Edit</a>				<span class="link-pad"></span>				<form action="/finance/entrytypes/delete/1" name="post_5c7640b53f933" id="post_5c7640b53f933" style="display:none;" method="post"><input type="hidden" name="_method" value="POST"><input type="hidden" name="data[_Token][key]" value="84beba25417f325acfd802e7b33d660489e5abe3" id="Token504629859"><div style="display:none;"><input type="hidden" name="data[_Token][fields]" value="1031cb68a1f401f034471be643f76847fd458a70%3A" id="TokenFields199321991"><input type="hidden" name="data[_Token][unlocked]" value="" id="TokenUnlocked1468860269"></div></form><a href="#" class="no-hover" onclick="if (confirm('Are you sure you want to delete the entry type?')) { document.post_5c7640b53f933.submit(); } event.returnValue = false; return false;"><i class="glyphicon glyphicon-trash"></i> Delete</a>			</td>
                </tr>
                <tr>
                    <td>payment</td>
                    <td>Payment</td>
                    <td>Payment made from Bank account or Cash account</td>
                    <td></td>
                    <td></td>
                    <td>0</td>
                    <td>
                        <a href="/finance/entrytypes/edit/2" class="no-hover"><i class="glyphicon glyphicon-edit"></i> Edit</a>				<span class="link-pad"></span>				<form action="/finance/entrytypes/delete/2" name="post_5c7640b53fc6a" id="post_5c7640b53fc6a" style="display:none;" method="post"><input type="hidden" name="_method" value="POST"><input type="hidden" name="data[_Token][key]" value="84beba25417f325acfd802e7b33d660489e5abe3" id="Token1456649971"><div style="display:none;"><input type="hidden" name="data[_Token][fields]" value="1031cb68a1f401f034471be643f76847fd458a70%3A" id="TokenFields797675221"><input type="hidden" name="data[_Token][unlocked]" value="" id="TokenUnlocked1402994172"></div></form><a href="#" class="no-hover" onclick="if (confirm('Are you sure you want to delete the entry type?')) { document.post_5c7640b53fc6a.submit(); } event.returnValue = false; return false;"><i class="glyphicon glyphicon-trash"></i> Delete</a>			</td>
                </tr>
                <tr>
                    <td>contra</td>
                    <td>Contra</td>
                    <td>Transfer between Bank account and Cash account</td>
                    <td></td>
                    <td></td>
                    <td>0</td>
                    <td>
                        <a href="/finance/entrytypes/edit/3" class="no-hover"><i class="glyphicon glyphicon-edit"></i> Edit</a>				<span class="link-pad"></span>				<form action="/finance/entrytypes/delete/3" name="post_5c7640b53fefe" id="post_5c7640b53fefe" style="display:none;" method="post"><input type="hidden" name="_method" value="POST"><input type="hidden" name="data[_Token][key]" value="84beba25417f325acfd802e7b33d660489e5abe3" id="Token1522403511"><div style="display:none;"><input type="hidden" name="data[_Token][fields]" value="1031cb68a1f401f034471be643f76847fd458a70%3A" id="TokenFields2020275911"><input type="hidden" name="data[_Token][unlocked]" value="" id="TokenUnlocked648563042"></div></form><a href="#" class="no-hover" onclick="if (confirm('Are you sure you want to delete the entry type?')) { document.post_5c7640b53fefe.submit(); } event.returnValue = false; return false;"><i class="glyphicon glyphicon-trash"></i> Delete</a>			</td>
                </tr>
                <tr>
                    <td>journal</td>
                    <td>Journal</td>
                    <td>Transfer between Non Bank account and Cash account</td>
                    <td></td>
                    <td></td>
                    <td>0</td>
                    <td>
                        <a href="/finance/entrytypes/edit/4" class="no-hover"><i class="glyphicon glyphicon-edit"></i> Edit</a>				<span class="link-pad"></span>				<form action="/finance/entrytypes/delete/4" name="post_5c7640b540172" id="post_5c7640b540172" style="display:none;" method="post"><input type="hidden" name="_method" value="POST"><input type="hidden" name="data[_Token][key]" value="84beba25417f325acfd802e7b33d660489e5abe3" id="Token1148217800"><div style="display:none;"><input type="hidden" name="data[_Token][fields]" value="1031cb68a1f401f034471be643f76847fd458a70%3A" id="TokenFields1324610434"><input type="hidden" name="data[_Token][unlocked]" value="" id="TokenUnlocked153109634"></div></form><a href="#" class="no-hover" onclick="if (confirm('Are you sure you want to delete the entry type?')) { document.post_5c7640b540172.submit(); } event.returnValue = false; return false;"><i class="glyphicon glyphicon-trash"></i> Delete</a>			</td>
                </tr>
                </tbody></table>

            <div class="text-center paginate">
                <ul class="pagination">
                    <li class="disabled"><a>prev</a></li><li class="disabled"><a>next</a></li>	</ul>
            </div>


        </div>
    </div>
@endsection
@section('page-script')
@endsection
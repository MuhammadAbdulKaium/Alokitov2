@extends('layouts.master')
<!-- page content -->
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-plus-square"></i> Online Application        </h1>
            <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/admission/default/index">Enquiry</a></li>
                <li><a href="/admission/stu-admission-master/index">Manage Enquiry</a></li>
                <li class="active">Online Application</li>
            </ul>    </section>
        <section class="content">
            <div class="box box-solid">
                <form id="admission-form" action="/admission/stu-admission-master/create" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_csrf" value="a1N3N1RKdnIjGAUBDHoAIyILKE0mJTFHMWo.Q2AGAh8FN05dNnsiIg==">
                    <div class="box-body">
                        <div class="error-summary" style="display:none"><p>Please fix the following errors:</p><ul></ul></div>
                        <!-- Personal Information -->
                        <fieldset>
                            <legend>
                                <abbr title="Enter your personal information like. First name, Middle name, Last name, Gender, DOB, etc." data-toggle = "tooltip">
                                    <i class="fa fa-user"></i>
                                    Personal Information            </abbr>
                            </legend>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group field-stuadmissionmaster-stu_title required">
                                        <label class="control-label" for="stuadmissionmaster-stu_title">Title</label>
                                        <select id="stuadmissionmaster-stu_title" class="form-control" name="StuAdmissionMaster[stu_title]" aria-required="true">
                                            <option value="">--- Select Title ---</option>
                                            <option value="1">Mr.</option>
                                            <option value="2">Mrs.</option>
                                            <option value="3">Ms.</option>
                                            <option value="4">Prof.</option>
                                            <option value="5">Dr.</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>            </div>
                                <div class="col-sm-4">
                                    <div class="form-group field-stuadmissionmaster-stu_first_name required">
                                        <label class="control-label" for="stuadmissionmaster-stu_first_name">First Name</label>
                                        <input type="text" id="stuadmissionmaster-stu_first_name" class="form-control" name="StuAdmissionMaster[stu_first_name]" maxlength="50" placeholder="Enter First Name" aria-required="true">
                                        <div class="help-block"></div>
                                    </div>            </div>
                                <div class="col-sm-4">
                                    <div class="form-group field-stuadmissionmaster-stu_middle_name">
                                        <label class="control-label" for="stuadmissionmaster-stu_middle_name">Middle Name</label>
                                        <input type="text" id="stuadmissionmaster-stu_middle_name" class="form-control" name="StuAdmissionMaster[stu_middle_name]" maxlength="50" placeholder="Enter Middle Name">
                                        <div class="help-block"></div>
                                    </div>            </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group field-stuadmissionmaster-stu_last_name required">
                                        <label class="control-label" for="stuadmissionmaster-stu_last_name">Last Name</label>
                                        <input type="text" id="stuadmissionmaster-stu_last_name" class="form-control" name="StuAdmissionMaster[stu_last_name]" maxlength="50" placeholder="Enter Last Name" aria-required="true">
                                        <div class="help-block"></div>
                                    </div>            </div>
                                <div class="col-sm-4">
                                    <div class="form-group field-stuadmissionmaster-stu_gender">
                                        <label class="control-label" for="stuadmissionmaster-stu_gender">Gender</label>
                                        <select id="stuadmissionmaster-stu_gender" class="form-control" name="StuAdmissionMaster[stu_gender]">
                                            <option value="">--- Select Gender ---</option>
                                            <option value="1">Male</option>
                                            <option value="2">Female</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>            </div>
                                <div class="col-sm-4">
                                    <div class="form-group field-stuadmissionmaster-stu_dob required">
                                        <label class="control-label" for="stuadmissionmaster-stu_dob">Date of Birth</label>
                                        <input type="text" id="stuadmissionmaster-stu_dob" class="form-control" name="StuAdmissionMaster[stu_dob]" placeholder="Select Date of Birth" readOnly>
                                        <div class="help-block"></div>
                                    </div>            </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group field-stuadmissionmaster-stu_email_id required">
                                        <label class="control-label" for="stuadmissionmaster-stu_email_id">Email Id</label>
                                        <input type="text" id="stuadmissionmaster-stu_email_id" class="form-control" name="StuAdmissionMaster[stu_email_id]" maxlength="65" placeholder="Enter Email ID" aria-required="true">
                                        <div class="help-block"></div>
                                    </div>            </div>
                                <div class="col-sm-4">
                                    <div class="form-group field-stuadmissionmaster-stu_religion">
                                        <label class="control-label" for="stuadmissionmaster-stu_religion">Religion</label>
                                        <input type="text" id="stuadmissionmaster-stu_religion" class="form-control" name="StuAdmissionMaster[stu_religion]" maxlength="50" placeholder="Enter Religion">
                                        <div class="help-block"></div>
                                    </div>            </div>
                                <div class="col-sm-4">
                                    <div class="form-group field-stuadmissionmaster-stu_master_nationality_id">
                                        <label class="control-label" for="stuadmissionmaster-stu_master_nationality_id">Nationality</label>
                                        <select id="stuadmissionmaster-stu_master_nationality_id" class="form-control" name="StuAdmissionMaster[stu_master_nationality_id]">
                                            <option value="">--- Select Nationality ---</option>
                                            <option value="1">French</option>
                                            <option value="2">German</option>
                                            <option value="3">British</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>            </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group field-stuadmissionmaster-stu_master_category">
                                        <label class="control-label" for="stuadmissionmaster-stu_master_category">Category</label>
                                        <select id="stuadmissionmaster-stu_master_category" class="form-control" name="StuAdmissionMaster[stu_master_category]">
                                            <option value="">--- Select Category ---</option>
                                            <option value="1">Overseas</option>
                                            <option value="2">Domestic</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>            </div>
                                <div class="col-sm-4">
                                    <div class="form-group field-stuadmissionmaster-stu_mobile_no">
                                        <label class="control-label" for="stuadmissionmaster-stu_mobile_no">Mobile No</label>
                                        <input type="text" id="stuadmissionmaster-stu_mobile_no" class="form-control" name="StuAdmissionMaster[stu_mobile_no]" maxlength="12" placeholder="Enter Mobile No.">
                                        <div class="help-block"></div>
                                    </div>            </div>
                                <div class="col-sm-4">
                                    <div class="form-group field-stuadmissionmaster-stu_source_type">
                                        <label class="control-label" for="stuadmissionmaster-stu_source_type">Source Type</label>
                                        <select id="stuadmissionmaster-stu_source_type" class="form-control" name="StuAdmissionMaster[stu_source_type]">
                                            <option value="">--- Select Source Type ---</option>
                                            <option value="1">Walk-In</option>
                                            <option value="2">Telephonic</option>
                                            <option value="3">Advertisement</option>
                                            <option value="4">Website</option>
                                            <option value="5">News Paper</option>
                                            <option value="6">Seminar</option>
                                            <option value="7">Student Reference</option>
                                            <option value="8">Email</option>
                                            <option value="9">Facebook</option>
                                            <option value="10">Twitter</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>            </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group field-stuadmissionmaster-profileimage">
                                        <label class="control-label" for="stuadmissionmaster-profileimage">Profile Image</label>
                                        <input type="hidden" name="StuAdmissionMaster[profileImage]" value=""><input type="file" id="stuadmissionmaster-profileimage" name="StuAdmissionMaster[profileImage]">
                                        <div class="hint-block">NOTE : Upload only JPG, JPEG and PNG images and smaller than 300KB</div>
                                        <div class="help-block"></div>
                                    </div>         </div>
                            </div>
                        </fieldset>
                        <!-- Address Information -->
                        <fieldset>
                            <legend>
                                <abbr title="Enter your address information" data-toggle = "tooltip">
                                    <i class="fa fa-home"></i>
                                    Address Information            </abbr>
                            </legend>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group field-stuadmissionmaster-stu_padd">
                                        <label class="control-label" for="stuadmissionmaster-stu_padd">Address</label>
                                        <textarea id="stuadmissionmaster-stu_padd" class="form-control" name="StuAdmissionMaster[stu_padd]" maxlength="255" placeholder="Enter Address"></textarea>
                                        <div class="help-block"></div>
                                    </div>            </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group field-stuadmissionmaster-stu_padd_country">
                                        <label class="control-label" for="stuadmissionmaster-stu_padd_country">Country</label>
                                        <select id="stuadmissionmaster-stu_padd_country" class="form-control" name="StuAdmissionMaster[stu_padd_country]" onchange="$.get( &quot;/dependent/getstate&quot;, { id: $(this).val() })
         .done(function( data ) {
         $( &quot;#stuadmissionmaster-stu_padd_state&quot; ).html( data );
         });">
                                            <option value="">--- Select Country ---</option>
                                            <option value="1">England</option>
                                            <option value="2">France</option>
                                            <option value="3">Germany</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>            </div>
                                <div class="col-sm-4">
                                    <div class="form-group field-stuadmissionmaster-stu_padd_state">
                                        <label class="control-label" for="stuadmissionmaster-stu_padd_state">State</label>
                                        <select id="stuadmissionmaster-stu_padd_state" class="form-control" name="StuAdmissionMaster[stu_padd_state]" onchange="
         $.get( &quot;/dependent/getcity&quot;, { id: $(this).val() } )
         .done(function( data ) {
         $( &quot;#stuadmissionmaster-stu_padd_city&quot; ).html( data );
         });">
                                            <option value="">--- Select State ---</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>            </div>
                                <div class="col-sm-4">
                                    <div class="form-group field-stuadmissionmaster-stu_padd_city">
                                        <label class="control-label" for="stuadmissionmaster-stu_padd_city">City</label>
                                        <select id="stuadmissionmaster-stu_padd_city" class="form-control" name="StuAdmissionMaster[stu_padd_city]">
                                            <option value="">--- Select City ---</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>            </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group field-stuadmissionmaster-stu_padd_pincode">
                                        <label class="control-label" for="stuadmissionmaster-stu_padd_pincode">Pincode</label>
                                        <input type="text" id="stuadmissionmaster-stu_padd_pincode" class="form-control" name="StuAdmissionMaster[stu_padd_pincode]" maxlength="6" placeholder="Enter Pincode">
                                        <div class="help-block"></div>
                                    </div>         </div>
                                <div class="col-sm-4">
                                    <div class="form-group field-stuadmissionmaster-stu_padd_house_no">
                                        <label class="control-label" for="stuadmissionmaster-stu_padd_house_no">House No</label>
                                        <input type="text" id="stuadmissionmaster-stu_padd_house_no" class="form-control" name="StuAdmissionMaster[stu_padd_house_no]" maxlength="25" placeholder="Enter House No.">
                                        <div class="help-block"></div>
                                    </div>         </div>
                                <div class="col-sm-4">
                                    <div class="form-group field-stuadmissionmaster-stu_padd_phone_no">
                                        <label class="control-label" for="stuadmissionmaster-stu_padd_phone_no">Phone No</label>
                                        <input type="text" id="stuadmissionmaster-stu_padd_phone_no" class="form-control" name="StuAdmissionMaster[stu_padd_phone_no]" maxlength="25" placeholder="Enter Phone No.">
                                        <div class="help-block"></div>
                                    </div>         </div>
                            </div>
                        </fieldset>
                        <!-- Select Course & Batch -->
                        <fieldset>
                            <legend>
                                <abbr title="You should select batch and course for admission. Process is first select course that you want to study than after base on course selection you need to select batch." data-toggle = "tooltip">
                                    <i class="fa fa-graduation-cap"></i>
                                    Select Course & Batch            </abbr>
                            </legend>
                            <div class="row">
                                <div class="hidden field-stuadmissionmaster-stu_academic_year required">
                                    <input type="hidden" id="stuadmissionmaster-stu_academic_year" class="form-control" name="StuAdmissionMaster[stu_academic_year]" value="2">
                                    <div class="help-block"></div>
                                </div>                     <div class="col-sm-4">
                                    <div class="form-group field-stuadmissionmaster-stu_course_id required">
                                        <label class="control-label" for="stuadmissionmaster-stu_course_id">Course</label>
                                        <select id="stuadmissionmaster-stu_course_id" class="form-control" name="StuAdmissionMaster[stu_course_id]" onchange="$.get(&quot;/dependent/course-batch&quot;, {
         cid : $(this).val(),
         yid : $(&quot;#stuadmissionmaster-stu_academic_year&quot;).val()
         }).done(function(data) {
         $( &quot;#stuadmissionmaster-stu_batch_id&quot;).html(data);
         });" aria-required="true">
                                            <option value="">--- Select Course ---</option>
                                            <option value="5">Computer Science</option>
                                            <option value="6">Mathematics</option>
                                            <option value="7">Biochemistry</option>
                                            <option value="8">Agric Science</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>         </div>
                                <div class="col-sm-4">
                                    <div class="form-group field-stuadmissionmaster-stu_batch_id required">
                                        <label class="control-label" for="stuadmissionmaster-stu_batch_id">Batch</label>
                                        <select id="stuadmissionmaster-stu_batch_id" class="form-control" name="StuAdmissionMaster[stu_batch_id]" aria-required="true">
                                            <option value="">--- Select Batch ---</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>         </div>
                            </div>
                        </fieldset>
                        <!-- Upload Documents -->
                        <fieldset>
                            <legend>
                                <abbr title="You need to upload required documents" data-toggle = "tooltip">
                                    <i class="fa fa-upload"></i>
                                    Upload Documents        </abbr>
                            </legend>
                            <div class="dynamicform_wrapper" data-dynamicform="dynamicform_4b249215"><body><table class="table table-bordered table-striped margin-b-none">
                                    <colgroup>
                                        <col style="width:150px">
                                        <col style="width:200px">
                                        <col style="width:200px">
                                        <col style="width:10px">
                                    </colgroup>
                                    <thead><tr>
                                        <th>Document Category</th>
                                        <th>Document Details</th>
                                        <th>Document File</th>
                                        <th></th>
                                    </tr></thead>
                                    <tbody class="form-options-body">
                                    <tr class="form-options-item"><td class="vcenter">
                                            <div class="form-group field-stuadmissiondocs-0-stu_docs_category_id required">

                                                <select id="stuadmissiondocs-0-stu_docs_category_id" class="form-control" name="StuAdmissionDocs[0][stu_docs_category_id]"><option value="">--- Select Category ---</option><option value="1">Birth Certificate</option></select><div class="help-block"></div>
                                            </div>                    </td>
                                        <td class="vcenter">
                                            <div class="form-group field-stuadmissiondocs-0-stu_docs_details">

                                                <input id="stuadmissiondocs-0-stu_docs_details" class="form-control" name="StuAdmissionDocs[0][stu_docs_details]" maxlength="128" type="text"><div class="help-block"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group field-stuadmissiondocs-0-doc_file">

                                                <input name="StuAdmissionDocs[0][doc_file]" value="" type="hidden"><input id="stuadmissiondocs-0-doc_file" name="StuAdmissionDocs[0][doc_file]" accept="image/*, application/*, text/plain, .pdf" type="file"><div class="hint-block">NOTE : Upload only JPG, JPEG, PNG, TXT and PDF file and smaller than 512KB</div>
                                                <div class="help-block"></div>
                                            </div>                                            </td>
                                        <td class="text-center vcenter">
                                            <button type="button" class="delete-item btn btn-danger btn-xs"><i class="fa fa-minus"></i></button>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tfoot><tr>
                                        <td colspan="4" class="text-center">
                                            <button type="button" class="add-item btn btn-success btn-sm">
                                                <span class="fa fa-plus"></span> Add Document
                                            </button>
                                        </td>
                                    </tr></tfoot>
                                </table>
                                </body>
                            </div></fieldset>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary btn-create">Create</button>   <a class="btn btn-default btn-create" href="/admission/stu-admission-master/index">Cancel</a></div><!-- /.box-footer-->
                </form></div>
        </section>
    </div>
@endsection

@section('scripts')


@endsection

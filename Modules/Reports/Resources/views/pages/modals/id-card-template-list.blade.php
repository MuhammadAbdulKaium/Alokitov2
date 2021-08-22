<style>
    .temp_one_font_side{
        width: 250px;
        height: 350px;
        border: 2px solid #3498db;
        float: left;
    }
    .temp_one_backSide {
        float: left;
        width: 250px;
        height: 350px;
        border: 2px solid #3498db;
        margin-left: 50px;
    }
    .tem_id_header {
        background: #3498db;
        height: 40px;
        text-align: center;
    }
    .school-logo {
        margin-top: 10px;
        height: 60px;
    }
    .temp_one_institute_name {
        margin-top: 32px;
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        padding: 0px;
        line-height: 2px;
    }
    .temp_one_address_name {
        font-size: 12px;
        font-weight:600;
        line-height: 10px;
        text-align: center;
    }
    .temp_one_id-card-heading {
        width: 70%;
        margin: 0 auto;
        background: blue;
        color: #fff;
        font-weight: 700;
        text-align: center;
        font-size: 12px;
    }

    .temp_one_student_name {
        text-align: center;
        font-size: 12px;
        font-weight: bold;
        margin-top: 5px;
    }
    .table {
        font-size: 12px;
        width: 90%;
        margin: 0 auto;
    }
    .table>tbody>tr>td {
        padding: 1px !important;
    }
    .temp_one_principal_section {
        float: right;
        margin-right: 15px;
        height: 46px;
    }
    .temp_one_principal {
        font-size: 12px;
    }
    .tem_one_footer {
        background: #3498db;
        height: 5px;
        clear: both;
    }
    .tem_one_per {
        font-size: 20px;
        font-style: italic;
        padding: 5px;
        text-align: center;
    }
    .father_info p {
        line-height: 8px;
        font-size: 12px;
        font-style: italic;
        padding-left: 5px;
    }
    .tem_one_note {
        width: 40%;
        text-align: center;
        margin: 0 auto; }
    .tem_one_note p {
        border-bottom: 1px solid #3498db;
        border-top: 1px solid #3498db;
        font-size: 20px;
    }
    .tem_one_retun_address {
        text-align: center;
        line-height: 12px;
        font-size: 12px;
    }
    .temp_one_back_institute_name {
        font-size: 14px;
        font-weight: 600;
    }
    .temp_one_valid {
        margin-top: 40px;
        font-size: 12px;
        text-align: center;
    }
    .temp_one_all_content {
        height: 341px;
    }
    .box-footer {
        border: none !important;
    }

</style>
<form id="id_card_setting_from" enctype="multipart/form-data" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="id_card_setting_id" @if(!empty($templateProfile)) value="{{$templateProfile->id}}" @endif>
    <div class="row">
        {{--<div class="col-md-6">--}}
            {{--<div class="radio">--}}
                {{--<label><input type="radio" value="1"  name="temp_number">Template One</label>--}}
            {{--</div>--}}
            {{--<div class="temp_one_font_side">--}}
                {{--<div class="temp_one_all_content">--}}
                    {{--<div class="tem_id_header">--}}
                        {{--<img class="school-logo" src="https://2.bp.blogspot.com/-QEfdJEYvMmQ/WRbbp4AgHVI/AAAAAAAAEbM/gFn8NA0jqe8szMzNH3blHINQLPtDOs3JACLcB/s1600/M%2BM%2BCollege%2BLogo.png">--}}
                    {{--</div>--}}
                    {{--<p class="temp_one_institute_name">School Name</p>--}}
                    {{--<p class="temp_one_address_name">School Address</p>--}}
                    {{--<div class="temp_one_id-card-heading">--}}
                        {{--<p>STUDENT'S IDENTITY CARD</p>--}}
                    {{--</div>--}}

                    {{--<div class="temp_one_profile text-center">--}}
                        {{--<img class="temp_one_profile_pic" style="height:60px;" src="http://petmedmd.com/images/user-profile.png">--}}
                    {{--</div>--}}
                    {{--<p class="temp_one_student_name">Student Name</p>--}}
                    {{--<table class="table table-bordered">--}}
                        {{--<tr>--}}
                            {{--<td>Class</td>--}}
                            {{--<td>Eleven</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<td>ID</td>--}}
                            {{--<td>1836799364</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<td>Roll</td>--}}
                            {{--<td>1</td>--}}
                        {{--</tr>--}}
                    {{--</table>--}}
                    {{--<div class="temp_one_principal_section">--}}
                        {{--<img class="temp_one_principal_sig" style="height: 30px" src="https://camo.githubusercontent.com/59e9997c538fc5b1147b51c70540065423c30fcf/68747470733a2f2f662e636c6f75642e6769746875622e636f6d2f6173736574732f393837332f3236383034362f39636564333435342d386566632d313165322d383136652d6139623137306135313030342e706e67">--}}
                        {{--<p class="temp_one_principal">Principal</p>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="tem_one_footer">--}}

                {{--</div>--}}

            {{--</div>--}}

            {{--<div class="temp_one_backSide">--}}
                {{--<div class="temp_one_all_content">--}}
                    {{--<p class="tem_one_per">Student Information</p>--}}
                    {{--<div class="father_info">--}}
                        {{--<p><strong>Father's Name:</strong> </p>--}}
                        {{--<p><strong>Address:</strong></p>--}}
                        {{--<p><strong>Mobile No:</strong> </p>--}}
                    {{--</div>--}}
                    {{--<div class="tem_one_note">--}}
                        {{--<p>NOTE</p>--}}
                    {{--</div>--}}
                    {{--<div class="tem_one_retun_address">--}}
                        {{--<p>If Found Please Return This Card to</p>--}}
                        {{--<p class="temp_one_back_institute_name">Institute Name</p>--}}
                        {{--<p>Address Here </p>--}}
                        {{--<p><strong>Mobile No:</strong> </p>--}}
                        {{--<p>or the Nearest Police Station </p>--}}
                    {{--</div>--}}

                    {{--<p class="temp_one_valid">This card is valid till academic year 2019</p>--}}

                {{--</div>--}}
                {{--<div class="tem_one_footer">--}}

                {{--</div>--}}

            {{--</div>--}}
        {{--</div>--}}
        <div class="col-md-6">
            <div class="form-group">
                <label for="pwd">Principal Singature:</label>
                <input type="file" class="form-control" id="singature" required name="signature">
            </div>
            <div class="form-group">
                <label for="pwd">ID Card Valid:</label>
                <input type="text" class="form-control" required id="idcard_valid" name="idcard_valid">
            </div>
        </div>
        <div class="box-footer" style="clear: both;">
            <button type="submit" class="btn btn-info">Submit</button>
        </div>
    </div>

</form>



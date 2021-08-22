<div class="row">
    <form action="/reports/documents/customised-testimonial" method="post" target="_blank">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <hr/>
        <div class="col-md-12 text-center">
            <div class="col-md-2 col-md-offset-1">
                <strong>  Center Code: {{$instituteProfile->center_code}} </strong>
            </div>
            <div class="col-md-2">
                <strong>   Institute Code: {{$instituteProfile->institute_code}} </strong>
            </div>
            <div class="col-md-2">
                <strong> EIIN: {{$instituteProfile->eiin_code}} </strong>
            </div>
            <div class="col-md-2">
                <strong> Upazilla Code: {{$instituteProfile->upazila_code}}  </strong>
            </div>
            <div class="col-md-2">
                <strong> Zilla Code: {{$instituteProfile->zilla_code}}  </strong>
            </div>
        </div>

        <div class="col-md-12 text-center">
            <img src="{{URL::asset('assets/users/images/'.$instituteProfile->logo)}}" height="80px" width="80px" style="border-radius:50%" alt="profile" class="pro-pic">
            <h1><strong>{{$instituteProfile->institute_name}}</strong></h1>
            <h4><strong>{{$instituteProfile->address1}}</strong></h4>
            <h2><strong>TESTIMONIAL</strong></h2>
           Font Size: <input id="font_size" type="text" name="font_size" style="width: 62px" value="15" placeholder=" Font Size "> px,
           Border Color: <input id="border_color" type="color" name="border_color" style="width: 100px" value="#000000">
            <br/>
            <br/>
        </div>

        <div class="col-md-12">
            <p>
                I am highly delighted to certify that
                <input id="s_name" type="text" name="s_name" style="width: 200px" placeholder=" Student's Name">

                <select id="gender" name="gender" required="">
                    <option value="">--- Select gender ---</option>
                    <option value="son">son</option>
                    <option value="daughter">daughter</option>
                </select>

                of
                <input id="f_name" type="text" name="f_name" style="width: 200px" placeholder=" Father's name">

                and
                <input id="m_name" type="text" name="m_name" style="width: 200px" placeholder=" Mothers's Name">

                of village
                <input id="village" type="text" name="village" style="width: 150px" placeholder=" Village Name">

                <br/>
                <br/>
                Post Office
                <input id="post" type="text" name="post" style="width: 200px" placeholder=" Post Office">

                Thana/Upazilla
                <input id="upzilla" type="text" name="upzilla" style="width: 200px" placeholder=" Thana / Upazilla">

                District
                <input id="zilla" type="text" name="zilla" style="width: 200px" placeholder=" District">


                passed the

                <select id="exam" name="exam" required="">
                    <option value="">--- Select Exam ---</option>
                    <option value="Secondary School">Secondary School</option>
                    <option value="Higher Secondary">Higher Secondary</option>
                </select>

                Certificate Examination from this <br/><br/> institution under the board of Intermediate and Secondary Education,

                {{--<input id="board" type="text" name="board" style="width: 200px" placeholder=" Education Board">--}}
                <select id="board" name="board" required="">
                    <option value="">--- Select Board ---</option>
                    <option value="Barisal">Barisal</option>
                    <option value="Chittagong">Chittagong</option>
                    <option value="Comilla">Comilla</option>
                    <option value="Dhaka">Dhaka</option>
                    <option value="Dinajpur">Dinajpur</option>
                    <option value="Jessore">Jessore</option>
                    <option value="Mymensingh">Mymensingh</option>
                    <option value="Rajshahi">Rajshahi</option>
                    <option value="Sylhet">Sylhet</option>
                    <option value="Madrasah">Madrasah</option>
                    <option value="Technical">Technical</option>
                </select>

                held in
                <input id="year" type="text" name="year" style="width: 100px" placeholder=" Exam Year">

                in
                <select id="group" name="group" required="">
                    <option value="">--- Select Group ---</option>
                    <option value="Science">Science</option>
                    <option value="Humanities">Humanities</option>
                    <option value="Business Studies">Business Studies</option>
                </select>

                group. He/She obtained G.P.A
                <input id="gpa" type="text" name="gpa" style="width: 100px" placeholder=" Obtained GPA">

                <br/>
                <br/>

                Grade
                <input id="grade" type="text" name="grade" style="width: 100px" placeholder=" Letter grade">.

                His/Her Roll
                <input id="center" type="text" name="center" style="width: 150px" placeholder=" Center name">
                No
                <input id="roll" type="text" name="roll" style="width: 100px" placeholder=" Roll No">

                Registration No
                <input id="reg" type="text" name="reg" style="width: 150px" placeholder=" Reg. No">

                Session
                <input id="session" type="text" name="session" style="width: 150px" placeholder=" Session">


                Date Of Birth
                <input id="dob" type="text" name="dob" style="width: 150px" placeholder=" Birth Date">.


                <br/>
                <br/>

                As far as know he/she bears a Good moral Character. He/She did't take part in any subversive activity against the state and society during his/her staying in the institution.

                <br/>
                <br/>
                I wish his/her all success in life
            </p>
        </div>
        <div class="col-md-12">
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <div class="col-sm-5 text-left">
                <p> Prepared and Composed by <input id="writer" type="text" name="writer" style="width: 150px" placeholder=" Composer Name"></p>
                <p> Date <input id="date" type="text" name="date" style="width: 150px" placeholder=" Composed Date"></p>
            </div>
            <div class="col-sm-2 text-center">
                <button  class="btn btn-primary" type="submit"> Submit </button>
            </div>
            <div class="col-sm-5 text-right">
                <h3> Principal</h3>
            </div>
        </div>
    </form>
</div>
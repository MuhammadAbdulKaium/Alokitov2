@foreach($studentList  as $std)
    <div class="chat_list">
        <div class="chat_people">
            <div class="chat_ib">
                <h5>{{ $std->middle_name }} <span class="chat_date">
            @if($std->attendance_status==0)    
             <span class="offline_icon"> 
            </span>
            @else
            <span class="online_icon">  
            </span>
            @endif

                </span>
            </h5>
            </div>
        </div>
    </div>
@endforeach

         <div class="modal-header">
            <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title"><i class="fa fa-eye"></i> Details Records</h4>
         </div>

            <div class="modal-body">
{{--                {{$examDeatils}}--}}

                <table class="table table-striped table-bordered text-center">
                    <thead>
                    <tr>
                        <th>Subject name</th>
                        <th>Points</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($examDeatils as $academic)
                        <tr>
                            <td>{{$academic->subjectName()->activity_name}}</td>
                            <td>{{$academic->activity_point}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">

               <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
            </div>




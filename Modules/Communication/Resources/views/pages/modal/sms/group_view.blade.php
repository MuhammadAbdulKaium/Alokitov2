
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{$groupProfile->group_name}}</h4>
            </div>
            <div class="modal-body">
                <div class="row" style="padding: 10px">
                    @if(!empty($groupNumberList) && ($groupNumberList->count()>0))
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>SL.</th>
                            <th>Mobile Number</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        @foreach($groupNumberList as $number)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$number->mobile_number}}</td>
                        </tr>
                            @endforeach
                        </tbody>
                    </table>
                        @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>



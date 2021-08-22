<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">{{ $pageTitle }} </h4>
        </div>
        <div class="modal-body">
                <table id="w1" class="table table-striped table-bordered detail-view">
                    @foreach($campus as $value)
                        <tr class="odd">
                            <th class="col-sm-3">Name</th>
                            <td class="col-sm-3">{{$value->name}}</td>
                            <th class="col-sm-3">Campus Code</th>
                            <td class="col-sm-3">{{$value->campus_code}}</td>
                        </tr>
                        <tr class="even">
                            <th colspan="" class="col-sm-3">Address</th>
                            <td  class="col-sm-3" align="left">
                                {{$value->address()?$value->address()->address:'' }}
                            </td>

                        </tr>
                        <tr class="odd">
                            <th class="col-sm-3">House</th>
                            <td class="col-sm-3">{{$value->address()?$value->address()->house:''}}</td>
                            <th class="col-sm-3">Phone</th>
                            <td class="col-sm-3">{{$value->address()?$value->address()->phone:''}}</td>
                        </tr>
                        <tr class="even">
                            <th class="col-sm-3">Street</th>
                            <td class="col-sm-3">{{$value->address()?$value->address()->street:''}}</td>
                            <th class="col-sm-3">Area</th>
                            <td class="col-sm-3">{{$value->address()?$value->address()->city()->name:'' }}</td>
                        </tr>

                        <tr class="even">
                            <th class="col-sm-3">City</th>
                            <td class="col-sm-3">{{$value->address()?$value->address()->state()->name:'' }}</td>
                            <th class="col-sm-3">Zip</th>
                            <td class="col-sm-3">{{$value->address()?$value->address()->zip:'' }}</td>
                        </tr>
                        <tr class="even">
                            <th class="col-sm-3">Country</th>
                            <td class="col-sm-3">{{$value->address()?$value->address()->country()->name:'' }}</td>
                            <th class="col-sm-3">Phone</th>
                            <td class="col-sm-3">{{$value->address()?$value->address()->phone:'' }}</td>
                        </tr>
                    @endforeach
                </table>
        </div>
    </div>

    <div class="modal-footer">
        <a data-dismiss="modal" class="btn btn-default" type="button"> Close </a>
    </div>

</div>
</div>

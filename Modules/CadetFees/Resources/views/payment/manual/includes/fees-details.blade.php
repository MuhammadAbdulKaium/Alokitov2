<div class="col-md-12">
    <div class="box box-solid">
        <div class="et">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i> View Cadet Info</h3>
            </div>
        </div>

        <div class="card">
            @if($userDetails)
                <table class="table-bordered">
                    <tr>
                        <th>Name:</th>
                        <td>{{$userDetails->title}} {{$userDetails->first_name}} {{$userDetails->last_name}}</td>
                    </tr>
                </table>
            @endif
            @if(count($feesCheck)>0)
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Academic Year</th>
                            <th>Academic Level</th>
                            <th>Batch</th>
                            <th>Section</th>
                            <th>Month Name</th>
                            <th>Fees</th>
                            <th>Regular Fine</th>
                            <th>Fine Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($feesCheck as $fees)
                        <tr>
                        <td>{{$fees->year_name}}</td>
                        <td>{{$fees->level_name}}</td>
                        <td>{{$fees->batch_name}}</td>
                        <td>{{$fees->section_name}}</td>
                        <td>{{$fees->month_name==1?'January':'December'}}</td>
                        <td>{{$fees->fees}}</td>
                        <td>{{$fees->late_fine}}</td>
                        <td>{{$fees->fine_type==1?"Per Day":"Fixed"}}</td>
                        <td>{{$fees->status==0?"Due":"Paid"}}</td>
                        <td>
                            @if($fees->status==0)
                            <a id="update-guard-data" class="btn btn-success float-right" href="/cadetfees/calculate/fees/manually/{{$fees->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-user-plus" aria-hidden="true"></i> Payments</a>
                            @endif

                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                @else
                <p>No Fees Generate for this cadet</p>
            @endif
        </div>
        </div>
</div>
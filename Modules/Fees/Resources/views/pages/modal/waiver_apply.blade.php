<div class="modal-dialog">
    <div class="modal-content"><div class="modal-dialog">
            <div class="modal-content">
                <form id="add" action="/fees/invoice/add-waiver/{{$invoiceId}}" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <p>
                        </p><h4>
                            <i class="fa fa-hand-o-left"></i> Student Waiver</h4>
                        <p></p>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Percent/Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        @if($studentdWaiverProfile->type=="1")
                                        <span class="label label-primary">Percent</span>
                                        @else
                                        <span class="label label-info">Amount</span>
                                            @endif
                                    </td>
                                    <td> @if($studentdWaiverProfile->type=="1")
                                            {{$studentdWaiverProfile->value}} %
                                        @else
                                            {{$studentdWaiverProfile->value}} TK.
                                        @endif</td>
                                </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div><!--/ end modal-body-->

                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
                        <div class="returnBook">
                            <button type="submit" class="btn btn-info pull-left">Apply Waiver</button>	</div>
                    </div><!--/ end modal-footer-->

                </form>
               </div>
        </div></div>
</div>
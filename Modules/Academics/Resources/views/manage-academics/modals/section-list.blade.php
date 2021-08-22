        <div class="modal-header">
           <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
           <h4 class="modal-title"><i class="fa fa-plus-square"></i> All Section</h4>
        </div>
           <div class="modal-body">
             <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
                <p class="text-center">Showing All Sections of <strong>{{$batchProfile->batch_name}} @if($batchProfile->get_division())({{$batchProfile->get_division()->name}})@endif</strong></p>
                    <table class="table table-bordered text-center">
                       <tbody>
                          <tr>
                            <th>Section Name</th>
                            <th>Intake</th>                            
                            <th>Batch Name</th>
                            <th>Academic Year</th>
                          </tr>
                          @foreach($batchProfile->section() as $section)
                          <tr>
                            <td>{{$section->section_name}}</td>
                            <td>{{$section->intake}}</td>                           
                            <td>{{$section->batchName()->batch_name}} @if($batchProfile->get_division())({{$batchProfile->get_division()->name}})@endif</td>
                            <td>{{$section->academicsYear()->year_name}}</td>
                          </tr>
                          @endforeach
                       </tbody>
                    </table>
                </div>
             </div>            
           </div>
           <!--./modal-body-->
           <div class="modal-footer">
              <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
           </div>
           <!--./modal-footer-->
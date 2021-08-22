@extends('student::pages.student-profile.profile-layout')

@section('profile-content')
                      <p class="text-right">
                         <a id="document-upload" class="btn btn-success btn-sm" href="/student/documents/create/{{ $personalInfo->id }}" data-target="#globalModal" data-toggle="modal"><i class="fa fa-plus-square" aria-hidden="true"></i>Add</a>
                      </p>

                      @if($attchments = $personalInfo->allAttachment())                      
                      <div class="table-responsive" style="overflow-x:inherit">
                         <div id="w0" class="grid-view">
                            <table class="table table-striped table-bordered">
                               <thead>
                                  <tr>
                                     <th>#</th>
                                     <th>Category</th>
                                     <th>Document Details</th>
                                     <th>Submited Date</th>
                                     <th>Status</th>
                                     <th class="text-center">Download</th>
                                     <th class="text-center">Action</th>
                                  </tr>
                               </thead>
                               <tbody>
                                  @php $i=1; @endphp
                                  @foreach($attchments as $attachment)
                                  <tr data-key="5">
                                     @if($attachment->doc_type=="PROFILE_PHOTO") @continue @endif
                                     <td>{{$i++}}</td>
                                     <td>{{ $attachment->doc_type }}</td>
                                     <td>{{ $attachment->doc_details }}</td>
                                     <td>{{ date('F d, Y', strtotime($attachment->doc_submited_at)) }}</td>
                                     <td>
                                        @if($attachment->doc_status==0) 
                                          <span class="label label-info">Pending</span>
                                        @else
                                          <span class="label label-success">Approved</span>
                                        @endif
                                     </td>
                                     <td class="text-center"><a class="btn btn-default btn-sm" href="/{{$attachment->singleContent()->path.'/'.$attachment->singleContent()->name}}" title="Click here to download" download><i class="fa fa-download" aria-hidden="true"></i></a></td>
                                     <td class="text-center">
                                        <div class="btn-group">
                                           <button id="w1" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span></button>
                                           <ul id="w2" class="dropdown-menu dropdown-menu-right">
                                              <li>
                                                @if($attachment->doc_status==1)
                                                <a href="/student/documents/status/{{$attachment->id}}/0" data-confirm="Are you sure you want to disapprove this document?" tabindex="-1">Pending</a>
                                                @endif

                                                @if($attachment->doc_status==0)
                                                <a href="/student/documents/status/{{$attachment->id}}/1" data-confirm="Are you sure you want to approve this document?" tabindex="-1">Approve</a>
                                                @endif
                                              </li>
                                              <li><a id="update-document" href="/student/documents/edit/{{$attachment->id}}" title="Update Document" data-target="#globalModal" data-toggle="modal" tabindex="-1">Edit</a></li>
                                              <li><a href="/student/documents/delete/{{$attachment->id}}" title="Delete Document" data-confirm="Are you sure you want to delete this document?" data-method="get" tabindex="-1">Delete</a></li>
                                           </ul>
                                        </div>
                                     </td>
                                  </tr>
                                  @endforeach
                               </tbody>
                            </table>
                         </div>
                      </div>
                      @else
                        <div class="alert bg-warning text-warning">
                          <i class="fa fa-warning"></i> No record found.        
                        </div>
                      @endif
@endsection
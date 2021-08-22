
@extends('employee::layouts.profile-layout')

@section('profile-content')
  <p class="text-right">
     <a id="document-upload" class="btn btn-success btn-sm" href="/employee/profile/document/create/{{$employeeInfo->id}}" data-target="#globalModal" data-toggle="modal"><i class="fa fa-plus-square" aria-hidden="true"></i>Add</a>
  </p>

  <table class="table table-bordered">
      <thead>
      <tr>
          <th>SL</th>
          <th>Category</th>
          <th>Details</th>
          <th>Submitted At</th>
          <th>File</th>
          <th>Action</th>
      </tr>
      </thead>
      <tbody>
          @foreach($documents as $document)
              <tr>
                  <td>{{ $loop->index+1 }}</td>
                  <td>{{ $document->document_category }}</td>
                  <td>{{ $document->document_details }}</td>
                  <td>{{ \Carbon\Carbon::parse($document->document_submitted_at)->format('d/m/Y') }}</td>
                  <td>
                      <img src="{{ asset('/employee-attachment/'.$document->document_file) }}" style="width: 40px" alt="">
                  </td>
                  <td>
                      <a class="btn btn-primary btn-xs" href="{{ url('employee/profile/documents/edit/'.$document->id) }}"
                         data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i
                                  class="fa fa-edit"></i></a>
                      <a href="{{ url('employee/profile/documents/delete/'.$document->id) }}" class="btn btn-danger btn-xs"
                         onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                         data-content="delete"><i class="fa fa-trash-o"></i></a>
                  </td>
              </tr>
          @endforeach
      </tbody>
  </table>
@endsection

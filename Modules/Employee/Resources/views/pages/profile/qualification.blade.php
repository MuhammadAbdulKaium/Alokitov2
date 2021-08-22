
@extends('employee::layouts.profile-layout')

@section('profile-content')
    <p class="text-right">
        <a class="btn btn-primary btn-sm" href="{{url('/employee/profile/create/qualification/'.$employeeInfo->id)}}" oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-plus-square" aria-hidden="true"></i> Add</a>
    </p>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <th>SL</th>
            <th>Type</th>
            <th>Year</th>
            <th>Name</th>
            <th>Institute</th>
            <th>Institute Address</th>
            <th>Marks</th>
            <th>Attachment</th>
            <th>Action</th>
            </thead>
            <tbody>
            @foreach($qualifications as $qualification)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>
                        @if($qualification->qualification_type == 1)
                            General Qualification
                        @elseif($qualification->qualification_type == 2)
                            Special Qualification
                        @elseif($qualification->qualification_type == 3)
                            Last Academic Qualification
                        @endif
                    </td>
                    <td>{{$qualification->qualification_year}}</td>
                    <td>{{$qualification->qualification_name}}</td>
                    <td>{{$qualification->qualification_institute}}</td>
                    <td>{{$qualification->qualification_institute_address}}</td>
                    <td>{{$qualification->qualification_marks}}</td>
                    <td>
                        <img src="{{ asset('/employee-attachment/'.$qualification->qualification_attachment) }}" style="width: 40px" alt="">
                    </td>
                    <td>
                        <a class="btn btn-primary btn-xs" href="{{ url('employee/profile/edit/qualification/'.$qualification->id) }}"
                           data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i
                                    class="fa fa-edit"></i></a>
                        <a href="{{ url('employee/profile/delete/qualification/'.$qualification->id) }}" class="btn btn-danger btn-xs"
                           onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                           data-content="delete"><i class="fa fa-trash-o"></i></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

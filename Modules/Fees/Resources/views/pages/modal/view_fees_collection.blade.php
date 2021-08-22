
                <table id="invoicePayerList" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Serial NO.</th>
                        <th><a  data-sort="sub_master_code">Invoice ID</a></th>
                        <th><a  data-sort="sub_master_code">Student Name</a></th>
                        <th><a  data-sort="sub_master_alias">Class Name</a></th>
                        <th><a  data-sort="sub_master_alias">Section Name</a></th>
                        <th><a  data-sort="sub_master_alias">Studnt Profile</a></th>
                    </tr>

                    </thead>
                    <tbody>

                    @if(isset($invoicePayers))
                        @php

                            $i = 1
                        @endphp
                        @foreach($invoicePayers as $invoice)

                            <tr class="gradeX">
                                <td>{{$i++}}</td>
                                @php
                                    $std=$invoice->payer();
                                   $enroll=$std->singleEnroll();
                                   $batch=$enroll->batch();
                                   $section=$enroll->section();
                                @endphp
                                <td>{{$invoice->id}}</td>
                                <td>{{$std->first_name.' '.$std->middle_name.' '.$std->last_name}}</td>
                                <td>{{$batch->batch_name}}</td>
                                <td>{{$section->section_name}}</td>
                                <td><a href="{{URL::to('student/profile/personal/'.$invoice->payer_id)}}" target="_blank">Profile Link</a></td>

                            </tr>
                        @endforeach
                    @endif

                    </tbody>
                </table>

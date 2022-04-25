<table class="table signatory_table" style="margin-top: 30px">
    <thead>
        <tr>

            <th class="th1">SI</th>
            <th class="th2">Label Names</th>
            <th class="th3">Select HR</th>
            <th class="th4">Designation & Department</th>
            <th class="th5">Attach Scanned Signature</th>
            @if ($start > 0)
                <th class="th6">Action</th>
            @endif
        </tr>
    </thead>
    <tbody>


        @if ($start > 0)
            @isset($signatoryConfig)
                @foreach ($signatoryConfig as $key => $signatory)

                    <tr class="trCount">
                        <td>
                            {{ $key + 1 }}
                        </td>
                        <td>
                            <p>

                                <input type="text" name="label[{{ $key }}]" value="{{ $signatory->label }}"
                                    class="form-control lable" id="label" placeholder="Label Name">
                            </p>
                        </td>
                        <td>

                            <select name="empolyee_id[{{ $key }}]" class="form-control select-hr">
                                <option value="">Select Hr</option>
                                @foreach ($employeInformations as $employee)

                                    <option {{ $signatory->empolyee_id == $employee->id ? 'selected' : '' }}
                                        value="{{ $employee->id }}">
                                        {{ $employee->singleUser->name }} {{ $employee->singleUser->username }}
                                    </option>
                                @endforeach
                            </select>

                        </td>
                        <td>
                            <p class="showDepartmentDesignation">
                                @if ($signatory->employeeInfo->id == $signatory->empolyee_id)
                                    {{ $signatory->employeeInfo->singleDepartment ? $signatory->employeeInfo->singleDepartment->name : '' }}
                                    ,
                                    {{ $signatory->employeeInfo->singleDesignation ? $signatory->employeeInfo->singleDesignation->name : '' }}
                                @endif
                            </p>
                        </td>
                        <td>
                            @if ($signatory->attatch)
                                <img src="{{ asset('/assets/signatory/' . $signatory->attatch) }}" class="show_img"
                                    height="20" width="50" alt="signotryImage">
                            @else
                                <img src="#" class="show_img hidden" height="20" width="50" alt="signotryImage">
                            @endif
                            <p>

                                <input type="file" name="attatch[{{ $key }}]" class="form-control signature-file">
                            </p>
                          
                        </td>
                        <td>
                            <button type="button" title="delete"
                                value="{{ url('accounts/signatory-confin-data/delete', $signatory->id) }}"
                                class="signatory_delete btn btn-danger"><i class="fa fa-trash"
                                    aria-hidden="true"></i></button>
                        </td>
                    </tr>
                @endforeach
            @endisset

        @endif

        @if ($numberOfForms > 0)
            @for ($i = 0; $i < $numberOfForms; $i++)
                <tr class="trCount">
                    <td>
                        {{ $start + 1 }}
                    </td>
                    <td>
                        <p>
                            <input type="text" name="label[{{ $start }}]" class="form-control lable" id="label"
                                placeholder="Label Name">
                        </p>
                    </td>
                    <td>
                        <select name="empolyee_id[{{ $start }}]" class="form-control select-hr">
                            <option value="">Select Hr</option>
                            @foreach ($employeInformations as $employee)
                                <option value="{{ $employee->id }}">
                                    {{ $employee->singleUser->name }} {{ $employee->singleUser->username }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>

                        <p class="showDepartmentDesignation"></p>
                    </td>
                    <td>
                        <img src="#" class="show_img hidden" height="20" width="50" alt="signotryImage">
                        <input type="file" name="attatch[{{ $start }}]" "
                            class="   form-control signature-file">

                    </td>
                    <td>
                        <button type="button" title="remove" class="signatory_remove btn btn-danger"><i
                                class="fa fa-minus" aria-hidden="true"></i>
                        </button>
                    </td>
                </tr>
                @php
                    $start++;
                @endphp
            @endfor
        @endif
    </tbody>
</table>

<script>
    $(document).ready(function() {
        $('.select-hr').select2();

        $(".select-hr").change(function() {
            var hrId = $(this).val(); // get hr id
            var parent = $(this).parent().parent();
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('accounts/signatory-confin-getdesignation') }}",
                type: "GET",
                cache: false,
                data: {
                    '_token': $_token,
                    'hrId': hrId,
                },
                datatype: 'application/json',
                beforeSend: function() {},
                success: function(data) {
                    // department & designation data 
                    var departmant = (data?.single_department) ? (data?.single_department
                        .name) : '';
                    var designation = (data?.single_designation) ? (data?.single_designation
                        ?.name) : '';
                    var designationDepartmant = departmant + "  , " + designation;
                    // cheack showDepartmentDesignation class
                    var cheackShowDepartmentDesignation = parent.find(
                        '.showDepartmentDesignation');
                    cheackShowDepartmentDesignation.text(" ");
                    cheackShowDepartmentDesignation.text(designationDepartmant)
                    // set value form showDepartmentDesignation 
                }
            })

        });
    })
</script>

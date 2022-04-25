<style>
    / signatory Config Style Start /
    .signatory_table{
      width: 100% !important;
    }
    .signatory_table .th1{
      width: 5% !important;
    }
    .signatory_table .th2{
      width: 20% !important;
    }
    .signatory_table .th3{
      width: 30% !important;
    }
    .signatory_table .th4{
      width: 20% !important;
    }
    .signatory_table .th5{
      width: 20% !important;
    }
    .signatory_table .th6{
      width: 5% !important;
    }
    .signatory_table tbody input,
    .signatory_table tbody select{
      width: 100% !important;
    }
    / signatory Config Style End /
</style>
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h2 style="text-transform:capitalize"> {{ $reportName }} Voucher Signatory Config</h2>

</div>
<form id="saveSignatory" method="POST" action="{{ url('/accounts/signatory-confin-data/post') }}"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="reportName" value="{{ $reportName }}">
    <div class="modal-body">
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="signatory-number" class="">Number of Signatory:</label>
                <input type="number" id="signatory-number">
                <input type="hidden" id="reportName" value="{{ $reportName }}">
                <button type="button" class="add-form btn btn-sm btn-success">
                    <i class="fa fa-refresh" aria-hidden="true"></i>
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div id="signatoryFormShow">

                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a class="btn btn-default pull-left" data-dismiss="modal">Cancel</a>
        <button type="submit" id="" class="btn btn-info pull-right">Save</button>
    </div>
</form>

<script>
    // add-form
    // signatory-number
    $(document).ready(function() {
        // $("form#saveSignatory").validate();
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
        var reportName = "{{ $reportName }}";
        var exists = "{{ $exists }}";
        var formVal = "{{ $numberOfForms }}"; //SignatoryConfigController from page()
        $('#signatory-number').val(formVal);
        // signatoryConfigForm
        function signatoryConfigForm(totalForm, reportName) {
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('accounts/signatory-confin-form') }}",
                type: "GET",
                cache: false,
                data: {
                    '_token': $_token,
                    'totalForm': totalForm,
                    'reportName': reportName
                },
                datatype: 'application/json',
                beforeSend: function() {
                    // show waiting dialog
                    // waitingDialog.show('Loading...');
                },
                success: function(data) {
                    waitingDialog.hide();
                    $('#signatoryFormShow').html(data);

                    // $('#signatory-number').val(formVal);
                    // console.log(data);
                },
                error: function(data) {
                    waitingDialog.hide();
                    // alert(JSON.stringify(data));
                }
            })

        }

        $(".add-form").click(function() {
            var totalForm = $('#signatory-number').val();
            console.log(reportName);
            if ((totalForm - formVal) > 0) {
                signatoryConfigForm(totalForm, reportName);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops..',
                    text: 'Please add a number',
                })
            }
        });


        if (exists) {
            signatoryConfigForm(0, reportName);

        }

        $('form#saveSignatory').on('submit', function(e) {

            e.preventDefault();
            var formData = new FormData(this);
            var labelNull = false;
            var employeeNull = false;
            var fileNull = false;
            var fileSize = false;
            $_token = "{{ csrf_token() }}";

            $('input[name^=label]').each(function() {
                if (!$(this).val()) {
                    labelNull = true;
                }
            });
            $('input[name^=attatch]').each(function() {
                var validExtensions = ["jpg", "jpeg", "png"];
                var file = $(this).val().split('.').pop();
                if ($(this).val()) {
                    if (validExtensions.indexOf(file) == -1) {
                        fileNull = true;
                    }
                }

            });

            $('input[name^=attatch]').each(function() {

                var file = $(this)[0];
                if (file.files.length > 0) {
                    if (40000 < file.files[0].size) {
                        fileSize = true;
                    }
                }


            });

            $('select[name^=empolyee_id]').each(function() {
                if ($(this).val() == "") {
                    employeeNull = true;
                }
            });


            if (labelNull) {
                Toast.fire({
                    icon: "error",
                    title: "Label Field is required"
                });
            } else if (employeeNull) {
                Toast.fire({
                    icon: "error",
                    title: "HR Field is required"
                });
            } else if (fileNull) {
                Toast.fire({
                    icon: "error",
                    title: "Only formats are allowed :jpg, png, jpag"
                });
            } else if (fileSize) {
                Toast.fire({
                    icon: "error",
                    title: "Fiels More then 40kbs Not allowed"
                });
            } else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/accounts/signatory-confin-data/post",
                    type: 'POST',
                    cache: false,
                    data: formData,
                    processData: false,
                    contentType: false,
                    datatype: 'application/json',

                    beforeSend: function() {
                        // show waiting dialog
                        waitingDialog.show('Loading...');
                    },

                    success: function(data) {
                        waitingDialog.hide();
                        signatoryConfigForm(0, reportName);
                        // console.log(data);
                        if (data) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Signatory submitted successfully!'
                            });
                        }
                    },

                    error: function(data) {
                        waitingDialog.hide();
                        var errorData = JSON.parse(data.responseText);

                    }
                });
            }


        })

        // signatory_delete

        $(document).on('click', '.signatory_delete', function() {
            var url = $(this).val();
            $_token = "{{ csrf_token() }}";
            // console.log(url);
            // alert(hrId)
            // var url = "{{ url('accounts/signatory-confin-data/delete') }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: url,
                type: "GET",
                cache: false,
                data: {
                    '_token': $_token,
                },
                datatype: 'application/json',
                beforeSend: function() {
                    waitingDialog.show('Loading...');
                },
                success: function(data) {
                    waitingDialog.hide();
                    signatoryConfigForm(0, reportName);
                    var signatoryNumber = $('#signatory-number').val();
                    $('#signatory-number').val(signatoryNumber - 1)
                    Toast.fire({
                        icon: 'success',
                        title: 'Signatory Deleted successfully!'
                    });
                },
                error: function(data) {
                    waitingDialog.hide();
                    // alert(JSON.stringify(data));
                }
            })

        });

        $(document).on('click', '.signatory_remove', function() {
            var signatoryNumber = $('#signatory-number').val();
            var parent = $(this).parent().parent();
            $('#signatory-number').val(signatoryNumber - 1);
            parent.remove();
        });

        $(document).on('change', '.signature-file', function(e) {
            var parent = $(this).parent().parent();
            var show_img = parent.find('.show_img');
            var file = e.target.files[0];
            if (file) {
                var reader = new FileReader();

                reader.onload = function() {
                    show_img.attr("src", reader.result);
                }

                reader.readAsDataURL(file);
                show_img.removeClass('hidden');
            }
        });



    });
</script>

<form>
    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
    <input type="hidden" value="{{$structureName->id}}" name="structureID">
    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title text-bold">
            <i class="fa fa-info-circle"></i> Fees Structure Details for <span style="text-decoration: underline">{{$structureName->structure_name}}</span>
        </h4>
    </div>
    <!--modal-header-->
    <div class="modal-body">
        <div class="row">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Head Name</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($feesHeads as $feesHead)
                    <tr>
                        {{$feesHead->id}}
                        <td><input type="checkbox" name="checkbox[]" id="selectHead_{{$feesHead->id}}" onclick="selectHead({{$feesHead->id}})" value="{{$feesHead->id}}"></td>
                        <td>{{$feesHead->fees_head}}</td>
                        <td>
                            <div class="form-group">
                                <input type="number" class="form-control" id="headAmount_{{$feesHead->id}}" name="amount[]">
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-info pull-right" id="submitData">Submit</button>
        <a class="btn btn-default pull-left" data-dismiss="modal">Cancel</a>
    </div>
</form>
<script>
    var selectedData = [];
    function selectHead(id)
    {
        if($('#selectHead_'+id).is(":checked"))
        {
            console.log($('#headAmount_'+id).val())
            var head = $('#selectHead_'+id).val();
            var amount = $('#headAmount_'+id).val();
            var jsonData = JSON.parse('{"head":"'+head+'", "amount" : "'+amount+'" }');
            selectedData.push(jsonData);
        }
        else
        {
            var index = 0;
            for (index = selectedData.length - 1; index >= 0; --index) {
                console.log(selectedData[index]);
                if (selectedData[index].head == id) {
                    selectedData.splice(index, 1);
                }
            }
        }
    }

    $(function () {
        $("#selectAll").click(function () {
            $("input[type=checkbox]").prop("checked", $(this).prop("checked"));
        });

        $("input[type=checkbox]").click(function () {
            if (!$(this).prop("checked")) {
                $("#selectAll").prop("checked", false);
            }
        });
        $("#submitData").click(function (e) {
            e.preventDefault();

            // ajax request
            $.ajax({
                url: "/cadetfees/fees/structure/details/store/",
                type: 'POST',
                cache: false,
                data: {"_token": "{{ csrf_token() }}",
                    "selectedData":selectedData},
                datatype: 'application/json',


                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },

                success:function(data){

                    console.log(data);
                },

                error:function(data){
                    alert(JSON.stringify(data));
                }
            });
        });

    })
</script>

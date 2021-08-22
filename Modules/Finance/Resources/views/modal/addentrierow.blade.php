<tr class="ajax-add">
    <td>
        <div class="form-group-entryitem required">
            <select name="data[Entryitem][3][dc]" class="dc-dropdown form-control" id="Entryitem3Dc">
                <option value="D" selected="selected">Dr</option>
                <option value="C">Cr</option>
            </select>
        </div>
    </td>
    <td>
        <div class="form-group-entryitem required">
            <select name="data[Entryitem][3][ledger_id]" class="ledger-dropdown form-control selectLedger" id="Entryitem3LedgerId" style="width: 100%;">
                <option value="0">(Please select..)</option>
                @foreach($parentGroupList as $key=>$parent)
                    <optgroup label="{{$parent->name}}">
                        @php $childGroupList=$parent->getChildGroup($parent->id); @endphp
                        @foreach($childGroupList as $key=>$childGorup)
                            <optgroup label=" {{$childGorup->name}}">
                                @php $ledgerList=$childGorup->getLedger($childGorup->id); @endphp
                                @foreach($ledgerList as $key=>$ledger)
                                    <option value="">{{$ledger->name}}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </optgroup>

                @endforeach

            </select>
        </div>
    </td>
    <td>
        <div class="form-group-entryitem">
            <input name="data[Entryitem][3][dr_amount]" class="dr-item form-control" type="text" id="Entryitem3DrAmount" disabled="">
        </div>
    </td>
    <td>
        <div class="form-group-entryitem">
            <input name="data[Entryitem][3][cr_amount]" class="cr-item form-control" type="text" id="Entryitem3CrAmount" disabled="">
        </div>
    </td>
    <td>
        <button class="btn btn-success addrow" type="button"><i class="fa fa-plus"></i></button>
        <button class="btn btn-danger deleterow" type="button"><i class="fa fa-trash-o"></i></button>

    </td>
    <td class="ledger-balance">
        <div></div>
    </td>
</tr>
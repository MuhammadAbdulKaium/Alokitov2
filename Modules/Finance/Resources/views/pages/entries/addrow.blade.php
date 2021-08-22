@php
    // Generate a random id to use in below form array
        $i = time() + rand  (0, time()) + rand  (0, time()) + rand  (0, time());
@endphp

<tr class="ajax-add">
    <td>
        <div class="form-group-entryitem required">
            <select id="Entryitem{{$i}}Dc" class="dc-dropdown form-control" name="Entryitem[{{$i}}][dc]">
                <option selected="selected" value="D">Dr</option>
                <option value="C">Cr</option>
            </select>
        </div>
    </td>
    <td>
        <div class="form-group-entryitem required">
            <select id="Entryitem{{$i}}LedgerId" class="ledger-dropdown form-control" name="Entryitem[{{$i}}][ledger_id]">

                @foreach ($ledger_options as $row => $data) {
                    @if ($row >= 0)
                       <option value="{{$row}}">{{$data}}</option>
                    @else
                        <option value="{{$row}}" disabled >{{$data}}</option>
                     @endif
                @endforeach

            </select>
        </div>
    </td>
    <td>
        <div class="form-group-entryitem">
            <input type="text" id="Entryitem{{$i}}DrAmount" class="dr-item form-control" name="Entryitem[{{$i}}][dr_amount]" disabled="">
        </div>
    </td>
    <td>
        <div class="form-group-entryitem">
            <input type="text" id="Entryitem{{$i}}CrAmount" class="cr-item form-control" name="Entryitem[{{$i}}][cr_amount]" disabled="">
        </div>
    </td>
    <td>
        <div class='form-group-entryitem'>
            <input type="text" name="Entryitem[{{$i}}][narration]" value="" class="form-control" id="Entryitem{{$i}}Narration" />
        </div>
    </td>
    <td class="ledger-balance">
        <div></div>
    </td>
    <td><span class="deleterow" escape="false"><i class="glyphicon glyphicon-trash"></i></span></td>
</tr>
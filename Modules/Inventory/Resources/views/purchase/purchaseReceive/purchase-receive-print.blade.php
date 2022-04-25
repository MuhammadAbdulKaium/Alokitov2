<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Purchase Receive</title>
    <style>
        .clearfix {
            overflow: auto;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        img {
            width: 100%;
        }

        .header {
            border-bottom: 1px solid #f1f1f1;
            padding: 10px 0;
        }

        .logo {
            width: 12%;
            float: left;
        }

        .headline {
            width: 82%;
            float: right;
            padding: 0 20px;
            text-align: left;
        }
        td{
            font-size: 12px;
            padding: 3px 0;
        }

        a {
            text-decoration: none
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .top-table {
            width: 100%;
        }

        .top-table table tbody tr td {
            padding: 8px;
            border: 1px solid black;
        }

        .bottom-table table {
            border-collapse: collapse;

        }

        .bottom-table table th,
        .bottom-table table td {
            border: 1px solid #000;
            text-align: center
        }

        .hidden_border {
            border-style: none !important;
        }

        .terms {
            border-bottom: 2px solid black;
            width: 175px;
        }

        ul {
            color: blue;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        h1,
        h3,
        h4,
        p {
            margin: 5px 0;
        }

        h2 {
            margin: 10px 0;
        }

        .text-center {
            text-align: center;
        }

        .approved {
            text-transform: capitalize;
            color: rgba(3, 80, 3, 0.911);
            font-size: 18px
        }

        .pending {
            text-transform: capitalize;
            color: red;
            font-size: 18px;
        }

        .signature {
            width: 18%;
            margin: 0 5.4px;
            display: inline-block;
            height: 250px;
            height: auto;
            vertical-align: top;
        }

        .signature p {
            margin: 0;
            font-size: 12px;
        }

        .signotry_img {
            height: 60px;
            widows: 100%;
            text-align: center;
        }

        .signotry_img img {
            height: 100%;
            widows: 100%;
            overflow: hidden;
        }

        .footer {
            overflow: auto;
            margin-top: 0px;
        }
        .cadet_Name{
            margin-top: 0;
        }

    </style>
</head>

<body>
    <div class="header clearfix">
        <div class="logo">
            <img src="{{ public_path('assets/users/images/' . $institute->logo) }}" alt="logo">
        </div>
        <div class="headline">
            <h2 class="cadet_Name">{{$institute->institute_name}}({{$voucherInfo->campus_name}})</h2>
            <p>{{ $institute->address1 }} </p>
            <p>
                <strong>Phone:</strong>{{ $institute->phone }} <strong>Email:</strong> <a
                    href="{{ $institute->email }}" target="_blank">{{ $institute->email }}</a> <strong>Web:</strong>
                <a href=" {{ $institute->website }}" target="_blank"> {{ $institute->website }}</a>
            </p>
        </div>
    </div>
    <div class="content clearfix">

        <h1 class="text-center" style="margin-bottom: 0px; font-size:20px">Purchase Receive(PR)</h1>
        <h2 class="text-center" style="margin-top: 0; font-size:17px;">PR Store: <span
                style="text-transform: capitalize;">{{ $voucherInfo->store_name }}</span> </h2>

        <div class="clearfix">
            <div class="top-table">
                <table>
                    <tbody>
                        <tr>
                            <td>
                                Purchase Order No: <strong>{{ $voucherInfo->voucher_no }}</strong>
                            </td>
                            <td>
                                Vendor Name: <strong>{{ $voucherInfo->vendor_name }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                References: <strong>{{ $voucherInfo->reference_type }}</strong>
                            </td>
                            <td>
                                Instruction of : <strong>{{ $voucherInfo->name }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Date: <strong>{{ $voucherInfo->date }}</strong>
                            </td>
                            <td>
                                Institute: <strong>{{ $institute->institute_alias }}</strong> ,
                                Campus:<strong>{{ $voucherInfo->campus_name }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Due Date: <strong>{{ $voucherInfo->due_date }}</strong>
                            </td>
                            <td></td>
                        </tr>

                    </tbody>
                </table>
            </div>
            <div class="bottom-table">
                <p style="margin:13px 0; font-size:14px">Following are the List of required item List:</p>
                <table>
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Item Name</th>
                            <th>SKU</th>
                            <th>Uom</th>
                            <th>Qty</th>
                            <th>Rate</th>
                            <th>Amount</th>
                            <th>Discount</th>
                            <th>Vat</th>
                            <th>Vat Type</th>
                            <th>Net Amount</th>
                            <th>Refarance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalAmount = 0;
                            $totalDiscount = 0;
                            $totalVat = 0;
                            $totalNetAmount = 0;
                        @endphp
                        @foreach ($voucherDetailsData as $data)
                            {{-- {{ $data}} --}}
                            <tr>
                                <td>
                                    {{ $loop->index + 1 }}
                                </td>
                                <td>
                                    {{ $data->product_name }}
                                </td>
                                <td>
                                    {{ $data->sku }}
                                </td>
                                <td>
                                    {{ $data->uom }}
                                </td>
                                <td>
                                    {{ (int) $data->rec_qty }}
                                </td>
                                <td>
                                    {{ (int) $data->rate }}
                                </td>
                                <td>
                                    {{ (int) $data->total_amount }}
                                    @php
                                        $totalAmount += $data->total_amount;
                                    @endphp
                                </td>
                                <td>
                                    {{ (int) $data->discount }}
                                    @php
                                        $totalDiscount += $data->discount;
                                    @endphp

                                </td>
                                <td>
                                    {{ (int) $data->vat_per }}
                                    @php
                                        $totalVat += $data->vat_per;
                                    @endphp

                                </td>
                                <td>
                                    {{ $data->vat_type }}
                                </td>
                                <td>
                                    {{ (int) $data->net_total }}
                                    @php
                                        $totalNetAmount += $data->net_total;
                                    @endphp
                                </td>
                                <td>
                                    {{ $data->ref_voucher_name }}
                                </td>
                                {{-- <td></td> --}}
                            </tr>
                        @endforeach

                        <tr>

                            <td colspan="6" class="hidden_border"
                                style="text-align: right; border-style: none; padding-right:10px;">Total
                            </td>
                            <td>
                                {{ $totalAmount }}
                            </td>
                            <td>
                                {{ $totalDiscount }}
                            </td>
                            <td>
                                {{ $totalVat }}
                            </td>
                            <td style="border-style: none;"></td>
                            <td>
                                {{ $totalNetAmount }}
                            </td>
                            <td style="border-style: none;"></td>
                        </tr>
                    </tbody>
                </table>
                <h5>Net Amount In: {{ $totalNetAmount }} Taka Only </h5>
            </div>
            <div style="margin-top:0px">
                <h3 class="terms">Terms & Conditions:</h3>
                <ul>
                    <li>1</li>
                    <li>2</li>
                    <li>3</li>
                    <li>4</li>
                    <li>5</li>
                </ul>
            </div>
            <div style="margin-top:0px">
                <h3>Approval Status:</h3>

                <p>Level 1 Approval: <span class="approved">Approved</span>, by ..... on
                    {{ date('Y-m-d H:i:s') }}</p>
                <p>Level 1 Approval: <span class="approved">Approved</span>, by ..... on
                    {{ date('Y-m-d H:i:s') }}</p>
                <p>Level 1 Approval: <span class="pending">Pending</span></p>

            </div>

        </div>
    </div>
    <div class="footer clearfix" style="margin-top: 20px;">
        <div class="signature-row">
            @php
                $numOfFullLineSignatories = ((int) ($totalSignatory / 5)) * 5;
                $numOfLastLineSignatories = $totalSignatory % 5;
                $numOfBlankSignatories = 5 - $numOfLastLineSignatories;
                $typeOfLastSignatories = $numOfLastLineSignatories == 1 ? 'one' : ($numOfLastLineSignatories % 2 ? 'odd' : 'even');
            @endphp

            {{-- Full line signatories --}}
            @for ($i = 0; $i < $numOfFullLineSignatories; $i++)
                <div class="signature">
                    @if ($signatories[$i]->attatch)
                        <div class="signotry_img">
                            <img src="{{ public_path() . '/assets/signatory/' . $signatories[$i]->attatch }}" alt="">
                        </div>
                    @else
                        <div class="signotry_img"></div>
                    @endif
                    <hr>
                    <p><b>{{ $signatories[$i]->label }} by</b></p>
                    <p>{{ $signatories[$i]->employeeInfo->singleUser->name }}</p>
                    <p>
                        @if ($signatories[$i]->employeeInfo->singleDesignation)
                            {{ $signatories[$i]->employeeInfo->singleDesignation->name }}
                        @endif
                        @if ($signatories[$i]->employeeInfo->singleDepartment)
                            , {{ $signatories[$i]->employeeInfo->singleDepartment->name }}
                        @endif
                    </p>
                </div>
            @endfor

            {{-- Last line signatories --}}
            @if ($typeOfLastSignatories == 'one')
                @for ($i = 0; $i < $numOfBlankSignatories; $i++)
                    <div class="signature"></div>
                @endfor
                <div class="signature">
                    @if ($signatories[$numOfFullLineSignatories]->attatch)
                        <div class="signotry_img">
                            <img src="{{ public_path() . '/assets/signatory/' . $signatories[$numOfFullLineSignatories]->attatch }}"
                                alt="">
                        </div>
                    @else
                        <div class="signotry_img"></div>
                    @endif
                    <hr>
                    <p><b>{{ $signatories[$numOfFullLineSignatories]->label }} by</b></p>
                    <p>{{ $signatories[$numOfFullLineSignatories]->employeeInfo->singleUser->name }}</p>
                    <p>
                        @if ($signatories[$numOfFullLineSignatories]->employeeInfo->singleDesignation)
                            {{ $signatories[$numOfFullLineSignatories]->employeeInfo->singleDesignation->name }}
                        @endif
                        @if ($signatories[$numOfFullLineSignatories]->employeeInfo->singleDepartment)
                            , {{ $signatories[$numOfFullLineSignatories]->employeeInfo->singleDepartment->name }}
                        @endif
                    </p>
                </div>
            @elseif ($typeOfLastSignatories == 'even')
                @for ($i = $numOfFullLineSignatories; $i < $numOfFullLineSignatories + $numOfLastLineSignatories / 2; $i++)
                    <div class="signature">
                        @if ($signatories[$i]->attatch)
                            <div class="signotry_img">
                                <img src="{{ public_path() . '/assets/signatory/' . $signatories[$i]->attatch }}"
                                    alt="">
                            </div>
                        @else
                            <div class="signotry_img"></div>
                        @endif
                        <hr>
                        <p><b>{{ $signatories[$i]->label }} by</b></p>
                        <p>{{ $signatories[$i]->employeeInfo->singleUser->name }}</p>
                        <p>
                            @if ($signatories[$i]->employeeInfo->singleDesignation)
                                {{ $signatories[$i]->employeeInfo->singleDesignation->name }}
                            @endif
                            @if ($signatories[$i]->employeeInfo->singleDepartment)
                                , {{ $signatories[$i]->employeeInfo->singleDepartment->name }}
                            @endif
                        </p>
                    </div>
                @endfor
                @for ($i = 0; $i < $numOfBlankSignatories; $i++)
                    <div class="signature"></div>
                @endfor
                @for ($i = $numOfFullLineSignatories + $numOfLastLineSignatories / 2; $i < $numOfFullLineSignatories + $numOfLastLineSignatories; $i++)
                    <div class="signature">
                        @if ($signatories[$i]->attatch)
                            <div class="signotry_img">
                                <img src="{{ public_path() . '/assets/signatory/' . $signatories[$i]->attatch }}"
                                    alt="">
                            </div>
                        @else
                            <div class="signotry_img"></div>
                        @endif
                        <hr>
                        <p><b>{{ $signatories[$i]->label }} by</b></p>
                        <p>{{ $signatories[$i]->employeeInfo->singleUser->name }}</p>
                        <p>
                            @if ($signatories[$i]->employeeInfo->singleDesignation)
                                {{ $signatories[$i]->employeeInfo->singleDesignation->name }}
                            @endif
                            @if ($signatories[$i]->employeeInfo->singleDepartment)
                                , {{ $signatories[$i]->employeeInfo->singleDepartment->name }}
                            @endif
                        </p>
                    </div>
                @endfor
            @elseif ($typeOfLastSignatories == 'odd')
                <div class="signature"></div>
                @for ($i = $numOfFullLineSignatories; $i < $numOfFullLineSignatories + $numOfLastLineSignatories; $i++)
                    <div class="signature">
                        @if ($signatories[$i]->attatch)
                            <div class="signotry_img">
                                <img src="{{ public_path() . '/assets/signatory/' . $signatories[$i]->attatch }}"
                                    alt="">
                            </div>
                        @else
                            <div class="signotry_img"></div>
                        @endif
                        <hr>
                        <p><b>{{ $signatories[$i]->label }} by</b></p>
                        <p>{{ $signatories[$i]->employeeInfo->singleUser->name }}</p>
                        <p>
                            @if ($signatories[$i]->employeeInfo->singleDesignation)
                                {{ $signatories[$i]->employeeInfo->singleDesignation->name }}
                            @endif
                            @if ($signatories[$i]->employeeInfo->singleDepartment)
                                , {{ $signatories[$i]->employeeInfo->singleDepartment->name }}
                            @endif
                        </p>
                    </div>
                @endfor
                <div class="signature"></div>
            @endif
        </div>
    </div>
    <p style="margin-top: 25px">
        Printed from {{ $institute->institute_alias }} ICT {{ Auth::user()->username }}, on
        {{ date('Y/m/d H:i:s') }}
    </p>
</body>

</html>

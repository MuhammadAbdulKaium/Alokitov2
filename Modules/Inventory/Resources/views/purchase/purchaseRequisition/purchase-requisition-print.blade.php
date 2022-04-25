<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Purchase requisition</title>
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

        <h1 class="text-center" style="margin-bottom: 0px; text-transform:capitalize;">Purchase requisition(PR)</h1>

        <div class="clearfix">
            <div class="top-table" style="margin-top: 20px">
                <table>
                    <tbody>
                        <tr>
                            <td>
                                Purchase Order No: <strong>{{ $voucherInfo->voucher_no }}</strong>
                            </td>
                            <td>
                                Need Cs: <strong>{{ ($voucherInfo->need_cs==1)?'Yes':'No' }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Institute: <strong>{{ $institute->institute_alias }}</strong> ,
                                Campus:<strong>{{ $voucherInfo->campus_name }}</strong>
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
                                Due Date: <strong>{{ $voucherInfo->due_date }}</strong>
                            </td>
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
                            <th>Uom</th>
                            <th>Req.Qty</th>
                            <th>App.Qty</th>
                            <th>Remarks</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                      
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
                                    {{ $data->uom }}
                                </td>
                                <td>
                                    {{ (int) $data->req_qty }}
                                </td>
                                <td>
                                    {{ (int) $data->app_qty }}
                                </td>
                                <td>
                                    {{  $data->remarks }}
                                 
                                </td>
                                <td>
                                   @php
                                       switch($data->status){
                                           case 0:
                                               echo "Pending";
                                               break;
                                            case 1:
                                                echo "Approved";
                                                break;
                                            case 2:
                                                echo "Partial Approved";
                                                break;
                                            default:
                                                echo "Reject";
                                       }
                                   @endphp

                                </td>
                               
                            </tr>
                        @endforeach

                      
                    </tbody>
                </table>
               
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

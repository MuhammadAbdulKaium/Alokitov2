<!DOCTYPE html>
<html>
    <head>
        <title>Monthly Subscription Bill</title>
    </head>
    <body>
        <table border="1" cellspacing="0" cellpadding="15" bordercolor="#dedede">
            <thead>
                <tr>
                    <td>Bill No: {{ $subscriptionManagementData['billingID'] }}</td>
                    <td colspan="2">Bill generated date: {{ date('d F Y', strtotime(now())) }}</td>
                    <td>Invoice No: {{ $subscriptionManagementData['transactionID'] }}</td>
                </tr>

                <tr>
                    <th colspan="4">
                        <h2>Subscription bill of {{ $subscriptionManagementData['month'] }} {{ $subscriptionManagementData['year'] }}</h2>
                        <h3 style="margin:0;">Institute Name: {{ $subscriptionManagementData['instituteName'] }}</h3>
                        <h4 style="margin:0;">Campus Name: {{ $subscriptionManagementData['campusName'] }}</h3>
                    </th>
                </tr>

                <tr>
                    <th>SL.</td>
                    <th>Details</th>
                    <th>Amount</th>
                    <th>Last Payment Date</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>1</td>
                    <td>Student Charge</td>
                    <td>{{ $subscriptionManagementData['totalAmount'] }}</td>
                    <td rowspan="5">@php $currDate = date('d F Y', strtotime(now())); echo date('15 F Y', strtotime($currDate)); @endphp</td>
                </tr>

                <tr>
                    <td>2</td>
                    
                    <td>SMS Charge</td>

                    @if($subscriptionManagementData['totalSmsPrice'])
                        <td>{{ $subscriptionManagementData['totalSmsPrice'] }}</td>
                    @else
                        <td align="center">-</td>
                    @endif
                </tr>

                <tr>
                    <td>3</td>

                    <td>Old Dues</td>

                    @if($subscriptionManagementData['oldDues'])
                        <td>{{ $subscriptionManagementData['oldDues'] }}</td>
                    @else
                        <td align="center">-</td>
                    @endif
                </tr>

                <tr>
                    <td colspan="2">Total Amount</td>
                    <td>{{ $subscriptionManagementData['monthlyTotalCharge'] }} BDT</td>
                </tr>

                <tr>
                    <td colspan="2">Paid Amount</td>

                    @if($subscriptionManagementData['paidAmount'])
                        <td>{{ $subscriptionManagementData['paidAmount'] }} BDT</td>
                    @else
                        <td align="center">-</td>
                    @endif
                </tr>

                <tr>
                    <td colspan="2">Payable Amount</td>
                    <td>{{ $subscriptionManagementData['newDues'] }} BDT</td>
                    <td><em>Please pay ontime</em></td>
                </tr>

                <tr>
                    <td colspan="4">
                        <em style="font-size:0.9rem;">Note: To avoid intteruption accessing the software please pay your bill ontime</em>
                    </td>
                </tr>
            </tbody>
        </table>

        <p>
            <br /><br />
            Thank you for your supporting,<br /><br />

            <strong>For any queries please contact with us:</strong> <br />

            Venus Complex, Kha-199/2-199/4<br />

            Bir Uttam Rafiqul Islam Ave<br />
           
            Dhaka-1212<br />
           
            <strong>E-mail:</strong> <a href="mailto:info@venusitltd.com">info@venusitltd.com</a><br />
           
            <strong>Cell:</strong> +8801708872244
        </p>
    </body>

</html>

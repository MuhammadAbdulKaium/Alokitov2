<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Term Exam Results Details</title>
</head>
<body>
<style>
    body{
        font-size: 5px;
    }
    table{
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
        border: 1px solid black;
    }
    td,th{
        border: 1px solid #4c4a4a;
        border-collapse: collapse;
        padding: 1px;
    }
    .top>*{
        border: none;
        text-align: center;
    }
    *{
        text-align: center;
    }
</style>
<table style="border: none" class="top">
    <tbody>
        <tr style="border: none" class="top">
            <td colspan="3" style="border: none">Grading System</td>
            <td colspan="4" rowspan="9" style="border: none"></td>
            <td colspan="7" rowspan="9">
             <div style="text-align: center">
                 <b>Rangpur Cadet College</b><br>
                 <b>2nd Term End Exam-2021-IX-2021</b><br>
                 <span>Class: IX</span><br>
                 <span>Form: A</span><br>
                 <span>Group: Science</span><br>
                 <span>Form Master: Md. Sahidul Islam (2) , Lecturer</span><br>
             </div>
            </td>
            <td colspan="4" rowspan="9"></td>
            <td colspan="2">At A Glance</td>
        </tr>
    <tr style="border: none" class="top">
        <td>Lg</td>
        <td>GP</td>
        <td>Marks</td>
        <td>>=5</td>
        <td>total gpa 5</td>
    </tr>
        <tr style="border: none" class="top">
            <td>A+</td>
            <td>5</td>
            <td>>=80%</td>
            <td>4 to <5</td>
            <td>13</td>
        </tr>
        <tr style="border: none" class="top">
            <td>A</td>
            <td>4</td>
            <td>70% to  &lt;80%</td>
            <td>>=5</td>
            <td>5</td>
        </tr>
        <tr style="border: none" class="top">
            <td>A-</td>
            <td>3.5</td>
            <td>60% to  &lt;70% </td>
            <td>3.5 to <4</td>
            <td> 5</td>
        </tr>
        <tr class="top">
            <td>B</td>
            <td>3</td>
            <td>50% to  &lt;60%</td>
            <td>3 to < 3.5</td>
            <td> 5</td>
        </tr>
        <tr class="top">
            <td>C</td>
            <td>2</td>
            <td>40% to  &lt;50%</td>
            <td>2 to 3</td>
            <td>1</td>
        </tr>
        <tr class="top">
            <td>F</td>
            <td>0</td>
            <td>00% to  &lt;40%</td>
            <td>Failed or <2</td>
            <td>2</td>
        </tr>
        <tr class="top">
            <td></td>
            <td></td>
            <td></td>
            <td>Total</td>
            <td>25</td>
        </tr>
    </tbody>
</table>
<table  style="vertical-align: middle">
    <thead>
    <tr>
        <th rowspan="3">CN</th>
        <th rowspan="3">Name</th>
        <th rowspan="3">House</th>
        <th rowspan="1" colspan="3">Bangla paper 1 </th>
        <th rowspan="1" colspan="4">Bangla paper 2 </th>
        <th rowspan="3">GP</th>
        <th rowspan="1" colspan="3">English paper 1</th>
        <th rowspan="1" colspan="4">English paper 2</th>
        <th rowspan="3">GP</th>
        <th rowspan="1" colspan="4">Math</th>
        <th rowspan="3">GP</th>
        <th rowspan="1" colspan="5">Chemistry</th>
        <th rowspan="3">GP</th>
        <th rowspan="1" colspan="5">Biology</th>
        <th rowspan="3">GP</th>
        <th rowspan="1" colspan="5">Bangladesh & Global</th>
        <th rowspan="3">GP</th>
        <th rowspan="1" colspan="5">Physical Education</th>
        <th rowspan="3">GP</th>
        <th rowspan="1" colspan="5">Islam & Moral Education</th>
        <th rowspan="3">GP</th>
        <th rowspan="1" colspan="5">ICT </th>
        <th rowspan="3">GP</th>
        <th rowspan="3">Total</th>
        <th rowspan="3">%</th>
        <th rowspan="3" colspan="2">GP</th>
        <th rowspan="3">Grand Position</th>
    </tr>
    <tr>
        <th rowspan="1">F.Avg</th>
        <th rowspan="1">M/O</th>
        <th rowspan="1">T/E/C</th>
        <th rowspan="1">F.Avg</th>
        <th rowspan="1">M/O</th>
        <th rowspan="1">T/E/C</th>
        <th rowspan="1">Total</th>

        <th rowspan="1">F.Avg</th>
        <th rowspan="1">M/O</th>
        <th rowspan="1">T/E/C</th>
        <th rowspan="1">F.Avg</th>
        <th rowspan="1">M/O</th>
        <th rowspan="1">T/E/C</th>
        <th rowspan="1">Total</th>

        <th rowspan="1">F.Avg</th>
        <th rowspan="1">M/O</th>
        <th rowspan="1">T/E/C</th>
        <th rowspan="1">Total</th>

        @for($i=0;$i<6;$i++)
            <th rowspan="1">F.Avg</th>
            <th rowspan="1">M/O</th>
            <th rowspan="1">T/E/C</th>
            <th rowspan="1">P</th>
            <th rowspan="1">Total</th>
        @endfor
    </tr>
    <tr>
        <th rowspan="1">20</th>
        <th rowspan="1">30</th>
        <th rowspan="1">70</th>
        <th rowspan="1">20</th>
        <th rowspan="1">30</th>
        <th rowspan="1">70</th>
        <th rowspan="1">200</th>

        <th rowspan="1">20</th>
        <th rowspan="1">30</th>
        <th rowspan="1">70</th>
        <th rowspan="1">20</th>
        <th rowspan="1">30</th>
        <th rowspan="1">70</th>
        <th rowspan="1">200</th>

        <th rowspan="1">20</th>
        <th rowspan="1">30</th>
        <th rowspan="1">70</th>
        <th rowspan="1">100</th>

        @for($i=0;$i<6;$i++)
            <th rowspan="1">20</th>
            <th rowspan="1">25</th>
            <th rowspan="1">50</th>
            <th rowspan="1">25</th>
            <th rowspan="1">100</th>
        @endfor

    </tr>
    </thead>
    <tbody>
    <tr>
        <td>569</td>
        <td>Takia</td>
        <td>SH</td>

        <td>19</td>
        <td>29</td>
        <td>69</td>
        <td>19</td>
        <td>29</td>
        <td>69</td>
        <td>194</td>
        <td>A+</td>

        <td>19</td>
        <td>29</td>
        <td>69</td>
        <td>19</td>
        <td>29</td>
        <td>69</td>
        <td>194</td>
        <td>A+</td>

        <td>19</td>
        <td>29</td>
        <td>68</td>
        <td>96</td>
        <td>A+</td>


        @for($i=0;$i<6;$i++)
            <td rowspan="1">18</td>
            <td rowspan="1">23</td>
            <td rowspan="1">46</td>
            <td rowspan="1">25</td>
            <td rowspan="1">84.5</td>
            <td rowspan="1">A+</td>
    @endfor
    <!-- Total value -->
        <td>1190</td>
        <!-- Total perchantage -->
        <td>90</td>
        <!-- Gpa Score & Gpa -->
        <td>5.0</td>
        <td>A+</td>
        <!-- Grand Position  -->
        <td>2</td>
    </tr>
    </tbody>
</table>
</body>
</html>

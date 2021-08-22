<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admission From</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        @page {
            margin-left: 1cm;
            margin-right: 1cm;
            margin-top: .5cm;
        }

        .page-container {
            height: 975px;
            width: 800px;
            margin: 0 auto;
            padding: 0 auto;
        }

        h1, h2, h3, p, span {
            margin: 0;
            padding: 0;
        }


        #inst-info{
            float:left;
            width: 85%;
            margin: 30px;
            text-align: center;
        }


        #inst-photo {
            float: left;
            margin-top: 20px;
            margin-left: 10px;
        }

        .header-info {
            clear: both;

        }

    </style>

</head>
<body>

<div class="container page-container">
    <div class="header-info">
        <div id="inst-photo" style="text-align: center; padding-bottom: 20px">
            <b style="font-size: 22px">{{$institute->institute_name}}</b> <br>
            <img src="{{public_path().'/assets/users/images/'.$institute->logo}}"  style="width:100px;height:100px">
            <p style="font-size: 16px">{{'Address: '.$institute->address1}}<br/>{{'Phone: '.$institute->phone. ', E-mail: '.$institute->email.', Website: '.$institute->website}}</p>

        </div>

    </div>


    <div class="admission" style="width: 200px; background: #3c3c3c; padding: 5px; border-radius: 30px; margin: 0 auto; color: #fff; text-align: center; font-size: 20px; font-weight: 700"><p>ভর্তির আবেদন ফরম </p>  </div>


    <table width="100%">
        <tr>
            <td>১।</td>
            <td>ছাত্র/ছাত্রীর নামঃ</td>
            <td>বাংলায়ঃ......................................................................................................................</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>ইংরেজীঃ.....................................................................................................................</td>
        </tr>


        <tr>
            <td>২।</td>
            <td>পিতার নাম </td>
            <td>বাংলায়ঃ.................................................................পেশা...............................................</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>ইংরেজীঃ.................................................................শিক্ষাগত যোগ্যতা...............................</td>
        </tr>

        <tr>
            <td>৩।</td>
            <td>মাতার নামঃ</td>
            <td>বাংলায়ঃ.................................................................পেশা..............................................</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>ইংরেজীঃ.................................................................শিক্ষাগত যোগ্যতা..................................</td>
        </tr>

        <tr>
            <td>৪। </td>
            <td>স্থায়ীঠিকানা</td>
            <td>গ্রাম/মহল্লা...............................................................<span style="background: #3c3c3c; color: #fff; padding: 5px;">মোবাইল</span>................................</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>ডাকঘরঃক................., উপজেলাঃ ................., জেলাঃ .................।   </td>
        </tr>

        <tr>
            <td>৫।</td>
            <td>বর্তমান ঠিকানা</td>
            <td>গ্রাম/মহল্লা...............................................................<span style="background: #3c3c3c; color: #fff; padding: 5px;">মোবাইল</span>......................................</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>ডাকঘরঃক................., উপজেলাঃ ................., জেলাঃ .................।  </td>
        </tr>

    </table>


        <table>
            <tr>
                <td>৬।</td>
                <td>ক) অভিভাবকের নাম (যদি প্রয়োজন হয়)...................................................<span style="background: #3c3c3c; color: #fff; padding: 5px;">মোবাইল</span>............................. </td>
            </tr>

            <tr>
                <td>৭।</td>
                <td>পিতা/মাতা/অভিভাবকের বাৎসরিক আয়ঃ..................................টাকা, কথায় .................................................... </td>
            </tr>


            <tr>
                <td>৮।</td>
                <td>যাহার সাথে ছাত্র/ছাত্রী বাস করিবে ........................................................................................................ </td>
            </tr>

            <tr>
                <td>৯।</td>
                <td>জন্ম তারিখ (জন্ম সনদ অনুযায়ী)  ......................................................................কথায়................................ </td>
            </tr>

            <tr>
                <td>১০।</td>
                <td> যে শ্রেণীতে ভর্তি হতে চায়ঃ.................................................................................................................... </td>
            </tr>

            <tr>
                <td>১০।</td>
                <td>সমাপনী পরীক্ষায় প্রাপ্ত GPA......................... সমাপনী পরীক্ষার রোল নম্বর.............................সন........... </td>
            </tr>
            <tr>
                <td></td>
                <td>বিদ্যালয়ের নাম ................................................................................................................................ </td>
            </tr>
            <tr>
                <td></td>
                <td>প্রশংসাপত্র/ছাড়পত্র নম্বরঃ..........................................................তারিখ................................................... </td>
            </tr>
        </table>
        <table>
            <tr>
                <td>আমি এইমর্মে ঘোষণা করিতেছি যে, উপরে প্রদত্ত বিবরণ সম্পূর্ণ সঠিক ও সত্য।  তাহাকে ভর্তি করা হইলে বিদ্যালয়ের নিয়ম কানুন মানিয়া চলিব, বেতনাদি রীতিমত পরিশোধ করিব এবং তাহার পাঠোন্নতি ও নিয়মিত উপস্থিতির প্রতি সদা সতর্ক দৃষ্টি রাখিব </td>
            </tr>
        </table>

    <p style="width: 100%; margin-top: 20px;">তারিখঃ ............................................খ্রিঃ</p>
    <p style="width: 200px; margin-top: -25px; float: right; border-top: 2px solid black; text-align: center">পিতা/মাতা/অভিভাবকের স্বাক্ষর</p>


        <table style="margin-top: 30px">
            <tr>
                <td>আামি এই মর্মে অঙ্গিকার করিতেছি যে, সর্বদা বিদ্যালয়ের নিয়ম-কানুন মানিয়া চলিব।   রীতিমত লেখাপড়া ছাড়াও পাঠ্যক্রম বহির্ভূত কর্মসূচীতে নিয়মিত অংশগ্রহণ করিব এবং প্রতিষ্ঠানের সুনাম অক্ষুন্ন রাখিতে সচেষ্ট থাকিব।
                </td>
            </tr>
        </table>


    <p style="width: 100%; margin-top: -20px;">তারিখঃ ............................................খ্রিঃ</p>
    <p style="width: 200px; margin-top: -25px; float: right; border-top: 2px solid black; text-align: center">ছাত্র/ছাত্রীর স্বাক্ষর</p>



    <p  style="text-align:center; width: 100%; border-top: 1px solid black;   margin-top: 25px; font-size: 22px; font-weight: 700; padding: 5px; color: black;"> ভর্তি সংক্রান্ত তথ্য</p>

        <p style="text-align: center;">ফরম নম্বরঃ................ ভর্তি পরীক্ষার নম্বর/সমাপনী পরীক্ষায় প্রাপ্ত জিপিএঃ ......................ভর্তিকৃত শ্রেণী </p>

        <p style="text-align:center; border-top: 1px solid black; margin-top: 10px; font-size: 22px; font-weight: 700; padding: 5px; color: black;"> অফিস আদেশ</p>

        <p style="text-align: center; margin-top: 0px">আবেদনকারীকে কালাপাহাড়িয়া ইউনিয়ন উচ্চ বিদ্যালয়ের ............................. শ্রেণীতে ভর্তির অনুমতি দেওয়া গেল।   </p>
        <br>
        <p style="text-align:right; margin-top: -20px">প্রধান শিক্ষকের স্বাক্ষর ও সীল </p>

        <hr style="height:1px; border:none; color:#403C3C; background-color:#403C3C; margin: 0px; padding: 0px;">

    <p style="text-align:center;  font-size: 22px; font-weight: 700; padding: 5px; color: black;"> অফিস কর্তৃক পূরণী </p>
        <p style="text-align: center;">শ্রেণীঃ.....................  রোলঃ......................... শাখাঃ......................  ভর্তি নম্বর ও তারিখঃ......................... </p>
        <br>
        <br>
        <p style="text-align:center; border-top: 2px solid black; width:30%; float: right; padding-bottom: 40px">ভর্তিকারীর স্বাক্ষর  </p>


</body>
</html>

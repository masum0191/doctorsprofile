<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>আবেদনকারীর তথ্য</title>
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">

    <style>
        body {
            font-family: 'SolaimanLipi', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #000;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
        }

        .container {
            width: 90%;
            max-width: 900px;
            margin: 30px auto;
            padding: 20px 20px;
        }
        .contnet-item{
            padding: 0 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header img {
            width: 120px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .header h1 {
            margin: 10px 0;
            font-size: 26px;
            font-weight: bold;
        }

        .header p {
            margin: 5px 0;
            font-size: 18px;
        }

        hr {
            border: 1px solid #ccc;
            margin-bottom: 25px;
        }

        h2, h3 {
            text-align: center;
            margin-bottom: 25px;
            font-size: 20px;
            font-weight: bold;
            border-bottom: 2px solid #ccc;
            padding-bottom: 8px;
        }

        .info-section {
            margin-bottom: 25px;
        }

        .info-section p {
            font-size: 16px;
            margin: 8px 0;
        }

        .label {
            font-weight: bold;
            display: inline-block;
            width: 220px;
            margin-right: 10px;
        }

        ul {
            padding-left: 25px;
            font-size: 16px;
        }

        img {
            display: block;
            margin: 20px auto;
            max-height: 160px;
            max-width: 100%;
            object-fit: cover;
            border: 2px solid #ddd;
            padding: 5px;
            border-radius: 5px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                background: none;
            }

            .container {
                width: 100%;
                padding: 15px;
                box-shadow: none;
            }

            .header img {
                width: 100px;
            }

            .header h1 {
                font-size: 22px;
            }

            .header p {
                font-size: 16px;
            }

            .info-section p, .label {
                font-size: 14px;
            }
        }

        @media (max-width: 768px) {
            .container {
                width: 95%;
                padding: 15px;
            }

            .header img {
                width: 100px;
            }

            .header h1 {
                font-size: 22px;
            }

            .label {
                width: 180px;
            }
        }
    </style>
</head>
<body onload="window.print()" style="background-image: url({{asset('images/bg-main.gif')}}); background-size: cover;">

<div class="container">
   <div class="contnet-item">
   <div class="header">
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
        <h1>ঈশ্বরগঞ্জ পৌরসভা</h1>
        <p>গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</p>
        <hr>
    </div>
        <h2>আবেদনকারীর বিবরণ</h2>

        @if($applicant->image)
            <img src="{{ asset('images/' . $applicant->image) }}" alt="ছবি">
        @endif

        <div class="info-section">
            <p><span class="label">আবেদন নম্বর:</span> {{ $applicant->applicant_number }}</p>
            <p><span class="label">নাম:</span> {{ $applicant->head_name }}</p>
            <p><span class="label">পিতা/স্বামী:</span> {{ $applicant->father_or_husband_name }}</p>
            <p><span class="label">মাতার নাম:</span> {{ $applicant->mother_name }}</p>
            <p><span class="label">জাতীয় পরিচয়পত্র:</span> {{ $applicant->nid_number }}</p>
            <p><span class="label">ওয়ার্ড নম্বর:</span> {{ $applicant->ward_number }}</p>
            <p><span class="label">জন্ম তারিখ:</span> {{ $applicant->birth_date }}</p>
            <p><span class="label">মোবাইল নম্বর:</span> {{ $applicant->mobile_number }}</p>
            <p><span class="label">ঠিকানা:</span> {{ $applicant->address }}</p>
            <p><span class="label">কার্ডের ধরন:</span> {{ ucfirst($applicant->card_type) }}</p>
            <p><span class="label">পূর্বের কার্ড নম্বর:</span> {{ $applicant->previous_card_number ?? 'প্রযোজ্য নয়' }}</p>
        </div>

        <div class="info-section">
            <h3>যোগ্যতার শর্তাবলি</h3>
            @if(is_array($applicant->conditions))
                <ul>
                    @foreach($applicant->conditions as $condition)
                        <li>{{ $condition }}</li>
                    @endforeach
                </ul>
            @else
                <p>শর্ত পাওয়া যায়নি</p>
            @endif
        </div>

        <div class="info-section">
            <h3>সংযুক্ত নথিপত্র</h3>
            <ul>
    <li>
        জাতীয় পরিচয়পত্রের ফটোকপি:
        @if($applicant->doc_nid_copy)
            <a href="{{ asset($applicant->doc_nid_copy) }}" target="_blank">দেখুন</a>
        @else
            না
        @endif
    </li>

    <li>
        সুপারিশপত্র:
        @if($applicant->doc_recommendation)
            <a href="{{ asset($applicant->doc_recommendation) }}" target="_blank">দেখুন</a>
        @else
            না
        @endif
    </li>

    <li>
        পাসপোর্ট ছবি:
        @if($applicant->doc_photo)
            <a href="{{ asset($applicant->doc_photo) }}" target="_blank">দেখুন</a>
        @else
            না
        @endif
    </li>

    <li>
        প্রতিবন্ধী সনদ:
        @if($applicant->doc_disability_certificate)
            <a href="{{ asset($applicant->doc_disability_certificate) }}" target="_blank">দেখুন</a>
        @else
            না
        @endif
    </li>

    <li>
        মৃত্যুসনদ (স্বামী):
        @if($applicant->doc_husband_death_certificate)
            <a href="{{ asset($applicant->doc_husband_death_certificate) }}" target="_blank">দেখুন</a>
        @else
            না
        @endif
    </li>
</ul>
</div>
   </div>
</div>


</body>
</html>

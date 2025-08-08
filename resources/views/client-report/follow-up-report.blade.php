<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Danışan Raporu</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            background: #fff;
            padding: 20px;
            color: #2d3436;
        }
        .header {
            text-align: center;
            margin-bottom: 0;
        }
        /*.header img {*/
        /*    width: 100px;*/
        /*    margin-bottom: 5px;*/
        /*}*/
        .header h1 {
            font-size: 20px;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        h2.section-title {
            font-size: 14px;
            color: #0072ff;
            margin-top: 6px;
            margin-bottom: 6px;
            border-bottom: 1px solid #0072ff;
            padding-bottom: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        td, th {
            border: 1px solid #ccc;
            padding: 6px 10px;
            font-size: 10px;
            vertical-align: top;
        }
        th {
            background-color: #f1f2f6;
            color: #2d3436;
            text-align: left;
            width: 180px;
        }
        .footer {
            position: fixed;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #b2bec3;
        }
    </style>
</head>
<body>

<div class="header">
{{--    <img src="{{ public_path('panel/assets/images/neptune.png') }}" alt="Logo">--}}
    <h1>Danışan Raporu</h1>
</div>

<h2 class="section-title">Temel Bilgiler</h2>
<table>
    <tr><th>Ad Soyad</th><td>{{ $client->full_name }}</td></tr>
    <tr><th>Telefon</th><td>{{ $client->phone }}</td></tr>
    <tr><th>E-Posta</th><td>{{ $client->email }}</td></tr>
    <tr><th>Cinsiyet</th><td>{{ $client->gender_formatted }}</td></tr>
    <tr><th>Yaş</th><td>{{ $client->age }}</td></tr>
    <tr><th>Meslek</th><td>{{ $client->occupation }}</td></tr>
</table>

<h2 class="section-title">Fiziksel Bilgiler</h2>
<table>
    <tr><th>Boy</th><td>{{ $client->physicalProfile->initial_height ?? '--' }}</td></tr>
    <tr><th>Mevcut Kilo</th><td>{{ $client->physicalProfile->initial_weight ?? '--' }}</td></tr>
    <tr><th>Hedef Kilo</th><td>{{ $client->physicalProfile->target_weight ?? '--' }}</td></tr>
    <tr><th>Diyet Tipi</th><td>{{ $client->physicalProfile->goal_type_formatted ?? '--' }}</td></tr>
</table>

<h2 class="section-title">Tıbbi Bilgiler</h2>
<table>
    <tr><th>İlaçlar</th><td>{{ $client->medicalProfile->medications_formatted ?? '--' }}</td></tr>
    <tr><th>Tıbbi Durumlar</th><td>{{ $client->medicalProfile->medical_conditions_formatted ?? '--' }}</td></tr>
    <tr><th>Alerjiler</th><td>{{ $client->medicalProfile->allergies_formatted ?? '--' }}</td></tr>
    <tr><th>Besin Alerjileri</th><td>{{ $client->medicalProfile->food_allergies_formatted ?? '--' }}</td></tr>
    <tr><th>Ek Notlar</th><td>{{ $client->medicalProfile->additional_medical_notes ?? '--' }}</td></tr>
</table>

<h2 class="section-title">Beslenme Tarzı</h2>
<table>
    <tr><th>Sevdiği Yiyecekler</th><td>{{ $client->nutritionProfile->favorite_foods_formatted ?? '--' }}</td></tr>
    <tr><th>Sevmediği Yiyecekler</th><td>{{ $client->nutritionProfile->disliked_foods_formatted ?? '--' }}</td></tr>
    <tr><th>Besin Kısıtlamaları</th><td>{{ $client->nutritionProfile->dietary_restrictions_formatted ?? '--' }}</td></tr>
    <tr><th>Öğün Sıklığı</th><td>{{ $client->nutritionProfile->meal_frequency ?? '--' }}</td></tr>
</table>

<h2 class="section-title">Yaşam Tarzı</h2>
<table>
    <tr><th>Aktivite Seviyesi</th><td>{{ $client->lifestyleProfile->activity_level_formatted ?? '--' }}</td></tr>
    <tr><th>Uyku Süresi</th><td>{{ $client->lifestyleProfile->sleep_hours ?? '--' }}</td></tr>
    <tr><th>Su Tüketimi</th><td>{{ $client->lifestyleProfile->water_intake ?? '--' }}</td></tr>
    <tr><th>Sigara Kullanımı</th><td>{{ $client->lifestyleProfile->smoking_status_formatted ?? '--' }}</td></tr>
    <tr><th>Alkol Kullanımı</th><td>{{ $client->lifestyleProfile->alcohol_consumption_formatted ?? '--' }}</td></tr>
    <tr><th>Ekstra Notlar</th><td>{{ $client->lifestyleProfile->extra_notes ?? '--' }}</td></tr>
</table>

<div class="footer">
    © {{ date('Y') }} | {{ config('app.name') }}
</div>

</body>
</html>

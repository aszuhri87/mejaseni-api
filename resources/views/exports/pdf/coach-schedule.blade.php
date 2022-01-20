<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Schedule</title>

    <style>
        *{
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px;
        }

        /* Zebra striping */
        tr:nth-of-type(odd) {
            background: #eee;
        }

        th {
            background: #7F16A7;
            color: white;
            font-weight: bold;
        }

        td,
        th {
            padding: 5px;
            border: 1px solid #ccc;
            text-align: left;
            font-size: 12px;
        }

    </style>
</head>

<body>
    <h3 style="padding: 0; margin: 10px 0px 0px 10px">Jadwal kelas Mejaseni</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kelas</th>
                <th>Siswa</th>
            </tr>
        </thead>
        <tbody>
            @php
                $arr_day = [
                    "Sunday" => "Minggu",
                    "Monday" => "Senin",
                    "Tuesday" => "Selasa",
                    "Wednesday" => "Rabu",
                    "Thursday" => "Kamis",
                    "Friday" => "Jumat",
                    "Saturday" => "Sabtu",
                ];
            @endphp
            @foreach ($result as $item)
            <tr>
                <td data-column="First Name">{{$arr_day[date('l', strtotime($item->start))]}}, {{date('d M Y H:i:s', strtotime($item->start))}}</td>
                <td data-column="Last Name">{{$item->title}}</td>
                <td data-column="Job Title">{{$item->student}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

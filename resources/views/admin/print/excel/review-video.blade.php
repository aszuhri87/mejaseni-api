<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>
    <table class='table table-bordered'>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Rating</th>
                <th>Feedback</th>
            </tr>
        </thead>
        <tbody>
            @php $i=1 @endphp
            @foreach($data as $value)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{$value->student_name}}</td>
                <td>{{date('d-m-Y H:i:s', strtotime($value->datetime))}}</td>
                <td>{{$value->star}}</td>
                <td>{{$value->description}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

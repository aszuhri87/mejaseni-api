<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>
    <table class='table table-bordered'>
        <thead>
            <tr>
                <th><b>No</b></th>
                <th><b>Nomor</b></th>
                <th><b>Coach</b></th>
                <th><b>Bank</b></th>
                <th><b>Total</b></th>
                <th><b>Status</b></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $item)
            <tr>
                <td>{{$index + 1}}</td>
                <td>
                    {{$item->number}} <br>
                    <span class="text-muted">{{$item->datetime}}</span>
                </td>
                <td>
                    {{$item->bank}} <br>
                    <span class="text-muted">{{$item->bank_number}}</span>
                </td>
                <td>
                    {{$item->coach}} <br>
                    <span class="text-muted">{{$item->name_account}}</span>
                </td>
                <td>Rp. {{number_format($item->total)}}</td>
                <td>{{$item->status_text}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

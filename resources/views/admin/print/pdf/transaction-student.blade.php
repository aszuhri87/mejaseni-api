<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
    
    <table class='table table-bordered'>
        <thead>
            <tr>
                <th>No</th>
                <th>No Transaksi</th>
                <th>Tanggal</th>
                <th>Class</th>
                <th>Jumlah</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $i=1 @endphp
            @foreach($data as $value)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{$value->number}}</td>
                <td>{{date('d-m-Y H:i:s', strtotime($value->datetime))}}</td>
                @if(!empty($value->classroom_name))
                    <td>
                        {{$value->classroom_name}}<br>
                        @if($value->package_type == 1)
                            <span class="text-muted">Special Class</span>
                        @else
                            <span class="text-muted">Regular Class</span>
                        @endif
                    </td>
                @elseif(!empty($value->master_lessons_name))
                    <td>
                        {{$value->classroom_name}}<br>
                        <span class="text-muted">Master Lesson Class</span>
                    </td>
                @elseif(!empty($value->session_video_name))
                    <td>
                        {{$value->session_video_name}}<br>
                        <span class="text-muted">Session Video</span>
                    </td>
                @elseif(!empty($value->theory_name))
                    <td>
                        {{$value->theory_name}}<br>
                        <span class="text-muted">Theory</span>
                    </td>
                @endif
                <td>Rp. {{number_format($value->price)}}</td>
                <td>
                    @if($value->status == 0)
                        <span class="label label-pill label-inline label-danger mr-2">Cancel<span>
                    @elseif($value->status == 1)
                        <span class="label label-pill label-inline label-secondary mr-2">Waiting<span>
                    @elseif($value->status == 2)
                        <span class="label label-pill label-inline label-success mr-2">Success<span>
                    @elseif($value->status == 3)
                        <span class="label label-pill label-inline label-warning mr-2">Return<span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
</body>

</html>

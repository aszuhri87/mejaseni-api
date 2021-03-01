@extends('layouts.app')

@section('content')
<a href="https://sandbox.doku.com/wt-frontend-transaction/dynamic-payment-page?signature=HMACSHA256%3DiyVhX4GyKmhKCpyjvveDX7it7ybmScEhMGrNGQMYffQ%3D&clientId=MCH-0043-1376322085194&invoiceNumber=MJSN202116&requestId=74139"
    target="popup"
    onclick="window.open('https://sandbox.doku.com/wt-frontend-transaction/dynamic-payment-page?signature=HMACSHA256%3DiyVhX4GyKmhKCpyjvveDX7it7ybmScEhMGrNGQMYffQ%3D&clientId=MCH-0043-1376322085194&invoiceNumber=MJSN202116&requestId=74139','popup','width=600,height=600'); return false;">
        Open Link in Popup
</a>
<iframe src="{{url('my-sandbox')}}" frameborder="1" width="800" height="500"></iframe>
@endsection

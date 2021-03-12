<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <base href="{{ asset('cms/assets') }}">
    <meta charset="utf-8">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? 'Mejaseni - '.$title : 'Mejaseni'}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="cart-container">
        <div class="row">
            <div class="col-12">
                <div class="cart__wrapper m-5 p-5 d-flex justify-content-center align-items-center">
                    <div class="text-center">
                        <h1>Pembelian Gagal!</h1>
                        <p class="my-4">
                            Pemesanan Anda gagal. Silahkan mengulangi pembayaran.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>


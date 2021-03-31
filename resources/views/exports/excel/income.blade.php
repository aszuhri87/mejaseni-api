<table>
    <thead>
        <tr>
            <th><b>No</b></th>
            <th><b>Nomor</b></th>
            <th><b>Nama</b></th>
            <th><b>Tanggal Permintaan</b></th>
            <th><b>Tanggal Pencairan</b></th>
            <th><b>Bank</b></th>
            <th><b>Rekening</b></th>
            <th><b>Nama Rekening</b></th>
            <th><b>Total</b></th>
            <th><b>Persetujuan</b></th>
        </tr>
    </thead>
    <tbody>
    @foreach($transactions as $index => $transaction)
        <tr>
            <td>{{$index + 1}}</td>
            <td>{{$transaction->number}}</td>
            <td>{{$transaction->name}}</td>
            <td>{{$transaction->datetime}}</td>
            <td>{{$session_date}}</td>
            <td>{{$transaction->bank}}</td>
            <td>{{$transaction->bank_number}}</td>
            <td>{{$transaction->name_account}}</td>
            <td>{{$transaction->total}}</td>
            <td></td>
        </tr>
    @endforeach
    </tbody>
</table>


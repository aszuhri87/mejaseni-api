<table>
    <thead>
        <tr>
            <th><b>No</b></th>
            <th><b>Tanggal</b></th>
            <th><b>Nomor</b></th>
            <th><b>Coach</b></th>
            <th><b>Bank</b></th>
            <th><b>Rekening</b></th>
            <th><b>Nama Pemilik Rekening</b></th>
            <th><b>Total</b></th>
            <th><b>Status</b></th>
            <th><b>Bukti</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $item)
        <tr>
            <td>{{$index + 1}}</td>
            <td>{{$item->datetime}}</td>
            <td>{{$item->number}}</td>
            <td>{{$item->coach}}</td>
            <td>{{$item->bank}}</td>
            <td>{{$item->bank_number}}</td>
            <td>{{$item->name_account}}</td>
            <td>{{$item->total}}</td>
            <td>{{$item->status_text}}</td>
            <td>{{$item->image ? $item->image_url : '-'}}</td>
        </tr>
        @endforeach
    </tbody>
</table>

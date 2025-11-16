<table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse; width:100%; font-size:12px;">
    <thead style="background-color: #f2f2f2;">
        <tr>
            <th>NO.</th>
            <th>DESCRIPTION</th>
            <th>ID</th>
            <th>BW</th>
            <th>LAYANAN</th>
            <th>PEMAKAIAN AVG (Kbps)</th>
            <th>TRAFFIK TOTAL (GB)</th>
            <th>PERIOD</th>
            <th>HARGA PER LOKASI</th>
            <th>SUB TOTAL PER CABANG</th>
            <th>HARGA NORMAL LAMPIRAN KONTRAK</th>
            <th>SUB TOTAL PER CABANG</th>
        </tr>
    </thead>
    <tbody>
        @php $rowNum = 1; @endphp
        @foreach ($data as $regionName => $transactions)
            @if (count($transactions))
                <tr>
                    <td colspan="12" style="font-weight:bold; background-color:#e1eaf7;">
                        {{ $regionName }}
                    </td>
                </tr>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $rowNum++ }}</td>
                        <td>{{ $transaction->customer->nama ?? '-' }}</td>
                        <td style="mso-number-format:'\@'; text-align:center;">{{ $transaction->customer->id_pelanggan ?? '-' }}</td>
                        <td style="text-align:center;">{{ $transaction->bandwith ?? '-' }}</td>
                        <td style="text-align:center;">{{ $transaction->customer->layanan ?? '-' }}</td>
                        <td style="text-align:right;">{{ $transaction->pemakaian ?? '-' }}</td>
                        <td style="text-align:right;">
                            {{ $transaction->total ?? '-' }}
                        </td>
                        <td>{{ $transaction->periode ?? '-' }}</td>
                        <td>
                            @php
                                $harga = $transaction->harga_satuan ?? 0;
                            @endphp
                            {{ number_format($harga, 0, ',', '.') }}
                        </td>
                        <td>
                           
                        </td>
                        <td>
                            @php
                                $harga_normal = $transaction->harga_normal ?? $transaction->harga_satuan;
                            @endphp
                            {{ number_format($harga_normal, 0, ',', '.') }}
                        </td>
                        <td>
                            
                        </td>
                    </tr>
                @endforeach
                @php
                    $regionSubtotalHargaSatuan = collect($transactions)->sum(function ($t) {
                        return (float) ($t->harga_satuan ?? 0);
                    });
                    $regionSubtotalHargaNormal = collect($transactions)->sum(function ($t) {
                        return (float) ($t->harga_normal ?? $t->harga_satuan);
                    });
                @endphp
                <tr>
                    <td colspan="9" style="font-weight:bold; text-align:right; background-color:#f9f9f9;">SUB TOTAL
                    </td>
                    <td style="font-weight:bold; background-color:#f9f9f9;">
                        {{ number_format($regionSubtotalHargaSatuan, 0, ',', '.') }}
                    </td>
                    <td style="background-color:#f9f9f9;"></td>
                    <td style="font-weight:bold; background-color:#f9f9f9;">
                        {{ number_format($regionSubtotalHargaNormal, 0, ',', '.') }}
                    </td>                    
                </tr>
                 <tr>
                <td colspan="12"></td>
            </tr>
            @endif           
        @endforeach
    </tbody>
</table>

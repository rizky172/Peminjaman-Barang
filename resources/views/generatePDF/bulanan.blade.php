<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<title>KOMISI APARATUR SIPIL NEGARA</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
	<style type="text/css">
		table tr td,
		table tr th{
			font-size: 8pt;
		},
		.image-logo{
			height: 200px;
			weight: 200px;
			margin-left: 30%;
		}
		.borderless td, .borderless th {
			border: none;
		}
	</style>
	<div class="container">
		<img src="{{ public_path('images/logo-kasn.jpg')}}" class="image-logo">
		<br>
		<br>
		<p style="text-align:center"><b>LAPORAN PEMINJAMAN KENDARAAN OPERASIONAL</b></p>
		<P style="text-align:center"><b>TAHUN 2021</b></P>
		<br>
	</div>
	<table class="table table-bordered table-striped">
        <thead>
            <tr style="text-align:center">
                <th>No</th>
                <th>NIP</th>
                <th>Pegawai</th>
                <th>No Plat</th>
                <th>Mobil</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Keperluan</th>
            </tr>
        </thead>
		<tbody>
			@php $i=1 @endphp
			@foreach($data as $row)
				<tr>
					<td style="text-align:center">{{ $i++ }}</td>
                    <td>{{ $row->nip }}</td>
                    <td>{{ $row->pegawai }}</td>
                    <td>{{ $row->no_plat }}</td>
                    <td>{{ $row->jenis }}</td>
                    <td>{{ $row->tgl_pinjam }}</td>
                    <td>{{ $row->tgl_kembali }}</td>
                    <td>{{ $row->keperluan }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
    <table class='table borderless' >
		<tbody>
            <tr>
                <th style="width:40%"></th>
                <th style="width:15%"></th>
                <th style="width:15%"></th>
                <th style="width:30%">Jakarta {{ date('d F Y') }}</th>
            </tr>
            <tr>
                <th style="width:40%"></th>
                <th style="width:15%"></th>
                <th style="width:15%"></th>
                <th style="width:30%">Pengelola Barang Milik Negara</th>
            </tr>
		</tbody>
	</table>
    <br>
	<br>
	<table class='table borderless'>
		<tbody>
            <tr>
                <th style="width:40%"></th>
                <th style="width:15%"></th>
                <th style="width:15%"></th>
                <th style="width:30%">Darussalam</th>
            </tr>
		</tbody>
	</table>
</body>
</html>
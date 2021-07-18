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
		<p style="text-align:center"><b>PERMOHONAN PEMINJAMAN KENDARAAN OPERASIONAL</b></p>
		<P style="text-align:center"><b>Nomor : .............. /BAST.RAN/KASN/05/21</b></P>
		<br>
	</div>
	<table class='table borderless' cellpadding="0">
		<tbody>
			@php $i=1 @endphp
			@foreach($data as $row)
				<tr>
					<th style="width:30%">NAMA</th>
					<td style="width:5%">:</td>
					<td style="width:65%" colspan="2">{{ $row->pegawai }}</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<th style="width:30%">NIP / JABATAN</th>
					<td style="width:5%">:</td>
					<td style="width:35%" colspan="2">{{ $row->nip }}</td>
					<td>/ {{ $row->jabatan }}</td>
					<td></td>
				</tr>
				<tr>
					<th style="width:30%">JENIS / NO PLAT</th>
					<td style="width:5%">:</td>
					<td style="width:65%" colspan="2">{{ $row->jenis }}</td>
					<td>/ {{ $row->no_plat }}</td>
					<td></td>
				</tr>
				<tr>
					<th style="width:30%">KUNCI & STNK ASLI </th>
					<td style="width:5%">:</td>
					<td style="width:65%" colspan="2">YA / TIDAK</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<th style="width:30%">TANGGAL PINJAM </th>
					<td style="width:5%">:</td>
					<td style="65%" colspan="2">{{ date('d F Y', strtotime($row->tgl_pinjam)) }}</td>
					<td>TANGGAL PENGEMBALIAN</td>
					<td>{{ date('d F Y', strtotime($row->tgl_kembali)) }}</td>
				</tr>
				<tr>
					<th style="width:30%">KEPERLUAN</th>
					<td style="width:5%">:</td>
					<td style="width:65%" colspan="2">{{ $row->keperluan }}</td>
					<td></td>
					<td></td>
				</tr>
			@endforeach
		</tbody>
	</table>
	<table class='table borderless' >
		<tbody>
			@php $i=1 @endphp
			@foreach($data as $row)
				<tr>
					<th style="width:40%">Penanggung Jawab Pemakai Kendaraan</th>
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
			@endforeach
		</tbody>
	</table>
	<br>
	<br>
	<table class='table borderless'>
		<tbody>
			@php $i=1 @endphp
			@foreach($data as $row)
				<tr>
					<th style="width:40%">{{ $row->pegawai }}</th>
					<th style="width:15%"></th>
					<th style="width:15%"></th>
					<th style="width:30%">{{ $row->user }}</th>
				</tr>
				<tr>
					<th style="width:40%">NIP. {{ $row->nip }}</th>
					<th style="width:15%"></th>
					<th style="width:15%"></th>
					<th style="width:30%">NIP. {{ $row->nip_user }}</th>
				</tr>
			@endforeach
		</tbody>
	</table>
	<label for="">Catatan</label>
	<table class='table borderless'>
		<tbody>
			<tr>
				<td>1.</td>
				<td>Peminjam bertanggung jawab penuh terhadap segala jenis kerusakan baik yang 
				disengaja ataupun tidak disengaja</td>
			</tr>
			<tr>
				<td>2.</td>
				<td>Peminjam bertanggung jawab penuh bilamana terjadi kehilangan</td>
			</tr>
			<tr>
				<td>3.</td>
				<td>Kendaraan bisa segera digunakan setelah formulir permohonan ini ditanda tangani oleh 
				Penanggung Jawab pemakai kendaraan tersebut</td>
			</tr>
		</tbody>
	</table>
</body>
</html>
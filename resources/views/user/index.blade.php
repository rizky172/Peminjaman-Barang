@extends('layouts.app')

@section('content')
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
    </div>
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><i class="fa fa-users"></i> Users </h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
          <a href="/pembelian/form_add" class="btn btn-primary waves-effect waves-light m-b-5"> <i class="fa fa-plus m-r-5"></i> <span>Tambah Data</span></a>
            <table id="datatable" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th style="text-align:center">No</th>
                  <th style="text-align:center">Nama</th>
                  <th style="text-align:center">Email</th>
                  <th style="text-align:center"></th>
                </tr>
              </thead>
              <tbody>
              @if ($data)
              @foreach ($data as $key)  
                <tr data-id="{{ $key->id_barang }}">
                    <td style="text-align:center">{{ $no++ }}</td>
                    <td style="text-align:center">{{ $key->name }}</td>
                    <td style="text-align:center">{{ $key->email }}</td>
                    <td style="text-align:center">
                      <div class='tooltip-demo'>
                        <a data-toggle='modal' class='btn btn-warning btn-sm' data-popup='tooltip' data-placement='top' title='Edit Data'><i class='glyphicon glyphicon-edit'></i>
                        </a>
                        <a class='btn btn-danger btn-sm' data-toggle='tooltip' data-placement='top' title='Hapus Data'><i class='glyphicon glyphicon-trash'></i>
                        </a>
                      </div>
                    </td>
                </tr>
              @endforeach
            @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

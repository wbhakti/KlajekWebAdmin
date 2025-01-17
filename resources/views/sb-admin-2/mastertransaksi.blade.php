@extends('sb-admin-2.layouts.app')

@section('content')
<!-- CSS custom -->
<link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">

<style>
.card-header {
    background-color: #fff;
}
.mr-0 {
    margin-right: 0;
}
.ml-auto {
    margin-left: auto;
}
.d-block {
    display: block;
}
.button-group a {
    margin-bottom: 10px;
}
</style>

<!-- DataTales Example -->
<div class="card shadow mb-4 custom-card-header">
    <div class="card-header py-3">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Data Transaksi</h1>

        @if(isset($error))
        <div align="center">
            <text style="color:red">{{ $error }}</text>
        </div>
        @endif
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Nama Merchant</th>
                        <th>Nominal Transaksi</th>
                        <th>Ongkos Kirim</th>
                        <th>Biaya Layanan</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($data) && count($data) > 0)
                        @foreach ($data['data'] as $item)
                        
                        <tr>
                            <td>{{ $item['id_transaction'] }}</td>
                            <td>{{ $item['merchant']['name'] }}</td>
                            <td>{{ $item['total'] }}</td>
                            <td>{{ $item['ongkir'] }}</td>
                            <td>{{ $item['fee'] }}</td>
                            <td>
                                <form method="POST" action="/dashboard/detailtransaksi" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item['id_transaction'] }}">
                                    <input type="hidden" name="nama_merchant" value="{{ $item['merchant']['name'] }}">
                                    <input type="hidden" name="nama_pelanggan" value="{{ $item['customer']['full_name'] }}">
                                    <input type="hidden" name="nomor_handphone" value="{{ $item['customer']['phone_number'] }}">
                                    <input type="hidden" name="nominal" value="{{ $item['total'] }}">
                                    <button type="submit" class="btn btn-warning mb-2">Detail</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">Data tidak ditemukan.</td>
                        </tr>
                    @endif
                </tbody>                
            </table>
        </div>
    </div>
    <hr>
</div>

@if(session('success'))
<script>
    alert('{{ session('success') }}');
</script>
@endif
@if(session('error'))
<script>
    alert('{{ session('error') }}');
</script>
@endif

<!-- Page level plugins -->
<script src="{{ asset('vendor/jquery/jquery-3.3.1.min.js')}}"></script>
<script src="{{ asset('vendor/jquery/jquery.validate.min.js')}}"></script>
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<script>
$(document).ready(function() {
    $('#dataTable').dataTable({
        "lengthMenu": [10, 20, 50, 100],
        "pageLength": 10,
        searching: true
    });
});
</script>

<script>
    $(document).ready(function() {
        $("#toggleButton").click(function() {
            $("#myForm").toggle();
        });
    });
</script>

@endsection
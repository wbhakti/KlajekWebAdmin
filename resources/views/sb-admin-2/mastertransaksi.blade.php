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
        <div align="center">
            <h1 class="h3 mb-4 text-gray-800">Data Transaksi</h1>
        </div>
       
        <form method="GET" action="/reportTransaction">
            <div class="row justify-content-center">
                <div class="col-sm-4">
                    <div class="mb-3">
                        <label for="date_start" class="form-label">Tanggal Awal</label>
                        <input type="date" class="form-control form-control" id="date_start" name="date_start" 
                            value="{{ isset($date_start) ? $date_start : old('date_start') }}" required>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="mb-3">
                        <label for="date_end" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control form-control" id="date_end" name="date_end" 
                            value="{{ isset($date_end) ? $date_end : old('date_end') }}" required>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success" name="action" value="report">Tampilkan Data</button>
            </div>
        </form>
    </div>
       

    <div class="card-header py-3">
        <!-- Page Heading -->
        @if(isset($data) && count($data['merchants']) > 0)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>{{ 'Jumlah Transaksi' }}</td>
                            <td>{{ $data['count_order'] }}</td>
                        </tr>
                        <tr>
                            <td>{{ 'Total Order' }}</td>
                            <td>{{ $data['order_all'] }}</td>
                        </tr>
                        <tr>
                            <td>{{ 'Total Ongkir' }}</td>
                            <td>{{ $data['ongkir_all'] }}</td>
                        </tr>
                        <tr>
                            <td>{{ 'Total Fee' }}</td>
                            <td>{{ $data['fee_all'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center">
                <p>Data tidak ditemukan.</p>
            </div>
        @endif
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID Merchant</th>
                        <th>Nama Merchant</th>
                        <th>Jumlah Transaksi</th>
                        <th>Total Order</th>
                        <th>Total Ongkir</th>
                        <th>Total Fee</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($data) && count($data['merchants']) > 0)
                        @foreach ($data['merchants'] as $item)
                            <tr>
                                <td>{{ $item['merchant_id'] }}</td>
                                <td>{{ $item['merchant_name'] }}</td>
                                <td>{{ $item['total_transaksi'] }}</td>
                                <td>{{ $item['total_order'] }}</td>
                                <td>{{ $item['total_ongkir'] }}</td>
                                <td>{{ $item['total_fee'] }}</td>
                                <td>
                                    <form method="POST" action="/dashboard/detailtransaksimerchant" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="date_start" value="{{ $data['date_start'] }}">
                                        <input type="hidden" name="date_end" value="{{ $data['date_end'] }}">
                                        <input type="hidden" name="merchant_id" value="{{ $item['merchant_id'] }}">
                                        <input type="hidden" name="merchant_name" value="{{ $item['merchant_name'] }}">
                                        <input type="hidden" name="total_transaksi" value="{{ $item['total_transaksi'] }}">
                                        <input type="hidden" name="total_order" value="{{ $item['total_order'] }}">
                                        <input type="hidden" name="total_ongkir" value="{{ $item['total_ongkir'] }}">
                                        <input type="hidden" name="total_fee" value="{{ $item['total_fee'] }}">
                                        <button type="submit" class="btn btn-warning mb-2">Detail</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center">Data tidak ditemukan.</td>
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
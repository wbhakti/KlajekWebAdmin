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
        <div class="table-responsive">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td>{{ 'ID Transaksi' }}</td>
                    <td>{{ $idTransaksi }}</td>
                </tr>
                <tr>
                    <td>{{ 'Nama Merchant' }}</td>
                    <td>{{ $namaMerchant }}</td>
                </tr>
                <tr>
                    <td>{{ 'Nama Pelanggan' }}</td>
                    <td>{{ $namaPelanggan }}</td>
                </tr>
                <tr>
                    <td>{{ 'No Hp' }}</td>
                    <td>{{ $nomorHandphone }}</td>
                </tr>
                <tr>
                    <td>{{ 'Nominal Order' }}</td>
                    <td>{{ $nominal }}</td>
                </tr>
            </tbody>
        </table>
        </div>
        
        <br>
        <a href="{{ url('dashboard/mastertransaksi') }}" class="btn btn-primary mb-2">Kembali</a>
    </div>   

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Nama Menu</th>
                        <th>Harga</th>
                        <th>Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($data['data']) && count($data['data']) > 0)
                    @foreach ($data['data'] as $item)
                    <tr>
                        <td>{{ $item['menu']['sku'] }}</td>
                        <td>{{ $item['menu']['nama'] }}</td>
                        <td>{{ $item['menu']['harga'] }}</td>
                        <td>{{ $item['menu']['kategori'] }}</td>
                    </tr>
                    @endforeach
                    @else
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
        "pageLength": 100,
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
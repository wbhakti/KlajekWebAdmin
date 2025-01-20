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
        <a href="{{ url('reportTransaction') }}" class="btn btn-primary mb-2">Kembali</a>
        <br>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>{{ 'Tanggal Awal' }}</td>
                        <td>{{ $date_start }}</td>
                    </tr>
                    <tr>
                        <td>{{ 'Tanggal Akir' }}</td>
                        <td>{{ $date_end }}</td>
                    </tr>
                    <tr>
                        <td>{{ 'Nama Merchant' }}</td>
                        <td>{{ $merchant_name }}</td>
                    </tr>
                    <tr>
                        <td>{{ 'Jumlah Transaksi' }}</td>
                        <td>{{ $total_transaksi }}</td>
                    </tr>
                    <tr>
                        <td>{{ 'Total Order' }}</td>
                        <td>{{ $total_order }}</td>
                    </tr>
                    <tr>
                        <td>{{ 'Total Ongkir' }}</td>
                        <td>{{ $total_ongkir }}</td>
                    </tr>
                    <tr>
                        <td>{{ 'Total Fee' }}</td>
                        <td>{{ $total_fee }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>   

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>No Hp Pelanggan</th>
                        <th>Nama Pelanggan</th>
                        <th>Alamat</th>
                        <th>Order</th>
                        <th>Ongkir</th>
                        <th>Fee</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($data['data']) && count($data['data']) > 0)
                    @foreach ($data['data'] as $item)
                    <tr>
                        <td>{{ $item['id_transaction'] }}</td>
                        <td>{{ $item['customer']['phone_number'] }}</td>
                        <td>{{ $item['customer']['full_name'] }}</td>
                        <td>{{ $item['customer']['address'] }}</td>
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

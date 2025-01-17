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
        <h1 class="h3 mb-2 text-gray-800">Master Kategori - {{$namaMerchant}}</h1>
        <p class="mb-4" style="color:black">
            {{$deskripsiMerchant}}
        </p>
        <div style="margin-top: 20px; display: flex; align-items: center;">
            <img src="{{ $imgMerchant }}" alt="Logo" class="img-fluid" style="max-width: 50%; height: auto;">
        </div>
        <br>
        <a href="{{ url('dashboard/mastermerchant') }}" class="btn btn-primary mb-2">Back To Merchant</a>
    </div>    

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($data) && count($data) > 0)
                    @foreach ($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item['nama'] }}</td>
                        <div class="button-group">
                        <td>
                            <button type="button" class="btn btn-primary mb-2 btn-edit"
                                    data-rowid="{{ $item['id'] }}"
                                    data-nama="{{ $item['nama'] }}"
                                    data-merchantid="{{ $item['merchant_id'] }}">Edit</button>
                                <form method="POST" action="/postkategori" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="kategori_id" value="{{ $item['id'] }}">
                                    <button type="submit" name="proses" value="delete" class="btn btn-danger mb-2">Delete</button>
                                </form> 
                        </td>
                                                               
                        </div>
                    </tr>
                    @endforeach
                    @else

                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <hr>
    <div align="center">
        <button id="toggleButton" class="btn btn-success">Add New Kategori</button>
    </div>
    <br>
    <div id="myForm" style="display: none;">
        <div class="col-xl-8 col-lg-7 mx-auto">
            <!-- Project Card Example -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form method="POST" action="/postkategori">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="form-group col-sm-6">
                                <label for="nama"><b>Nama Kategori</b></label>
                                <input type="text" name="nama" class="form-control" required />
                                <input type="hidden" name="merchant_id" value="{{$merchantId}}"/>
                            </div>
                        </div>
                        <br />
                        <div align="center">
                            <button type="submit" name="proses" value="save" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Kategori</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" action="/postkategori">
                        @csrf
                        <input type="hidden" name="proses" value="edit">
                        <input type="hidden" name="kategori_id" id="editRowid">
                        <input type="hidden" name="merchant_id" id="editMerchantid">
                        <div class="form-group">
                            <label for="editNama"><b>Nama Kategori</b></label>
                            <input type="text" name="nama" id="editNama" class="form-control" required />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="proses" value="edit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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

<script>
    $(document).ready(function() {
        // Delegasi event untuk tombol edit
        $(document).on('click', '.btn-edit', function() {
            var rowid = $(this).data('rowid');
            var nama = $(this).data('nama');
            var merchantid = $(this).data('merchantid');
            
            // Set modal data
            $('#editRowid').val(rowid);
            $('#editNama').val(nama);
            $('#editMerchantid').val(merchantid);

            // Show modal
            $('#editModal').modal('show');
        });
    });
</script>


@endsection
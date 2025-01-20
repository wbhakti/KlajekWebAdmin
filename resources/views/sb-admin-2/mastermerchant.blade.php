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
        <h1 class="h3 mb-2 text-gray-800">Data Master Merchant</h1>

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
                        <th>No</th>
                        <th>Nama Merchant</th>
                        <th>Lokasi Merchant</th>
                        <th>Image Merchant</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($data) && count($data) > 0)
                        @foreach ($data['data'] as $item)
                        
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item['nama'] }}</td>
                            <td>{{ $item['deskripsi'] }}</td>
                            <td>
                                <img src="{{ $item['image_url'] }}" alt="Thumbnail" style="max-width: 100px; max-height: 100px;">
                            </td>
                            <td>
                                <div class="button-group">
                                    <div class="button-group">
                                        <button type="button" class="btn btn-primary mb-2 btn-edit"
                                            data-rowid="{{ $item['id'] }}"
                                            data-nama="{{ $item['nama'] }}"
                                            data-deskripsi="{{ $item['deskripsi'] }}"
                                            data-latitude="{{ $item['location']['latitude'] }}"
                                            data-longitude="{{ $item['location']['longitude'] }}"
                                            data-oldimage="{{ $item['image_url'] }}">Edit</button>
                                    
                                        <form method="POST" action="/dashboard/kategori" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $item['id'] }}">
                                            <input type="hidden" name="nama" value="{{ $item['nama'] }}">
                                            <input type="hidden" name="deskripsi" value="{{ $item['deskripsi'] }}">
                                            <input type="hidden" name="img" value="{{ $item['image_url'] }}">
                                            <button type="submit" class="btn btn-success mb-2">Kategori</button>
                                        </form>

                                        <form method="POST" action="/dashboard/mastermenu" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $item['id'] }}">
                                            <input type="hidden" name="nama" value="{{ $item['nama'] }}">
                                            <input type="hidden" name="deskripsi" value="{{ $item['deskripsi'] }}">
                                            <input type="hidden" name="img" value="{{ $item['image_url'] }}">
                                            <button type="submit" class="btn btn-warning mb-2">Menu</button>
                                        </form>
                                    
                                        <!-- Form untuk tombol Delete -->
                                        <form method="POST" action="/postmerchant" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="merchant_id" value="{{ $item['id'] }}">
                                            <button type="submit" name="proses" value="delete" class="btn btn-danger mb-2">Delete</button>
                                        </form>
                                    </div>                                    
                                </div>
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
    <div align="center">
        <button id="toggleButton" class="btn btn-success">Add New Merchant</button>
    </div>
    <br>
    <div id="myForm" style="display: none;">
        <div class="col-xl-8 col-lg-7 mx-auto">
            <!-- Project Card Example -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form method="POST" action="/postmerchant" enctype="multipart/form-data">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="form-group col-sm-6">
                                <label for="nama"><b>Nama Merchant</b></label>
                                <input type="text" name="nama" class="form-control" required />
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="deskripsi"><b>Desc Merchant</b></label>
                                <textarea name="deskripsi" class="form-control" rows="4" required></textarea>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="form-group col-sm-6">
                                <label for="latitude"><b>Latitude</b></label>
                                <input type="text" name="latitude" class="form-control" required />
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="longitude"><b>Longitude</b></label>
                                <input type="text" name="longitude" class="form-control" required />
                            </div>                        
                        </div>
                        <div class="row justify-content-center">
                            <div class="form-group col-sm-6">
                                <label for="img_merchant"><b>Image Merchant (900x400)</b></label>
                                <input type="file" name="img_merchant" class="form-control" accept="image/*" required />
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
                    <h5 class="modal-title" id="editModalLabel">Edit Merchant</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" action="/postmerchant" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="proses" value="edit">
                        <input type="hidden" name="merchant_id" id="editRowid">
                        <div class="form-group">
                            <label for="editNama"><b>Nama Merchant</b></label>
                            <input type="text" name="nama" id="editNama" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label for="editDeskripsi"><b>Deskripsi</b></label>
                            <textarea name="deskripsi" id="editDeskripsi" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="editLatitude"><b>Latitude</b></label>
                            <input type="text" name="latitude" id="editLatitude" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label for="editLongitude"><b>Longitude</b></label>
                            <input type="text" name="longitude" id="editLongitude" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label for="image"><b>Image</b></label>
                            <input type="file" name="img_merchant" class="form-control" accept="image/*" />
                        </div>
                        <div class="form-group">
                            <label><b>Current Image</b></label>
                            <img id="currentimage" src="" alt="Current Image" style="max-width: 100px; max-height: 100px;">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
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
        // Edit button click event
        $(document).on('click', '.btn-edit', function() {
            var rowid = $(this).data('rowid');
            var nama = $(this).data('nama');
            var desc = $(this).data('deskripsi');
            var lat = $(this).data('latitude');
            var long = $(this).data('longitude');
            var imgMerchant = $(this).data('oldimage');
            
            // Set modal data
            $('#editRowid').val(rowid);
            $('#editNama').val(nama);
            $('#editDeskripsi').val(desc);
            $('#editLatitude').val(lat);
            $('#editLongitude').val(long);
            $('#currentimage').attr('src', imgMerchant);

            // Show modal
            $('#editModal').modal('show');
        });
    });
</script>


@endsection
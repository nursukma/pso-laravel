@extends('layouts.default')

@section('page-style')
    <style>
        /* Style the loading spinner overlay */
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        /* Style the loading spinner */
        .spinner-border {
            color: #ffffff;
            /* Adjust the color as needed */
        }
    </style>
@endsection

@section('content')
    <main class="main" id="main">
        <div class="pagetitle">
            <h1>Data Variabel</h1>
        </div>

        <div id="loading-spinner" class="spinner-overlay">
            <div class="spinner-border" style="width: 50px; height: 50px;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <div class="row">
            <!-- Tables -->
            <div class="col-xl-12 col-lg-10">
                <div class="card shadow mb-4">
                    <div class="card-header d-flex align-items-center justify-content-end mx-2">
                        <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                            data-bs-toggle="modal" data-bs-target="#addModal">
                            Tambah
                        </button>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="row mx-auto mt-1">
                            <div class="col-12">
                                {{-- @dd(session()->get('data')) --}}
                                <table class="table table-hover" id="table-data">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">ID</th>
                                            <th scope="col">Nama Variabel</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $jsonData)
                                            @php
                                                $item = json_decode($jsonData, true);
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item['id'] }}</td>
                                                <td>{{ $item['data'] }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-light rounded-pill" title="Hapus"
                                                        id="hapus" name="hapus" data-bs-target="#deleteModal"
                                                        data-bs-toggle="modal" data-bs-id="{{ $item['id'] }}">
                                                        <i class="ri-delete-bin-line"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal hitung -->
        <div class="modal fade" id="addModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
            aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Form Tambah Variabel</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="row g-2 needs-validation" action="{{ route('variabel.add') }}" method="post"
                        enctype="multipart/form-data" novalidate>
                        @csrf
                        <div class="modal-body">
                            <div class="col-md-12">
                                <label for="bdv" class="form-label">Nama Variabel</label>
                                <div class="input-group has-validation">
                                    <input type="text" class="form-control" id="nama_variabel" name="nama_variabel"
                                        required placeholder="Contoh: BDV">
                                    <div class="invalid-feedback">Harap isi bidang ini!</div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal edit item --}}
        <div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ubah Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    {{-- <form class="row g-2 needs-validation" novalidate> --}}
                    <form class="row g-2 needs-validation" id="update-form" action="/" method="post"
                        enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('put')
                        <div class="modal-body">
                            <div class="col-12">
                                <label for="link" class="form-label">BDV</label>
                                <div class="input-group has-validation">
                                    <input name="bdv" type="text" class="form-control" id="bdv"
                                        onkeypress="return isNumberKey(this, event);" required>
                                    <div class="invalid-feedback">Harap isi bidang ini!</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="water" class="form-label">Water</label>
                                <div class="input-group has-validation">
                                    <input name="water" type="text" class="form-control" id="water"
                                        onkeypress="return isNumberKey(this, event);" required>
                                    <div class="invalid-feedback">Harap isi bidang ini!</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="acidity" class="form-label">Acidity</label>
                                <div class="input-group has-validation">
                                    <input name="acidity" type="text" class="form-control" id="acidity"
                                        onkeypress="return isNumberKey(this, event);" required>
                                    <div class="invalid-feedback">Harap isi bidang ini!</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="ift" class="form-label">IFT</label>
                                <div class="input-group has-validation">
                                    <input name="ift" type="text" class="form-control" id="ift"
                                        onkeypress="return isNumberKey(this, event);" required>
                                    <div class="invalid-feedback">Harap isi bidang ini!</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="color" class="form-label">Color</label>
                                <div class="input-group has-validation">
                                    <input name="color" type="text" class="form-control" id="color"
                                        onkeypress="return isNumberKey(this, event);" required>
                                    <div class="invalid-feedback">Harap isi bidang ini!</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="target" class="form-label">Target</label>
                                <div class="input-group has-validation">
                                    <input name="target" type="text" class="form-control" id="target"
                                        onkeypress="return isNumberKey(this, event);" required>
                                    <div class="invalid-feedback">Harap isi bidang ini!</div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-success" id="update-data">Simpan
                            </button>
                            {{-- <a class="btn btn-success" id="perbarui">Simpan
                            </a> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal hapus --}}
        <div class="modal fade" id="deleteModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog" role="dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="row g-3 needs-validation" id="delete-form" action="/" method="post" novalidate>
                        @csrf
                        @method('delete')
                        <div class="modal-body">
                            <p class="text-center">
                                Yakin untuk menghapus data no <strong class="badge border-danger border-1 text-danger"
                                    id="title"> </strong>?
                            </p>
                            <div class="alert alert-danger text-center" role="alert">
                                <i class="bi bi-exclamation-octagon me-1"></i>
                                <span class=""> Perhatian! data akan terhapus dan tidak dapat
                                    dikembalikan.</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </main>
@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            // var detailModal = bootstrap.Modal.getOrCreateInstance('#editModal');
            var deleteModal = bootstrap.Modal.getOrCreateInstance('#deleteModal');
            // var deleteAllModal = bootstrap.Modal.getOrCreateInstance('#deleteAllModal');

            var table = $('#table-data').DataTable({
                "searching": false,
                "ordering": false,
                "processing": true,
                "language": {
                    "sEmptyTable": "Tidak ada data yang tersedia",
                    "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "lengthMenu": "Menampilkan _MENU_ data",
                    "oPaginate": {
                        "sFirst": "<<",
                        "sPrevious": "<",
                        "sNext": ">",
                        "sLast": ">>"
                    },
                    'processing': 'Loading...'
                }
            });

            $("#table-data tbody").on('click', '#edit', function() {
                var id = $(this).closest('tr').index();

                var url = "{{ route('pso.search', ':id') }}";
                url = url.replace(':id', id);

                var urlUpdate = "/update-data/" + id;

                $.ajax({
                    type: 'GET',
                    url: url,
                    success: (data) => {
                        detailModal.show();
                        $('#bdv').val(data[0]);
                        $('#water').val(data[1]);
                        $('#acidity').val(data[2]);
                        $('#ift').val(data[3]);
                        $('#color').val(data[4]);
                        $('#target').val(data[5]);

                        const upForm = $('form#update-form');
                        upForm.attr('action', urlUpdate);

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true,
                            "positionClass": "toast-top-right"
                        };
                        toastr.error(errorThrown);
                    }
                });

            });

            // delete modal
            // $("#table-data tbody").on('click', '#hapus', function() {
            //     var id = $(this).closest('tr').index() + 1;
            // var index = $(this).closest('tr').index();

            // var id = $(this).closest('tr').data('id');
            // console.log(id);
            // deleteModal.show();

            // $('#title').text('"' + id + '"')

            // var urlDelete = "/delete-data/" + index;

            // const delForm = $('form#delete-form');
            // delForm.attr('action', urlDelete);
            // });

            // $('#hapus').on('click', function() {
            //     var tr = $(this).closest('tr');
            //     var row = table.row(tr);
            //     console.log(row)
            // });

            // $('#deleteAll').on('click', function() {
            //     deleteAllModal.show();

            //     var urlDelete = "{{ route('pso.hapus') }}";

            //     const delForm = $('form#deleteAll-form');
            //     delForm.attr('action', urlDelete);
            // })

            // var table = $('#table-data').DataTable({
            //     ajax: {
            //         url: '/getVariabel',
            //         type: 'GET',
            //         dataType: 'json',
            //     },
            //     columns: [{
            //             data: 'id',
            //             name: 'id'
            //         },
            //         {
            //             data: 'data',
            //             name: 'data'
            //         },
            //         {
            //             data: null,
            //             name: 'action',
            //             render: function(data, type, row) {
            //                 return '<button class="hapus-btn" data-id="' + data.id +
            //                     '">Hapus</button>';
            //             }
            //         }
            //     ],
            //     "searching": false,
            //     "ordering": false,
            //     "language": {
            //         "sEmptyTable": "Tidak ada data yang tersedia",
            //         "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            //         "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
            //         "lengthMenu": "Menampilkan _MENU_ data",
            //         "oPaginate": {
            //             "sFirst": "<<",
            //             "sPrevious": "<",
            //             "sNext": ">",
            //             "sLast": ">>"
            //         },
            //         'processing': 'Loading...'
            //     }
            // });

            // Add click event listener to the button with class 'hapus-btn'
            $(document).on('click', '.hapus-btn', function() {
                var idToDelete = $(this).data('id');
                console.log('ID to Delete:', idToDelete);
                // Perform your delete logic here or send the ID to the server for deletion
                // ...
            });
        });
    </script>
    <script>
        window.onload = function() {
            $('#loading-spinner').hide();
            $('body').css('pointer-events', 'auto');
        }
    </script>
@endsection

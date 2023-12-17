@extends('layout.Base')
@section('content')
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mt--5" style="float: left">Data Pegawai</h4>
                <button type="button" id="createData" data-toggle="modal" data-target="#add" class="btn btn-primary"
                    style="float: right">
                    Tambah Data
                </button>
            </div>
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table id="table-data" class="table table-striped">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>User</th>
                                <th>Nama</th>
                                <th>NIDN</th>
                                <th>Departement</th>
                                <th>Jabatan</th>
                                <th>TTL</th>
                                <th>Alamat</th>
                                <th>Agama</th>
                                <th>JK</th>
                                <th>No Hp</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($data['pegawai'] as $item)
                                <tr>
                                    <td style="width: 10%">{{ $no++ }}</td>
                                    <td style="width: 10%">{{ $item->user->email }}</td>
                                    <td style="width: 10%">{{ $item->nama }}</td>
                                    <td style="width: 10%">{{ $item->nidn }}</td>
                                    <td style="width: 10%">{{ $item->departement->nama_departement }}</td>
                                    <td style="width: 10%">{{ $item->jabatan->nama_jabatan }}</td>
                                    <td style="width: 10%">{{ $item->ttl }}</td>
                                    <td style="width: 10%">{{ $item->alamat }}</td>
                                    <td style="width: 10%">{{ $item->agama }}</td>
                                    <td style="width: 10%">{{ $item->jk }}</td>
                                    <td style="width: 10%">{{ $item->no_hp }}</td>
                                    <td style="width: 10%">

                                        <button id="editItem" class="btn btn-sm btn-info" data-id="{{ $item->id }}">
                                            Edit
                                        </button>
                                        <button id="btn-hapus" class="btn btn-sm btn-danger" data-id="{{ $item->id }}">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLiveLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalheader">Gate</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form id="formData" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6">
                                    <input type="hidden" name="id" id="dataId">
                                    <div class="col-12 col-md-12 mb-3">
                                        <label class="form-label">User</label><br>
                                        <select name="user_id" id="user_id" class="form-control" required>
                                            <option value="" selected disabled>--pilih--</option>
                                            @foreach ($data['user'] as $d)
                                                <option value="{{ $d->id }}">{{ $d->email }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-msg small" id="nama-alert"></span>
                                    </div>
                                    <div class="col-12 col-md-12 mb-3">
                                        <label class="form-label">Foto</label>
                                        <input type="file" class="form-control" name="foto" id="foto"
                                            placeholder="Nama" required>
                                        <span class="text-danger error-msg small" id="foto-alert"></span>
                                    </div>
                                    <div class="col-12 col-md-12 mb-3">
                                        <label class="form-label">Nama</label>
                                        <input type="text" class="form-control" name="nama" id="nama"
                                            placeholder="Nama" required>
                                        <span class="text-danger error-msg small" id="nama-alert"></span>
                                    </div>
                                    <div class="col-12 col-md-12 mb-3">
                                        <label class="form-label">NIDN</label>
                                        <input type="text" class="form-control" name="nidn" id="nidn"
                                            placeholder="Nidn" required>
                                        <span class="text-danger error-msg small" id="nama-alert"></span>
                                    </div>
                                    <div class="col-12 col-md-12 mb-3">
                                        <label class="form-label">Departement</label><br>
                                        <select name="departement_id" id="departement_id" class="form-control" required>
                                            <option value="" selected disabled>--pilih--</option>
                                            @foreach ($data['departement'] as $d)
                                                <option value="{{ $d->id }}">{{ $d->nama_departement }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-msg small" id="nama-alert"></span>
                                    </div>
                                    <div class="col-12 col-md-12 mb-3">
                                        <label class="form-label">Jabatan</label><br>
                                        <select name="jabatan_id" id="jabatan_id" class="form-control" required>
                                            <option value="" selected disabled>--pilih--</option>
                                            @foreach ($data['jabatan'] as $d)
                                                <option value="{{ $d->id }}">{{ $d->nama_jabatan }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-msg small" id="nama-alert"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="col-12 col-md-12 mb-3">
                                        <label class="form-label">TTL</label>
                                        <input type="text" class="form-control" name="ttl" id="ttl"
                                            placeholder="Tempat tanggal lahir" required>
                                        <span class="text-danger error-msg small" id="nama-alert"></span>
                                    </div>
                                    <div class="col-12 col-md-12 mb-3">
                                        <label class="form-label">Alamat</label>
                                        <input type="text" class="form-control" name="alamat" id="alamat"
                                            placeholder="Alamat" required>
                                        <span class="text-danger error-msg small" id="nama-alert"></span>
                                    </div>
                                    <div class="col-12 col-md-12 mb-3">
                                        <label class="form-label">Agama</label><br>
                                        <select name="agama" id="agama" class="form-control" required>
                                            <option value="" selected disabled>--pilih--</option>
                                            <option value="Islam">Islam</option>
                                            <option value="Kristen">Kristen</option>
                                            <option value="Hindu">Hindu</option>
                                            <option value="Buddha">Buddha</option>
                                            <option value="Konghucu">Konghucu</option>
                                        </select>
                                        <span class="text-danger error-msg small" id="nama-alert"></span>
                                    </div>
                                    <div class="col-12 col-md-12 mb-3">
                                        <label class="form-label">Jenis Kelamin</label><br>
                                        <select name="jk" id="jk" class="form-control" required>
                                            <option value="" selected disabled>--pilih--</option>
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                        <span class="text-danger error-msg small" id="nama-alert"></span>
                                    </div>
                                    <div class="col-12 col-md-12 mb-3">
                                        <label class="form-label">No Hp</label>
                                        <input type="text" class="form-control" name="no_hp" id="no_hp"
                                            placeholder="No Hp" required>
                                        <span class="text-danger error-msg small" id="nama-alert"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="text-danger small" id="nama-alert"></span>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-simpan">Tambah</button>
                </div>
                </form>
            </div>
        </div>
    </div>

@section('script')
    <script>
        let baseUrl

        $(document).ready(function() {
            baseUrl = "{{ config('app.url') }}"

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#table-data').DataTable();
        });

        $('#createData').click(function() {
            $('.modal-title').html("Formulir Tambah Data");
            $('#btn-simpan').val("create-Item");
            $('#id').val('');
            $('#dataId').val('');
            $('#formData').trigger("reset");
            $('#modal-data').modal('show');
            $('#nama-alert').html('');
        });

        $(document).on('click', '#editItem', function() {
            var _id = $(this).data('id');
            $.get(`${baseUrl}/api/w1/pegawai/` + _id, function(res) {
                $('.modal-title').html("Formulir Edit Data");
                $('#btn-simpan').val("edit-user");
                $('#nama-alert').html('                   ');
                $('#modal-data').modal('show');
                $('#user_id').val(res.data.user_id);
                $('#nama').val(res.data.nama);
                $('#nidn').val(res.data.nidn);
                $('#departement_id').val(res.data.departement_id);
                $('#jabatan_id').val(res.data.jabatan_id);
                $('#ttl').val(res.data.ttl);
                $('#alamat').val(res.data.alamat);
                $('#agama').val(res.data.agama);
                $('#jk').val(res.data.jk);
                $('#no_hp').val(res.data.no_hp);
                $('#dataId').val(res.data.id);
            })
        });

        $('#btn-simpan').click(function(e) {
            e.preventDefault();
            let submitButton = $(this);
            submitButton.html('Simpan');

            let foto = $('#foto').prop('files')[0];
            let data = new FormData($('#formData')[0]);

            let typePost
            let url
            let dataId = $('#dataId').val()

            if (dataId == '') {
                typePost = "POST"
                url = `${baseUrl}/api/w1/pegawai`
                console.log('post');
            } else {
                typePost = "PUT"
                url = `${baseUrl}/api/w1/pegawai/${dataId}`
                console.log('put');
            }

            $.ajax({
                data: data,
                url: url,
                type: typePost,
                processData: false,
                contentType: false,
                success: function(res) {
                    Swal.fire({
                        title: 'Success',
                        text: 'Data Berhasil diproses',
                        icon: 'success',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Oke'
                    }).then((res) => {
                        if (res.isConfirmed) {
                            location.reload();
                        }
                    })

                    $('#modal-data').modal('hide');
                    console.log('berhasil', result);
                },
                error: function(result) {
                    submitButton.prop('disabled', false);
                    if (result.status = 422) {
                        let data = result.responseJSON
                        let errorRes = data.errors;
                        if (errorRes.length >= 1) {
                            $('#nama-alert').html(errorRes.data.no_sesi);
                        }
                    } else {
                        let msg = 'Sedang pemeliharaan server'
                        iziToast.error(msg)
                    }
                }
            });
        });

        $(document).on('click', '#btn-hapus', function() {
            let _id = $(this).data('id');
            let url = `${baseUrl}/api/w1/pegawai/` + _id;
            Swal.fire({
                title: 'Anda Yakin?',
                text: "Data ini mungkin terhubung ke tabel yang lain!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Hapus'
            }).then((res) => {
                if (res.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'delete',
                        success: function(result) {
                            let data = result.data;
                            Swal.fire({
                                title: 'Success',
                                text: 'Data Berhasil Dihapus.',
                                icon: 'success',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Oke'
                            }).then((result) => {
                                location.reload();
                            });
                        },
                        error: function(result) {
                            let msg
                            if (result.responseJSON) {
                                let data = result.responseJSON
                                message = data.message
                            } else {
                                msg = 'Sedang pemeliharaan server'
                            }
                            iziToast.error(msg)
                        }
                    });
                }
            })
        });
    </script>
@endsection
@endsection

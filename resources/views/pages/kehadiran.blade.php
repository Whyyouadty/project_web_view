@extends('layout.Base')
@section('content')
<div class="col-xl-12">
    <div class="card">
        <div class="card-header">
            <h4 class="mt--5" style="float: left">Data Kehadiran</h4>
                    <button type="button"
                            id="createData"
                            data-toggle="modal"
                            data-target="#add"
                            class="btn btn-primary"
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
                            <th>Pegawai</th>
                            <th>Koordinat</th>
                            <th>Tanggal</th>
                            <th>Jam masuk</th>
                            <th>Jam keluar</th>
                            <th>Status</th>
                            <th>Gate</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($data['kehadiran'] as $item)
                        <tr>
                            <td style="width: 10%">{{$no++}}</td>
                            <td style="width: 10%">{{ $item->pegawai->nama }}</td>
                            <td style="width: 10%">{{ $item->koordinat->latitude }}:{{ $item->koordinat->logtitude }}</td>
                            <td style="width: 10%">{{ $item->tanggal }}</td>
                            <td style="width: 10%">{{ $item->jam_masuk }}</td>
                            <td style="width: 10%">{{ $item->jam_keluar }}</td>
                            <td style="width: 10%">{{ $item->status }}</td>
                            <td style="width: 10%">{{ $item->gate->no_sesi }}</td>
                            <td style="width: 10%">
                                
                                <button id="editItem" 
                                        class="btn btn-sm btn-info" 
                                        data-id="{{$item->id}}">
                                        Edit
                                </button>
                                <button id="btn-hapus" 
                                        class="btn btn-sm btn-danger"
                                        data-id="{{$item->id}}">
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

<div id="modal-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLiveLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalheader">Log</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form id="formData" onsubmit="return false">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="id" id="dataId">
                        <div class="col-12 col-md-12">
                            <label class="form-label">Pegawai</label><br>
                            <select name="pegawai_id" id="pegawai_id" class="form-control" required>
                                <option value="" selected disabled>--pilih--</option>
                                @foreach ($data['pegawai'] as $d)
                                    <option value="{{$d->id}}">{{$d->nama}}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-msg small" id="pegawai-alert"></span>
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label">Koordinat</label><br>
                            <select name="koordinat_id" id="koordinat_id" class="form-control" required>
                                <option value="" selected disabled>--pilih--</option>
                                @foreach ($data['koordinat'] as $d)
                                    <option value="{{$d->id}}">{{$d->latitude}}:{{$d->longtitude}}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-msg small" id="koordinat-alert"></span>
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label">Tanggal</label>
                            <input type="date" class="form-control" name="tanggal" id="tanggal" placeholder="Tanggal" required>
                            <span class="text-danger error-msg small" id="tanggal-alert"></span>
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label">Jam masuk</label>
                            <input type="time" class="form-control" name="jam_masuk" id="jam_masuk" placeholder="Jam masuk" >
                            <span class="text-danger error-msg small" id="jam_masuk-alert"></span>
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label">Jam keluar</label>
                            <input type="time" class="form-control" name="jam_keluar" id="jam_keluar" placeholder="Jam keluar" >
                            <span class="text-danger error-msg small" id="jam_keluar-alert"></span>
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label">Status</label><br>
                            <input type="text" class="form-control" name="status" id="status" placeholder="Status" readonly>
                            <span class="text-danger error-msg small" id="status-alert"></span>
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label">Gate</label><br>
                            <select name="gate_id" id="gate_id" class="form-control">
                                <option value="" selected disabled>--pilih--</option>
                                @foreach ($data['gate'] as $d)
                                    <option value="{{$d->id}}">{{$d->no_sesi}}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-msg small" id="gate-alert"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn  btn-secondary" data-dismiss="modal" >Close</button>
                    <button type="submit" class="btn  btn-primary" id="btn-simpan">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('script')
<script>
    let baseUrl;

    $(document).ready(function() {
        baseUrl = "{{ config('app.url') }}";

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#table-data').DataTable();

        // Panggil fungsi updateStatus saat halaman pertama kali di-load
        updateStatus();

        // Panggil fungsi updateStatus saat input jam_masuk berubah
        $('#jam_masuk').on('change', function() {
            updateStatus();
        });
    });

    function updateStatus() {
        const jamMasuk = $('#jam_masuk').val();

        // Lakukan perhitungan status sesuai dengan tabel setup atau logika lainnya
        let status;
        const jamMulaiKerja = moment('{{ $setupData->start }}', 'HH:mm:ss');
        const jamSelesaiKerja = moment('{{ $setupData->end }}', 'HH:mm:ss');
        const jamMasukMoment = moment(jamMasuk, 'HH:mm:ss');

        const selisihMenit = jamMasukMoment.diff(jamMulaiKerja, 'minutes');
        const batasTerlambat = 60;

        if (jamMasukMoment.isBefore(jamMulaiKerja) || jamMasukMoment.isAfter(jamSelesaiKerja)) {
            status = 'Tidak Masuk';
        } else if (selisihMenit <= batasTerlambat) {
            status = 'Masuk Tepat Waktu';
        } else {
            status = 'Terlambat';
        }

        // Tampilkan status di input status
        $('#status').val(status);
    }

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
        $.get(`${baseUrl}/api/w1/kehadiran/` + _id, function(res) {
            $('.modal-title').html("Formulir Edit Data");
            $('#btn-simpan').val("edit-user");
            $('#nama-alert').html('');
            $('#modal-data').modal('show');
            $('#pegawai_id').val(res.data.pegawai_id);
            $('#koordinat_id').val(res.data.koordinat_id);
            $('#tanggal').val(res.data.tanggal);
            $('#jam_masuk').val(res.data.jam_masuk);
            $('#jam_keluar').val(res.data.jam_keluar);
            $('#status').val(res.data.status);
            $('#gate_id').val(res.data.gate_id);
            $('#dataId').val(res.data.id);
        });
    });

    $('#btn-simpan').click(function(e) {
        e.preventDefault();
        let submitButton = $(this);
        submitButton.html('Simpan');

        let typePost;
        let url;
        let dataId = $('#dataId').val();

        if (dataId == '') {
            typePost = "POST";
            url = `${baseUrl}/api/w1/kehadiran`;
        } else {
            typePost = "PUT";
            url = `${baseUrl}/api/w1/kehadiran/${dataId}`;
        }

        $.ajax({
            data: $('#formData').serialize(),
            url: url,
            type: typePost,
            dataType: 'json',
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
                });

                $('#modal-data').modal('hide');
            },
            error: function(result) {
                submitButton.prop('disabled', false);
                if (result.status = 422) {
                    let data = result.responseJSON;
                    let errorRes = data.errors;
                    if (errorRes.length >= 1) {
                        $('#pegawai-alert').html(errorRes.data.pegawai_id);
                        $('#koordinat-alert').html(errorRes.data.koordinat_id);
                        $('#tanggal-alert').html(errorRes.data.tanggal);
                        $('#jam_masuk-alert').html(errorRes.data.jam_masuk);
                        $('#jam_keluar-alert').html(errorRes.data.jam_keluar);
                        $('#status-alert').html(errorRes.data.status);
                        $('#gate-alert').html(errorRes.data.gate_id);
                    }
                } else {
                    let msg = 'Sedang pemeliharaan server';
                    iziToast.error(msg);
                }
            }
        });
    });

    $(document).on('click', '#btn-hapus', function() {
        let _id = $(this).data('id');
        let url = `${baseUrl}/api/w1/kehadiran/` + _id;
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
                        let msg;
                        if (result.responseJSON) {
                            let data = result.responseJSON;
                            message = data.message;
                        } else {
                            msg = 'Sedang pemeliharaan server';
                        }
                        iziToast.error(msg);
                    }
                });
            }
        });
    });
</script>
@endsection
@endsection
@extends('layout.Base')
@section('content')
<div class="col-xl-12">
    <div class="card">
        <div class="card-header">
            <h4 class="mt--5" style="float: left">Data Log</h4>
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
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Koordinat</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($data['log'] as $item)
                        <tr>
                            <td style="width: 5%">{{ $no++}}</td>
                            <td style="width: 10%">{{ $item->tanggal }}</td>
                            <td style="width: 10%">{{ $item->waktu }}</td>
                            <td style="width: 10%">{{ $item->koordinat->latitude }}:{{ $item->koordinat->longtitude }}</td>
                            <td style="width: 10%">{{ $item->pegawai->nama }}</td>
                            <td style="width: 10%">{{ $item->status }}</td>
                            <td style="width: 10%">
                                
                                <button id="editItem" class="btn btn-sm btn-info" 
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
                            <label class="form-label">Tanggal</label>
                            <input type="date" class="form-control" name="tanggal" id="tanggal" placeholder="Tanggal" required>
                            <span class="text-danger error-msg small" id="tanggal-alert"></span>
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label">Waktu</label>
                            <input type="time" class="form-control" name="waktu" id="waktu" placeholder="Waktu" required>
                            <span class="text-danger error-msg small" id="waktu-alert"></span>
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
                            <label class="form-label">User</label><br>
                            <select name="pegawai_id" id="pegawai_id" class="form-control" required>
                                <option value="" selected disabled>--pilih--</option>
                                @foreach ($data['pegawai'] as $d)
                                    <option value="{{$d->id}}">{{$d->nama}}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-msg small" id="pegawai-alert"></span>
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label">Status</label>
                            <input type="text" class="form-control" name="status" id="status" placeholder="Status">
                            <span class="text-danger error-msg small" id="status-alert"></span>
                        </div>
                        
                    </div>
                    <span class="text-danger small" id="nama-alert"></span>
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
        
        $('#createData').click(function () {
            $('.modal-title').html   ("Formulir Tambah Data");
            $('#btn-simpan' ).val    ("create-Item"         );
            $('#id'         ).val    (''                    );
            $('#dataId'     ).val    (''                    );
            $('#formData'   ).trigger("reset"               );
            $('#modal-data' ).modal  ('show'                );
            $('#nama-alert' ).html   (''                    );
        });

        $(document).on('click', '#editItem', function () {
            var _id = $(this).data('id');
            $.get(`${baseUrl}/api/w1/log/` + _id, function (res) {
                $('.modal-title'   ).html ("Formulir Edit Data" );
                $('#btn-simpan'    ).val  ("edit-user"          );
                $('#nama-alert'    ).html ('                   ');
                $('#modal-data'    ).modal('show'               );
                $('#tanggal'       ).val  (res.data.tanggal);
                $('#waktu'         ).val  (res.data.waktu);
                $('#koordinat_id'  ).val  (res.data.koordinat_id);
                $('#pegawai_id'    ).val  (res.data.pegawai_id);
                $('#status'    ).val  (res.data.status);
                $('#dataId'        ).val  (res.data.id          );
            })
        });

        $('#btn-simpan').click(function (e) {
            e.preventDefault();
            let submitButton = $(this);
            submitButton.html('Simpan');

            let typePost
            let url
            let dataId = $('#dataId').val()

            if (dataId == '') {
                typePost = "POST"
                url = `${baseUrl}/api/w1/log`
                console.log('post');
            } else {
                typePost = "PUT"
                url = `${baseUrl}/api/w1/log/${dataId}`
                console.log('put');
            }

            let tanggal = $('#tanggal').val();
            let waktu = $('#waktu').val();
            let koordinat_id = $('#koordinat_id').val();
            let pegawai_id = $('#pegawai_id').val();

            if (tanggal === '' || waktu === '' || koordinat_id === '' || pegawai_id === '') {
                iziToast.error('Harap isi semua field.');
                return;
            }

            $.ajax({
                data    : $('#formData').serialize()  ,
                url     : url       ,
                type    : typePost  ,
                dataType: 'json'    ,
                success: function(res) {
                    Swal.fire({
                        title            : 'Success'               ,
                        text             : 'Data Berhasil diproses',
                        icon             : 'success'               ,
                        cancelButtonColor: '#d33'                  ,
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
                    // console.log('error', result);
                    submitButton.prop('disabled', false);
                    if (result.status = 422) {
                        let data = result.responseJSON
                        let errorRes = data.errors;
                        if (errorRes.length >= 1) {
                            $('#tanggal-alert').html(errorRes.data.tanggal);
                            $('#waktu-alert').html(errorRes.data.tanggal);
                            $('#koordinat-alert').html(errorRes.data.tanggal);
                            $('#pegawai-alert').html(errorRes.data.tanggal);
                            $('#status-alert').html(errorRes.data.tanggal);
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
            let url = `${baseUrl}/api/w1/log/` + _id;
            Swal.fire({
                title             : 'Anda Yakin?',
                text              : "Data ini mungkin terhubung ke tabel yang lain!",
                icon              : 'warning',
                showCancelButton  : true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor : '#d33',
                cancelButtonText  : 'Batal',
                confirmButtonText : 'Hapus'
            }).then((res) => {
                if (res.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'delete',
                        success: function(result) {
                            let data = result.data;
                            Swal.fire({
                                title            : 'Success'               ,
                                text             : 'Data Berhasil Dihapus.',
                                icon             : 'success'               ,
                                cancelButtonColor: '#d33'                  ,
                                confirmButtonText: 'Oke'
                            }).then((result) => {
                                location.reload();
                            });
                        },
                        error: function(result) {
                            let msg
                            if (result.responseJSON) {
                                let data = result.responseJSON
                                message  = data.message
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
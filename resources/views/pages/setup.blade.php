@extends('layout.Base')
@section('content')
<div class="col-xl-12">
    <div class="card">
        <div class="card-header">
            <h4 class="mt--5" style="float: left">Data SetUp</h4>
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
                            <th>Start</th>
                            <th>End</th>
                            <th>Tipe</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($data as $item)
                        <tr>
                            <td style="width: 20cm">{{$no++}}</td>
                            <td style="width: 20%">{{ $item->start }}</td>
                            <td style="width: 20%">{{ $item->end }}</td>
                            <td style="width: 20%">{{ $item->tipe }}</td>
                            <td style="width: 20%">
                                
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
                <h5 class="modal-title" id="modalheader">SetUp</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form id="formData" onsubmit="return false">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="id" id="dataId">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Start</label>
                            <input type="time" class="form-control" name="start" id="start" placeholder="Start" required>
                            <span class="text-danger error-msg small" id="start-alert"></span>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">End</label>
                            <input type="time" class="form-control" name="end" id="end" placeholder="End" required>
                            <span class="text-danger error-msg small" id="end-alert"></span>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Tipe</label>
                            <select name="tipe" id="tipe" class="form-control">
                                <option value="" selected disabled>--pilih--</option>
                                <option value="waktu_kerja">waktu kerja</option>
                                <option value="waktu_istirahat">waktu istirahat</option>
                            </select>
                            <span class="text-danger error-msg small" id="tipe-alert"></span>
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
            $.get(`${baseUrl}/api/w1/setup/` + _id, function (res) {
                $('.modal-title' ).html ("Formulir Edit Data" );
                $('#btn-simpan'  ).val  ("edit-user"          );
                $('#nama-alert'  ).html ('                   ');
                $('#modal-data'  ).modal('show'               );
                $('#start'       ).val  (res.data.start);
                $('#end'         ).val  (res.data.end);
                $('#tipe'        ).val  (res.data.tipe);
                $('#dataId'      ).val  (res.data.id          );
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
                url = `${baseUrl}/api/w1/setup`
                console.log('post');
            } else {
                typePost = "PUT"
                url = `${baseUrl}/api/w1/setup/${dataId}`
                console.log('put');
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
                            $('#start-alert').html(errorRes.start);
                            $('#end-alert').html(errorRes.end);
                            $('#tipe-alert').html(errorRes.tipe);
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
            let url = `${baseUrl}/api/w1/setup/` + _id;
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
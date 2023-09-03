@extends('layout.Base')
@section('content')
<div class="col-xl-12">
    <div class="card">
        <div class="card-header">
            <h4 class="mt--5" style="float: left">Data Departement</h4>
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
                            <th>Nama Departement</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($data as $item)
                        <tr>
                            <td style="width: 40%">{{$no++}}</td>
                            <td style="width: 50%">{{ $item->nama_departement }}</td>
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
                <h5 class="modal-title" id="modalheader">Departement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form id="formData" onsubmit="return false">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="id" id="dataId">
                        <div class="col-12 col-md-12">
                            <label class="form-label">Departement</label>
                            <input type="text" class="form-control" name="nama_departement" id="nama_departement" placeholder="Departement" required>
                            <span class="text-danger error-msg small" id="departement-alert"></span>
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
            $.get(`${baseUrl}/api/w1/departement/` + _id, function (res) {
                $('.modal-title' ).html ("Formulir Edit Data" );
                $('#btn-simpan'  ).val  ("edit-user"          );
                $('#nama-alert'  ).html ('                   ');
                $('#modal-data'  ).modal('show'               );
                $('#nama_departement').val  (res.data.nama_departement);
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
                url = `${baseUrl}/api/w1/departement`
                console.log('post');
            } else {
                typePost = "PUT"
                url = `${baseUrl}/api/w1/departement/${dataId}`
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
                            $('#departement-alert').html(errorRes.data.nama_departement);
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
            let url = `${baseUrl}/api/w1/departement/` + _id;
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
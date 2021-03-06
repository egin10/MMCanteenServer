@extends('layouts.app')

@section('title')
{{ request()->path() }}
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Siswa</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if(Auth::user()->role == 'admin')
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addSiswa">
                          Tambah Siswa +
                        </button>
                        <hr>
                        <select class="selectpicker" id="sortKelasID" data-size="5">
                          <option value="">Kelas</option>
                          @foreach($kelas as $k)
                          <option value="{{ $k->id }}">{{$k->name}}</option>
                          @endforeach
                        </select>
                        <select class="selectpicker" id="sortThAjaranID" data-size="5">
                          <option value="">Tahun Ajaran</option>
                          @foreach($thAjaran as $t)
                          <option value="{{ $t->id }}">{{$t->tahun}}</option>
                          @endforeach
                        </select>
                        <hr>
                    @endif

                    <table id="siswa" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Tahun Ajaran</th>
                                <th>Status</th>
                                @if(Auth::user()->role == 'admin')
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php $n=1 @endphp
                            @foreach($siswa as $data)
                            <tr>
                                <td>{{$n++}}</td>
                                <td>{{ $data->nis }}</td>
                                <td>{{ $data->name }}</td>
                                <td>{{ $data->kelas_name->name }}</td>
                                <td>{{ $data->th_ajaran_name->tahun }}</td>
                                <td>{{ $data->status }}</td>
                                @if(Auth::user()->role == 'admin')
                                    <td>
                                        <a href="http://localhost:8000/siswa/qr/{{$data->id}}" type="button" class="btn btn-info">
                                          QR Code
                                        </a>
                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editSiswa" onclick="getData('{{ $data->id }}')">
                                          Edit
                                        </button>
                                        <button class="btn btn-danger" onclick="deleteData('{{ $data->id }}')">Delete</button>
                                    </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL ADD -->
    <div class="modal fade" id="addSiswa" tabindex="-1" role="dialog" aria-labelledby="addSiswaCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addSiswaCenterTitle">Form Tambah Data Siswa</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
                <div class="form-group">
                    <label for="nis" class="col-form-label">NIS</label>
                    <input type="text" class="form-control" id="nis" placeholder="Nomor Induk Siswa">
                </div>
                <div class="form-group">
                    <label for="namaSiswa" class="col-form-label">Nama Siswa</label>
                    <input type="text" class="form-control" id="namaSiswa" placeholder="Nama Lengkap Siswa">
                </div>
                <div class="form-group">
                    <label for="kelasID">Kelas</label>
                    <select id="kelasID" class="form-control">
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="thAjaranID">Tahun Ajaran</label>
                    <select id="thAjaranID" class="form-control">
                        @foreach($thAjaran as $th)
                            <option value="{{ $th->id }}">{{ $th->tahun }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal" id="saveAdd">Save</button>
          </div>
        </div>
      </div>
    </div>

    <!-- MODAL EDIT -->
    <div class="modal fade" id="editSiswa" tabindex="-1" role="dialog" aria-labelledby="editSiswaCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editSiswaCenterTitle">Form Edit Data Siswa</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
                <input type="hidden" class="form-control" id="editID">
                <div class="form-group">
                    <label for="editNis" class="col-form-label">NIS</label>
                    <input type="text" class="form-control" id="editNis" placeholder="Nomor Induk Siswa">
                </div>
                <div class="form-group">
                    <label for="editNamaSiswa" class="col-form-label">Nama Siswa</label>
                    <input type="text" class="form-control" id="editNamaSiswa" placeholder="Nama Lengkap Siswa">
                </div>
                <div class="form-group">
                    <label for="editKelasID">Kelas</label>
                    <select id="editKelasID" class="form-control">
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="editThAjaranID">Tahun Ajaran</label>
                    <select id="editThAjaranID" class="form-control">
                        @foreach($thAjaran as $th)
                            <option value="{{ $th->id }}">{{ $th->tahun }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="editStatus">Status</label>
                    <select id="editStatus" class="form-control">
                        <option value="enable">enable</option>
                        <option value="disable">disable</option>
                    </select>
                </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal" id="saveEdit">Save</button>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#siswa').DataTable();
    } );

    function getData(id) {
        $.get('http://localhost:8000/siswa/'+id, function(data) {
            if(data.status == 0){
                alert('Data Not Found');
            } else {
                $('#editNis').val(data.nis);
                $('#editNamaSiswa').val(data.name);
                $('#editKelasID').val(data.kelas_id); 
                $('#editThAjaranID').val(data.th_ajaran_id);
                $('#editID').val(data.id); 
            }
        });
    }

    // TAMBAH DATA Siswa
    $('#saveAdd').click(function(e) {
        e.preventDefault();
        if($('#nis').val() == '' || $('#namaSiswa').val() == '' || $('#kelasID').val() == '' || $('#thAjaranID').val() == '') {
            alert("Data tidak boleh ada yang kosong!");
        } else if (isNaN($('#nis').val())){
            alert("NIS hasil berupa Nomor!");
        } else {
            $.ajax({  
                url: 'http://localhost:8000/siswa',  
                type: 'POST',  
                dataType: 'json',  
                data: {
                    nis: $('#nis').val(),
                    name: $('#namaSiswa').val(),
                    kelas_id: $('#kelasID').val(),
                    th_ajaran_id: $('#thAjaranID').val()
                },  
                success: function (data) {
                    if(data.status == 0) {
                        alert(data.msg);
                    } else {
                        alert('Data berhasil ditambah.');
                        refreshForm();
                        $("#siswa").load(window.location + " #siswa");
                    }
                }
            });
        }
    });

    // EDIT DATA Siswa
    $('#saveEdit').click(function(e) {
        e.preventDefault();

        $.ajax({  
            url: 'http://localhost:8000/siswa/'+$('#editID').val(),  
            type: 'PATCH',  
            dataType: 'json',  
            data: {
                nis: $('#editNis').val(),
                name: $('#editNamaSiswa').val(),
                kelas_id: $('#editKelasID').val(),
                th_ajaran_id: $('#editThAjaranID').val()
            },  
            success: function (data) {
                if(data.status == 0){
                    alert(data.msg);
                }else{
                    alert('Data berhasil diedit.');
                    $("#siswa").load(window.location + " #siswa");
                }
            }
        });
    });

    // DELETE DATA SISWA
    function deleteData(id) {
        let conf = confirm("Apakah anda yakin data ini akan dihapus ?");
        if(conf){
            $.ajax({  
                url: 'http://localhost:8000/siswa/'+id,  
                type: 'DELETE',  
                dataType: 'json', 
                success: function (data) {
                    if(data.status == 0){
                        alert(data.msg);
                    }else{
                        alert('Data berhasil dihapus.');
                        $("#siswa").load(window.location + " #siswa");
                    }
                }
            });
        }
    }

    //REFRESH FORM
    function refreshForm() {
        $('#nis').val('');
        $('#namaSiswa').val('');
        $('#kelasID').val('');
        $('#thAjaranID').val('');
    }
</script>
@endsection
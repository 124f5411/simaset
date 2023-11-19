<div class="modal fade" id="modalEdit"  aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-laporan" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama">Uraian</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                        <input type="hidden" class="form-control" id="table" name="table" required>
                    </div>
                    <div class="alert" role="alert" style="display: none">
                        <p id="massages"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKelompok"  aria-labelledby="modalKelompokLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-kode" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalKelompokLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="objek">Kode Kelompok</label>
                        <input type="text" class="form-control" id="kode" name="kode" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="nama">Uraian</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="alert" role="alert" style="display: none">
                        <p id="massages"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalJenis"  aria-labelledby="modalJenisLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-kode" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalJenisLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kelompok">Kelompok</label>
                        <select class="form-control" id="kelompok" name="kelompok" data-placeholder="Pilih Kelompok">
                            <option></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="objek">Kode Jenis</label>
                        <input type="text" class="form-control" id="kode" name="kode" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="nama">Uraian</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="alert" role="alert" style="display: none">
                        <p id="massages"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalObjek"  aria-labelledby="modalObjekLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-kode" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalObjekLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kelompok">Kelompok</label>
                        <select class="form-control" id="kelompok" name="kelompok" data-placeholder="Pilih Kelompok">
                            <option></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jenis">Jenis</label>
                        <select class="form-control" id="jenis" name="jenis" data-placeholder="Pilih Jenis">
                            <option></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="objek">Kode Objek</label>
                        <input type="text" class="form-control" id="kode" name="kode" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="nama">Uraian</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="alert" role="alert" style="display: none">
                        <p id="massages"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRincian"  aria-labelledby="modalRincianLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-kode" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRincianLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kelompok">Kelompok</label>
                        <select class="form-control" id="kelompok" name="kelompok" data-placeholder="Pilih Kelompok">
                            <option></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jenis">Jenis</label>
                        <select class="form-control" id="jenis" name="jenis" data-placeholder="Pilih Jenis">
                            <option></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="objek">Objek</label>
                        <select class="form-control" id="objek" name="objek" data-placeholder="Pilih Objek">
                            <option></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="objek">Kode Rincian</label>
                        <input type="text" class="form-control" id="kode" name="kode" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="nama">Uraian</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="alert" role="alert" style="display: none">
                        <p id="massages"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSubRincian"  aria-labelledby="modalSubRincianLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-kode" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSubRincianLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kelompok">Kelompok</label>
                        <select class="form-control" id="kelompok" name="kelompok" data-placeholder="Pilih Kelompok">
                            <option></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jenis">Jenis</label>
                        <select class="form-control" id="jenis" name="jenis" data-placeholder="Pilih Jenis">
                            <option></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="objek">Objek</label>
                        <select class="form-control" id="objek" name="objek" data-placeholder="Pilih Objek">
                            <option></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="rincian">Rincian</label>
                        <select class="form-control" id="rincian" name="rincian" data-placeholder="Pilih Rincian">
                            <option></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="objek">Kode Sub Rincian</label>
                        <input type="text" class="form-control" id="kode" name="kode" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="nama">Uraian</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="alert" role="alert" style="display: none">
                        <p id="massages"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="modalKode"  aria-labelledby="modalKodeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-kode" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalKodeLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kelompok">Kelompok</label>
                        <select class="form-control" id="kelompok" name="kelompok" data-placeholder="Pilih Kelompok">
                            <option></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jenis">Jenis</label>
                        <select class="form-control" id="jenis" name="jenis" data-placeholder="Pilih Jenis">
                            <option></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="objek">Objek</label>
                        <select class="form-control" id="objek" name="objek" data-placeholder="Pilih Objek">
                            <option></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="rincian">Rincian</label>
                        <select class="form-control" id="rincian" name="rincian" data-placeholder="Pilih Rincian">
                            <option></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="subrincian">Sub Rincian</label>
                        <select class="form-control" id="subrincian" name="subrincian" data-placeholder="Pilih Sub Rincian">
                            <option></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="objek">Kode Barang</label>
                        <input type="text" class="form-control" id="kode" name="kode" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="nama">Uraian</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="alert" role="alert" style="display: none">
                        <p id="massages"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade" id="modalRincian"  aria-labelledby="modalRincianLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-laporan" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRincianLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
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
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="uraian">Barang</label>
                                <select class="form-control" id="uraian" name="uraian" data-placeholder="Pilih Uraian">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="kode">Kode</label>
                                    <input type="text" class="form-control" id="kode" name="kode" readonly required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jumlah">Jumlah Barang</label>
                                <input type="text" class="form-control" id="jumlah" name="jumlah" required>
                            </div>
                            <div class="form-group">
                                <label for="asal">Asal Usul Dana</label>
                                <input type="text" class="form-control" id="asal" name="asal" required>
                            </div>
                            <div class="form-group">
                                <label for="harga">Harga Rincian</label>
                                <input type="number" class="form-control" id="harga" name="harga" required>
                            </div>
                        </div>
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

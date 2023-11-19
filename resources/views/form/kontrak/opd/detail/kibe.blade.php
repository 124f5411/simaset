<div class="modal fade" id="modalKibE"  aria-labelledby="modalKibELabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-kibC" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalKibELabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="kode">Kode</label>
                                    <input type="text" class="form-control" id="kode" name="kode" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nm_aset">Nama Aset</label>
                                <input type="text" class="form-control" id="nm_aset" name="nm_aset" required>
                            </div>
                            <div class="form-group">
                                <label for="asal_daerah">Asal Daerah</label>
                                <input type="text" class="form-control" id="asal_daerah" name="asal_daerah">
                            </div>
                            <div class="form-group">
                                <label for="pencipta">Pencipta</label>
                                <input type="text" class="form-control" id="pencipta" name="pencipta">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="jenis">Jenis Aset</label>
                                <input type="text" class="form-control" id="jenis" name="jenis" required>
                            </div>
                            <div class="form-group">
                                <label for="ukuran">Ukuran</label>
                                <input type="text" class="form-control" id="ukuran" name="ukuran">
                            </div>
                            <div class="form-group">
                                <label for="jumlah">Jumlah</label>
                                <input type="text" class="form-control" id="jumlah" name="jumlah" required>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
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

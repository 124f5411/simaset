<div class="modal fade" id="modalKibC"  aria-labelledby="modalKibCLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-kibC" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalKibCLabel">Modal title</h5>
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
                                <label for="tgl_dokumen">Tanggal Dokumen</label>
                                <input type="text" class="form-control" id="tgl_dokumen" name="tgl_dokumen">
                            </div>
                            <div class="form-group">
                                <label for="no_dokumen">Nomor Dokumen</label>
                                <input type="text" class="form-control" id="no_dokumen" name="no_dokumen">
                            </div>
                            <div class="form-group">
                                <label for="luas_lantai">Luas Lantai</label>
                                <input type="text" class="form-control" id="luas_lantai" name="luas_lantai" required>
                            </div>
                            <div class="form-group">
                                <label for="luas_tanah">Luas Tanah</label>
                                <input type="text" class="form-control" id="luas_tanah" name="luas_tanah" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="tingkat">Bertingkat</label>
                                <select class="form-control" id="tingkat" name="tingkat" required>
                                    <option value="">Pilih Tingak</option>
                                    <option value="1">Ya</option>
                                    <option value="0">Tidak</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="beton">Beton</label>
                                <select class="form-control" id="beton" name="beton" required>
                                    <option value="">Pilih Beton</option>
                                    <option value="1">Ya</option>
                                    <option value="0">Tidak</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="id_status_tanah">Status Tanah</label>
                                <select class="form-control" id="id_status_tanah" name="id_status_tanah" required>
                                    <option value="">Pilih Hak Tanah</option>
                                    @foreach ($drops['status_tanah'] as $value)
                                        <option value="{{ $value->id }}">{{ $value->status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="kode_tanah">Kode Tanah</label>
                                <input type="text" class="form-control" id="kode_tanah" name="kode_tanah" required>
                            </div>
                            <div class="form-group">
                                <label for="kondisi">Kondisi</label>
                                <input type="text" class="form-control" id="kondisi" name="kondisi">
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

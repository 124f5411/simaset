<div class="modal fade" id="modalKibA"  aria-labelledby="modalKibALabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-kibA" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalKibALabel">Modal title</h5>
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
                                <label for="tgl_sertifikat">Tanggal Sertifikat</label>
                                <input type="text" class="form-control" id="tgl_sertifikat" name="tgl_sertifikat">
                            </div>
                            <div class="form-group">
                                <label for="no_sertifikat">Nomor Sertifikat</label>
                                <input type="text" class="form-control" id="no_sertifikat" name="no_sertifikat">
                            </div>
                            <div class="form-group">
                                <label for="luas_tanah">Luas Tanah</label>
                                <input type="text" class="form-control" id="luas_tanah" name="luas_tanah" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="id_hak">Hak Tanah</label>
                                <select class="form-control" id="id_hak" name="id_hak" required>
                                    <option value="">Pilih Hak Tanah</option>
                                    @foreach ($drops['hak'] as $value)
                                        <option value="{{ $value->id }}">{{ $value->hak }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="penggunaan">Penggunaan</label>
                                <input type="text" class="form-control" id="penggunaan" name="penggunaan" required>
                            </div>
                            <div class="form-group">
                                <label for="bahan">Tahun</label>
                                <input type="number" class="form-control" id="tahun" name="tahun" maxlength="4" minlength="4" max="{{ date('Y')+1 }}" required>
                            </div>
                            <div class="form-group">
                                <label for="alamat">Lokasi / Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
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

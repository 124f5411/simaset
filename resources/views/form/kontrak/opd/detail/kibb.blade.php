<div class="modal fade" id="modalKibB"  aria-labelledby="modalKibBLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-kibB" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalKibBLabel">Modal title</h5>
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
                                <label for="merek">Merek</label>
                                <input type="text" class="form-control" id="merek" name="merek" required>
                            </div>
                            <div class="form-group">
                                <label for="pabrik">Pabrik</label>
                                <input type="text" class="form-control" id="pabrik" name="pabrik" >
                            </div>
                            <div class="form-group">
                                <label for="no_bpkb">Nomor BPKB</label>
                                <input type="text" class="form-control" id="no_bpkb" name="no_bpkb" >
                            </div>
                            <div class="form-group">
                                <label for="no_rangka">Nomor Rangka</label>
                                <input type="text" class="form-control" id="no_rangka" name="no_rangka" >
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="no_mesin">Nomor Mesin</label>
                                <input type="text" class="form-control" id="no_mesin" name="no_mesin">
                            </div>
                            <div class="form-group">
                                <label for="nopol">Nomor Polisi</label>
                                <input type="text" class="form-control" id="nopol" name="nopol" >
                            </div>
                            <div class="form-group">
                                <label for="spesifikasi">Ukuran / CC</label>
                                <input type="text" class="form-control" id="spesifikasi" name="spesifikasi" required>
                            </div>
                            <div class="form-group">
                                <label for="bahan">Bahan</label>
                                <input type="text" class="form-control" id="bahan" name="bahan" required>
                            </div>
                            <div class="form-group">
                                <label for="bahan">Tahun</label>
                                <input type="number" class="form-control" id="tahun" name="tahun" maxlength="4" minlength="4" max="{{ date('Y')+1 }}" required>
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

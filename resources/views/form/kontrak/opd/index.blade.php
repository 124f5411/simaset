<div class="modal fade" id="modalKontrak"  aria-labelledby="modalKontrakLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-laporan" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalKontrakLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="no_kontrak">Nomor Kontrak</label>
                        <input type="text" class="form-control" id="no_kontrak" name="no_kontrak" required>
                    </div>
                    <div class="form-group">
                        <label for="nm_kontrak">Judul Kontrak</label>
                        <input type="text" class="form-control" id="nm_kontrak" name="nm_kontrak" required>
                    </div>
                    <div class="form-group">
                        <label for="tahun">Tahun</label>
                        <input type="text" class="form-control" id="tahun" name="tahun" required>
                    </div>
                    <div class="form-group">
                        <label for="t_kontrak">Tanggal Kontrak</label>
                        <input type="text" class="form-control" id="t_kontrak" name="t_kontrak" required>
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

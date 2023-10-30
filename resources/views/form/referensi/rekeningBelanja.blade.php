<div class="modal fade" id="modalRekening"  aria-labelledby="modalRekeningabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-laporan" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRekeningabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kode_akun">Kode Akun</label>
                        <input type="text" class="form-control" id="kode_akun" name="kode_akun" required>
                    </div>
                    <div class="form-group">
                        <label for="nm_akun">Nama Akun</label>
                        <input type="text" class="form-control" id="nm_akun" name="nm_akun" required>
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

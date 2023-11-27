<div class="modal fade" id="modalPenyedia"  aria-labelledby="modalPenyediaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-laporan" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPenyediaLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nm_penyedia">Nama Penyedia</label>
                        <input type="text" class="form-control" id="nm_penyedia" name="nm_penyedia" required>
                    </div>
                    <div class="form-group">
                        <label for="pimpinan">Nama Pimpinan</label>
                        <input type="text" class="form-control" id="pimpinan" name="pimpinan" required>
                    </div>
                    <div class="form-group">
                        <label for="telp">Telpon / HP</label>
                        <input type="text" class="form-control" id="telp" name="telp" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
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

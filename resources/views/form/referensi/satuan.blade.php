<div class="modal fade" id="modalSatuan"  aria-labelledby="modalSatuanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-laporan" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSatuanLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nm_satuan">Satuan</label>
                        <input type="text" class="form-control" id="nm_satuan" name="nm_satuan" required>
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

<div class="modal fade" id="modalRekeningImport"  aria-labelledby="modalRekeningImportLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-import" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRekeningImportLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="dok_rekening">File excel</label>
                        <input type="file" class="form-control" id="dok_rekening" name="dok_rekening" required>
                    </div>
                    <div class="alert" role="alert" style="display: none">
                        <p id="massages-imp"></p>
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

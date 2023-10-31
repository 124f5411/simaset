<div class="modal fade" id="modalAsb"  aria-labelledby="modalAsbLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-laporan" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAsbLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="spesifikasi">Tahun</label>
                        <input type="year" class="form-control" id="tahun" name="tahun" required>
                    </div>
                    <div class="form-group">
                        <label for="induk_perubahan">Jenis Usulan</label>
                        <select class="form-control" id="induk_perubahan" name="induk_perubahan">
                            <option value="">Pilih Jenis</option>
                            <option value="1">Induk</option>
                            <option value="2">Perubahan</option>
                        </select>
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

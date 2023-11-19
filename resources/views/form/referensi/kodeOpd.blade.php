<div class="modal fade" id="modalOpd"  aria-labelledby="modalOpdLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-kode" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalOpdLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="urusan">Urusan / Unsur</label>
                        <select class="form-control" id="urusan" name="urusan" required data-placeholder="Pilih Urusan / Unsur">
                            <option></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="bidang">Bidang</label>
                        <select class="form-control" id="bidang" name="bidang" required data-placeholder="Pilih Bidang">
                            <option></option>
                        </select>
                    </div>
                    <div class="form-group insuburusan">
                        <label for="suburusan">Urusan / Unsur</label>
                        <select class="form-control" id="suburusan" name="suburusan" data-placeholder="Pilih Urusan / Unsur">
                            <option></option>
                        </select>
                    </div>
                    <div class="form-group insubbidang">
                        <label for="subbidang">Bidang</label>
                        <select class="form-control" id="subbidang" name="subbidang" data-placeholder="Pilih Bidang">
                            <option></option>
                        </select>
                    </div>
                    <div class="form-group insubsuburusan">
                        <label for="subsuburusan">Urusan / Unsur</label>
                        <select class="form-control" id="subsuburusan" name="subsuburusan" data-placeholder="Pilih Urusan / Unsur">
                            <option></option>
                        </select>
                    </div>
                    <div class="form-group insubsubbidang">
                        <label for="subsubbidang">Bidang</label>
                        <select class="form-control" id="subsubbidang" name="subsubbidang" data-placeholder="Pilih Bidang">
                            <option></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kd_opd">Kode OPD</label>
                        <input type="text" class="form-control" id="kd_opd" name="kd_opd" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="nm_opd">Nama OPD</label>
                        <input type="text" class="form-control" id="nm_opd" name="nm_opd" required>
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

<div class="modal fade" id="modalBiro"  aria-labelledby="modalBiroLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-kode" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBiroLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kd_opd">Kode OPD</label>
                        <input type="text" class="form-control" id="kd_opd" name="kd_opd" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="nm_opd">Nama OPD</label>
                        <input type="text" class="form-control" id="nm_opd" name="nm_opd" required>
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

<div class="modal fade" id="modalRekening"  aria-labelledby="modalRekeningLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-laporan" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRekeningLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="update">
                            <label for="id_rekenings">Rekening Belanja</label>
                            <select class="js-example-basic-multiple js-states form-control id_rekenings" id="id_rekenings[]" name="id_rekenings[]" multiple="multiple">
                                <option>Cari Akun Belanja</option>
                                    @foreach ($drops['rekening'] as $value)
                                        <option value="{{ $value->id }}">{{ $value->nm_akun }}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="alert" role="alert" style="display: none">
                        <p id="rek-massages"></p>
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

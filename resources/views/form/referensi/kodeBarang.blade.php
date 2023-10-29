<div class="modal fade" id="modalKode"  aria-labelledby="modalKodeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-laporan" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalKodeLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kode_barang">Kode Barang</label>
                        <input type="text" class="form-control" id="kode_barang" name="kode_barang" required>
                    </div>
                    <div class="form-group">
                        <label for="uraian">Uraian</label>
                        <input type="text" class="form-control" id="uraian" name="uraian" required>
                    </div>
                    <div class="form-group">
                        <label for="kib">KIB</label>
                        <select class="form-control" id="kib" name="kib">
                            <option value="">Pilih KIB</option>
                            @foreach ($drops['master'] as $value)
                                <option value="{{ $value->kib }}">KIB {{ $value->kib }} {{ $value->type }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kelompok">Kelompok</label>
                        <select class="form-control" id="kelompok" name="kelompok">
                            <option value="">Pilih Kelompok</option>
                            @foreach ($drops['kelompok'] as $value)
                                <option value="{{ $value->id }}">{{ $value->kelompok }}</option>
                            @endforeach
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

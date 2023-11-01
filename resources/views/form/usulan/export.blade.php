<div class="modal fade" id="modalExport"  aria-labelledby="modalExportLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-import" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalExportLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <select class="form-control" id="tahun" name="tahun">
                            <option value="">Pilih Tahun</option>
                                @foreach ($drops['tahun'] as $value)
                                    <option value="{{ $value->tahun }}">{{ $value->tahun }}</option>
                                @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select class="form-control" id="jenis" name="jenis">
                            <option value="">Pilih Jenis Usulan</option>
                                @foreach ($drops['jenis'] as $value)
                                    <option value="{{ $value->induk_perubahan }}">{{ ($value->induk_perubahan == 1) ? "Induk" : "Perubahan" }}</option>
                                @endforeach
                        </select>
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

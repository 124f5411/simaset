<div class="modal fade" id="modalInstansi"  aria-labelledby="modalInstansiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-laporan" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalInstansiLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="opd">Nama Instansi</label>
                        <input type="text" class="form-control" id="opd" name="opd" required autofocus="autofocus">
                    </div>
                    <div class="form-group">
                        <label for="parent">Parent Instansi</label>
                        <select class="form-control" id="parent" name="parent">
                          <option value="">Pilih Instansi</option>
                          @foreach ($drops as $value)
                            <option value="{{ $value->id }}">{{ $value->opd }}</option>
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

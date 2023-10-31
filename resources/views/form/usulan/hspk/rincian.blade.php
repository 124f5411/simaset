<div class="modal fade" id="modalHspk"  aria-labelledby="modalHspkLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-laporan" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalHspkLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="id_kode">Barang</label>
                        <select class="form-control" id="id_kode" name="id_kode">
                            <option value="">Cari Barang</option>
                                @foreach ($drops['kode_barang'] as $value)
                                    <option value="{{ $value->id }}">{{ $value->uraian }}</option>
                                @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="spesifikasi">Spesifikasi</label>
                        <input type="text" class="form-control" id="spesifikasi" name="spesifikasi" required>
                    </div>
                    <div class="form-group">
                        <label for="id_satuan">Satuan</label>
                        <select class="form-control" id="id_satuan" name="id_satuan">
                            <option value="">Cari Satuan</option>
                                @foreach ($drops['satuan'] as $value)
                                    <option value="{{ $value->id }}">{{ $value->nm_satuan }}</option>
                                @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <input type="text" class="form-control" id="harga" name="harga" required>
                    </div>
                    <div class="form-group">
                        <label for="id_rekening">Rekening Belanja</label>
                        <select class="form-control" id="id_rekening" name="id_rekening">
                            <option value="">Cari Akun Belanja</option>
                                @foreach ($drops['rekening'] as $value)
                                    <option value="{{ $value->id }}">{{ $value->nm_akun }}</option>
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

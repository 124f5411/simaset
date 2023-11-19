<div class="modal fade" id="modalSsh"  aria-labelledby="modalSshLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-laporan" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSshLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="id_kode">Barang</label>
                                <select class="form-control" id="id_kode" name="id_kode">
                                    <option value="">Cari Barang</option>
                                        @foreach ($drops['kode_barang'] as $value)
                                            <option value="{{ $value->id }}">{{ $value->kode_barang }} {{ $value->uraian }}</option>
                                        @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="uraian">Uraian</label>
                                <input type="text" class="form-control" id="uraian" name="uraian" required>
                            </div>
                            <div class="form-group">
                                <label for="spesifikasi">Spesifikasi</label>
                                <input type="text" class="form-control" id="spesifikasi" name="spesifikasi" required>
                            </div>
                            <div class="form-group">
                                <label for="id_satuan">Satuan</label>
                                <select class="form-control" id="id_satuan" name="id_satuan">
                                    <option>Cari Satuan</option>
                                        @foreach ($drops['satuan'] as $value)
                                            <option value="{{ $value->id }}">{{ $value->nm_satuan }}</option>
                                        @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="harga">Harga</label>
                                <input type="number" class="form-control" id="harga" name="harga" required>
                            </div>
                            <div class="form-group">
                                <label for="tkdn">T K D N</label>
                                <input type="text" class="form-control" id="tkdn" name="tkdn" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group rek_1">
                                <label for="rek_1">Rekening Belanja 1</label>
                                <select class="form-control" id="rek_1" name="rek_1" data-placeholder="Cari Kode Rekening">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group rek_2">
                                <label for="rek_2">Rekening Belanja 2</label>
                                <select class="form-control" id="rek_2" name="rek_2" data-placeholder="Cari Kode Rekening">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group rek_3">
                                <label for="rek_3">Rekening Belanja 3</label>
                                <select class="form-control" id="rek_3" name="rek_3" data-placeholder="Cari Kode Rekening">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group rek_4">
                                <label for="rek_4">Rekening Belanja 4</label>
                                <select class="form-control" id="rek_4" name="rek_4" data-placeholder="Cari Kode Rekening">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group rek_5">
                                <label for="rek_5">Rekening Belanja 5</label>
                                <select class="form-control" id="rek_5" name="rek_5" data-placeholder="Cari Kode Rekening">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group rek_6">
                                <label for="rek_6">Rekening Belanja 6</label>
                                <select class="form-control" id="rek_6" name="rek_6" data-placeholder="Cari Kode Rekening">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group rek_7">
                                <label for="rek_7">Rekening Belanja 7</label>
                                <select class="form-control" id="rek_7" name="rek_7" data-placeholder="Cari Kode Rekening">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group rek_8">
                                <label for="rek_8">Rekening Belanja 8</label>
                                <select class="form-control" id="rek_8" name="rek_8" data-placeholder="Cari Kode Rekening">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group rek_9">
                                <label for="rek_9">Rekening Belanja 9</label>
                                <select class="form-control" id="rek_9" name="rek_9" data-placeholder="Cari Kode Rekening">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group rek_10">
                                <label for="rek_10">Rekening Belanja 10</label>
                                <select class="form-control" id="rek_10" name="rek_10" data-placeholder="Cari Kode Rekening">
                                    <option></option>
                                </select>
                            </div>
                        </div>
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

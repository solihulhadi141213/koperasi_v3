<div class="row mb-3">
    <div class="col-md-4">
        <label for="sesi_shu">Nama Sesi Bagi Hasil</label>
    </div>
    <div class="col-md-8">
        <input type="text" name="sesi_shu" id="sesi_shu" class="form-control" placeholder="Contoh: SHU Tahun 2022">
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-4">
        <label for="periode_hitung1">Periode Awal Perhitungan</label>
    </div>
    <div class="col-md-8">
        <input type="date" name="periode_hitung1" id="periode_hitung1" class="form-control">
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-4">
        <label for="periode_hitung2">Periode Akhir Perhitungan</label>
    </div>
    <div class="col-md-8">
        <input type="date" name="periode_hitung2" id="periode_hitung2" class="form-control">
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-4">
        <label for="persen_usaha">Persen Sisa Usaha (%)</label>
    </div>
    <div class="col-md-8">
        <input type="number" min="0" max="100" name="persen_usaha" id="persen_usaha" class="form-control" placeholder="[0-100]">
        <small class="credit">
            <code class="text text-grayish">Nilai persentase (%) sisa usaha yang disisihkan untuk SHU</code>
        </small>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-4">
        <label for="persen_modal">Persen Sisa Modal (%)</label>
    </div>
    <div class="col-md-8">
        <input type="number" min="0" max="100" name="persen_modal" id="persen_modal" class="form-control" placeholder="[0-100]">
        <small class="credit">
            <code class="text text-grayish">Nilai persentase (%) sisa modal yang disisihkan untuk SHU</code>
        </small>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-4">
        <label for="persen_pinjaman">Persen Sisa Modal (%)</label>
    </div>
    <div class="col-md-8">
        <input type="number" min="0" max="100" name="persen_pinjaman" id="persen_pinjaman" class="form-control" placeholder="[0-100]">
        <small class="credit">
            <code class="text text-grayish">Nilai persentase (%) sisa jasa pinjaman yang disisihkan untuk SHU</code>
        </small>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-4">
        <label for="alokasi_nyata">Alokasi Sisa Usaha (Rp)</label>
    </div>
    <div class="col-md-8">
        <input type="text" name="alokasi_nyata" id="alokasi_nyata" class="form-control format_uang">
        <small class="credit">
            <code class="text text-grayish">Nilai nominal kas usaha yang disisihkan untuk SHU</code>
        </small>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-4">
        <label for="status">Status Pembagian SHU</label>
    </div>
    <div class="col-md-8">
        <select name="status" id="status" class="form-control">
            <option value="">Pilih</option>
            <option value="Pending">Masih Pending</option>
            <option value="Realisasi">Sudah Realisasi</option>
        </select>
    </div>
</div>
<script>
    $('#alokasi_nyata').on('keypress', function(e) {
        // Hanya mengizinkan angka (0-9)
        if (e.which < 48 || e.which > 57) {
            e.preventDefault();
        }
    });
</script>

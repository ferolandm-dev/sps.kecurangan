@extends('layouts.app', [
'namePage' => 'Tambah Kecurangan',
'class' => 'sidebar-mini',
'activePage' => 'kecurangan',
])

@section('content')
<div class="panel-header panel-header-sm panel-header-sps"></div>

<div class="content">
    <div class="row">
        <div class="col-md-12">

            {{-- ALERT SUCCESS --}}
            @if(session('success'))
            <div class="alert alert-success alert-with-icon alert-dismissible fade show" data-notify="container"
                role="alert" style="
                    background: rgba(41,177,74,0.2);
                    border: 1px solid #29b14a;
                    color: #155724;
                    border-radius: 12px;
                ">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color:#155724;">
                    <i class="now-ui-icons ui-1_simple-remove"></i>
                </button>
                <span data-notify="icon" class="now-ui-icons ui-1_bell-53 mr-2"></span>
                <span data-notify="message">{{ session('success') }}</span>
            </div>
            @endif

            {{-- ALERT ERROR --}}
            @if(session('error'))
            <div class="alert alert-danger alert-with-icon alert-dismissible fade show" data-notify="container"
                role="alert" style="
                    background: rgba(231,76,60,0.2);
                    border: 1px solid #e74c3c;
                    color: #721c24;
                    border-radius: 12px;
                ">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color:#721c24;">
                    <i class="now-ui-icons ui-1_simple-remove"></i>
                </button>
                <span data-notify="icon" class="now-ui-icons ui-1_bell-53"></span>
                <span data-notify="message">{{ session('error') }}</span>
            </div>
            @endif

            <div class="card" style="border-radius: 20px;">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap"
                    style="border-radius: 20px 20px 0 0; background: rgba(255,255,255,0.5);">
                    <h4 class="card-title mb-0 text-dark mt-4">{{ __('Tambah Data Kecurangan') }}</h4>
                </div>

                <div class="card-body" style="background: rgba(255,255,255,0.7); border-radius: 0 0 20px 20px;">
                    <form action="{{ route('kecurangan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- ===================== DETAIL SALES ===================== --}}
                        <h6 class="heading-small text-success mb-3" style="font-weight:600;">Detail Sales</h6>

                        <div class="form-group">
                            <label class="text-dark font-weight-bold">ID Sales</label>
                            <select name="id_sales" id="id_sales" class="form-control select2" required
                                style="border-radius:12px;">
                                <option value="">-- Pilih Sales --</option>
                                @foreach($sales as $s)
                                <option value="{{ $s->ID_SALESMAN }}">
                                    {{ $s->ID_SALESMAN }} - {{ $s->NAMA_SALESMAN }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label class="text-dark font-weight-bold">Nama Sales</label>
                                    <input type="text" id="nama_sales" name="nama_sales" class="form-control" readonly
                                        style="border-radius:12px;">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label class="text-dark font-weight-bold">Distributor</label>
                                    <input type="text" id="distributor" name="distributor" class="form-control" readonly
                                        style="border-radius:12px;">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4" style="border-color:#29b14a;">

                        {{-- ===================== DETAIL ASSISTANT MANAGER ===================== --}}
                        <h6 class="heading-small text-success mb-3" style="font-weight:600;">Detail Asisten Manager</h6>

                        <div class="form-group">
                            <label class="text-dark font-weight-bold">ID ASS</label>
                            <select name="id_asisten_manager" id="id_asisten_manager" class="form-control select2"
                                style="border-radius:12px;">
                                <option value="">-- Pilih ASS --</option>
                            </select>

                        </div>

                        <div class="col-md-3 pl-0">
                            <div class="form-group has-label">
                                <label class="text-dark font-weight-bold">Nama ASS</label>
                                <input type="text" id="nama_asisten_manager" name="nama_asisten_manager"
                                    class="form-control" readonly style="border-radius:12px;">
                            </div>
                        </div>

                        <hr class="my-4" style="border-color:#29b14a;">

                        {{-- ===================== DETAIL KEJADIAN ===================== --}}
                        <h6 class="heading-small text-success mb-3" style="font-weight:600;">Detail Kejadian</h6>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="text-dark font-weight-bold">Jenis Sanksi</label>
                                    <select name="jenis_sanksi" id="jenis_sanksi" class="form-control select2" required>
                                        <option value="">-- Pilih Jenis --</option>
                                        @foreach($jenisSanksi as $jenis)
                                        <option value="{{ $jenis }}">{{ $jenis }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-dark font-weight-bold">Deskripsi Sanksi</label>
                                    <select name="deskripsi_sanksi" id="deskripsi_sanksi" class="form-control select2"
                                        required></select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="text-dark font-weight-bold">Nilai Sanksi</label>
                                <input type="text" id="nilai_sanksi" name="nilai_sanksi" class="form-control" readonly
                                    style="border-radius:12px;">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <label class="text-dark font-weight-bold">Toko</label>
                                <input type="text" name="toko" class="form-control" required
                                    style="border-radius:12px;">
                            </div>

                            <div class="col-md-3">
                                <label class="text-dark font-weight-bold">Kunjungan</label>
                                <input type="text" name="kunjungan" class="form-control" required
                                    style="border-radius:12px;">
                            </div>

                            <div class="col-md-3">
                                <label class="text-dark font-weight-bold">Tanggal</label>
                                <input type="text" name="tanggal" id="tanggal" class="form-control" required
                                    placeholder="dd/mm/yyyy" style="border-radius:12px;">
                            </div>

                            <div class="col-md-3">
                                <label class="text-dark font-weight-bold">Kuartal</label>
                                <input type="text" name="kuartal" id="kuartal" class="form-control" readonly
                                    style="border-radius:12px;">
                            </div>
                        </div>

                        <div class="form-group has-label">
                            <label class="text-dark font-weight-bold">{{ __('Keterangan') }}</label>
                            <textarea name="keterangan" class="form-control"
                                style="border-radius:12px; width:40%; height:100px; padding:10px 14px; border:1px solid #E3E3E3;"></textarea>
                        </div>


                        <hr class="my-4">

                        {{-- ===================== BAGIAN FOTO ===================== --}}
                        <h6 class="heading-small text-success mb-3" style="font-weight:600;">Bukti Kecurangan (Foto)
                        </h6>

                        <div class="form-group has-label">

                            {{-- Label Upload Foto --}}
                            <label class="text-dark font-weight-bold d-block mb-2">
                                {{ __('Upload Foto (Maksimal total 5 foto)') }}
                            </label>

                            {{-- Tombol pilih foto --}}
                            <button type="button" id="btn-upload" class="btn btn-outline-success btn-round mb-3"
                                style="border-radius:12px;padding:8px 16px;">
                                <i class="now-ui-icons ui-1_simple-add"></i> Pilih Foto
                            </button>

                            {{-- Input file disembunyikan --}}
                            <input type="file" name="bukti[]" id="bukti" accept=".jpg,.jpeg,.png" multiple hidden>

                            {{-- Container preview foto baru --}}
                            <div id="preview-container"
                                class="mt-2 d-flex flex-wrap gap-2 justify-content-start align-items-start"></div>

                            <small class="form-text text-muted mt-2">
                                Format diperbolehkan: JPG, JPEG, PNG — maksimal 5 foto.
                            </small>

                        </div>

                        <div class="text-right mt-3">
                            <a href="{{ route('kecurangan.index') }}" class="btn btn-secondary btn-round">Batal</a>
                            <button type="submit" class="btn btn-success btn-round">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ===================== MODAL PREVIEW FOTO ===================== --}}
<div class="modal fade" id="modalPreview" tabindex="-1" role="dialog" aria-hidden="true" style="margin-top:-10px;">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:1000px;">
        <div class="modal-content border-0" style="background:rgba(255,255,255,0.97);
            border-radius:15px;
            box-shadow:0 4px 25px rgba(0,0,0,0.3);
            overflow:hidden;
            position:relative;">

            {{-- Header --}}
            <div class="modal-header d-flex justify-content-between align-items-center" style="border-bottom:none;">
                <h5 class="modal-title text-success" style="font-weight:600;">
                    <i class="now-ui-icons"></i> Bukti Kecurangan
                </h5>
            </div>

            {{-- Isi Modal --}}
            <div class="modal-body d-flex justify-content-center align-items-center"
                style="height:75vh; overflow:hidden; position:relative;">
                <img id="modalImage" src="" alt="Preview" style="max-width:90%; max-height:90%; object-fit:contain;
                    border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.2);
                    transition:0.3s;">
                <button type="button" id="modalPrev" class="btn btn-link" style="
                    position:absolute;left:0;top:0;height:100%;width:120px;
                    display:flex;align-items:center;justify-content:flex-start;
                    padding-left:25px;font-size:42px;color:#333;opacity:0.7;">
                    ‹
                </button>
                <button type="button" id="modalNext" class="btn btn-link" style="
                    position:absolute;right:0;top:0;height:100%;width:120px;
                    display:flex;align-items:center;justify-content:flex-end;
                    padding-right:25px;font-size:42px;color:#333;opacity:0.7;">
                    ›
                </button>
            </div>

            {{-- Footer --}}
            <div class="modal-footer" style="border-top:none;justify-content:center;">
                <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
input:invalid,
textarea:invalid,
select:invalid {
    box-shadow: none !important;
    border-color: #ced4da !important;
}

input:focus,
textarea:focus,
select:focus {
    border-color: #4caf50 !important;
    /* hijau atau sesuai tema */
}

body,
.wrapper,
.main-panel {
    background: linear-gradient(140deg, #29b14a 0%, #c7c500 50%, #dbd300 92%) !important;
    background-attachment: fixed !important;
    /* supaya smooth */
}


.panel-header-sps {
    background: transparent !important;
    box-shadow: none !important;
}


.content {
    background: transparent !important;
}

/* ========================================
   NAVBAR MATCHING — SAME GRADIENT AS HEADER
========================================= */

.navbar-soft {
    background: linear-gradient(90deg, #29b14a 0%, #dbd300 85%) !important;
    border: none !important;
    box-shadow: none !important;

    /* Tinggi navbar sesuai permintaan */
    height: 95px !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;

    display: flex !important;
    align-items: center !important;

    border-bottom-left-radius: 20px;
    border-bottom-right-radius: 20px;
}

/* Brand */
.navbar-soft .navbar-brand {
    color: #ffffff !important;
    font-size: 22px !important;
    font-weight: 700;
}

/* Icons */
.navbar-soft .nav-link i {
    color: #ffffff !important;
    font-size: 22px;
    transition: .2s ease;
}

.navbar-soft .nav-link:hover i {
    color: #333 !important;
}

.navbar-soft {
    transition: none !important;
    /* matikan transisi container */
}

.navbar-soft .nav-link i,
.navbar-soft .navbar-brand {
    transition: color .25s ease, transform .25s ease !important;
    /* biarkan hover tetap smooth */
}

/* === BACKDROP AKTIF DAN TIDAK TRANSPARAN === */
.modal-backdrop.show {
    opacity: 1 !important;
    background: rgba(0, 0, 0, 0.7) !important;
    backdrop-filter: blur(2px);
}

/* Tombol navigasi modal tetap di atas */
#modalCloseBtn,
#modalPrev,
#modalNext {
    z-index: 2102 !important;
    pointer-events: auto !important;
}

/* Kunci scroll pada body dan modal */
body.modal-open {
    overflow: hidden !important;
}

.modal {
    overflow: hidden !important;
}

/* ===========================================================
   GLOBAL SOFT UI BUTTON STYLE
=========================================================== */
.btn {
    border: none !important;
    border-radius: 12px !important;
    font-weight: 600 !important;
    padding: 8px 18px !important;
    transition: all 0.25s ease-in-out !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15) !important;
}

/* SUCCESS BUTTON (Hijau) */
.btn-success {
    background: linear-gradient(135deg, #29b14a, #34d058) !important;
    color: #fff !important;
}

.btn-success:hover {
    background: linear-gradient(135deg, #25a344, #2fc655) !important;
}

/* DANGER BUTTON (Merah) */
.btn-danger {
    background: linear-gradient(135deg, #e74c3c, #ff6b5c) !important;
    color: white !important;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #d84333, #fa5f50) !important;
}

/* SECONDARY BUTTON (Abu) */
.btn-secondary {
    background: linear-gradient(135deg, #bfc2c7, #d6d8db) !important;
    color: #333 !important;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #b0b3b7, #c9cbce) !important;
}

/* WARNING BUTTON (Kuning lembut) */
.btn-warning {
    background: linear-gradient(135deg, #eee733, #faf26b) !important;
    color: #333 !important;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #e2db2e, #f0eb63) !important;
}

/* ROUND STYLE */
.btn-round {
    border-radius: 30px !important;
}

/* ICON ALIGNMENT FIX */
.btn i {
    font-size: 15px;
    margin-right: 6px;
}

/* DISABLED BUTTON STYLE */
.btn:disabled {
    opacity: 0.6 !important;
    cursor: not-allowed !important;
    transform: none !important;
    box-shadow: none !important;
}

/* Pastikan ditempatkan terakhir agar menimpa Bootstrap */
#modalPrev,
#modalNext {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    outline: none !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;
    -webkit-tap-highlight-color: transparent !important;
    color: #333 !important;
    /* sesuaikan warna */
    text-decoration: none !important;
}

/* Hilangkan efek hover / fokus / aktif sepenuhnya */
#modalPrev:hover,
#modalNext:hover,
#modalPrev:focus,
#modalNext:focus,
#modalPrev:active,
#modalNext:active,
#modalPrev:focus-visible,
#modalNext:focus-visible {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    outline: none !important;
    opacity: 0.6 !important;
    /* atur sesuai kebutuhan, atau 1 */
    transform: none !important;
}

/* Khusus untuk tombol bootstrap .btn-link yang mungkin menambahkan inner focus di Firefox */
#modalPrev::-moz-focus-inner,
#modalNext::-moz-focus-inner {
    border: 0 !important;
    padding: 0 !important;
}

/* Jika masih muncul garis biru di Chrome pada focus, override ring color */
#modalPrev:focus,
#modalNext:focus {
    box-shadow: 0 0 0 0 transparent !important;
}

/* OPTIONAL: jika tetap ada style dari .btn-link atau .btn, paksa prioritas lebih tinggi */
button#modalPrev.btn,
button#modalNext.btn {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    outline: none !important;
}

/* ============================================================
   TOMBOL HAPUS FOTO (X) — SUPER RAPIH & PRESISI
=========================================================== */

#preview-container .btn-remove,
.existing-photo .btn-delete-existing {
    position: absolute;
    top: -9px;
    right: -9px;

    width: 28px;
    height: 28px;

    border-radius: 50% !important;
    background: #e74c3c !important;
    color: #fff !important;

    border: none !important;
    outline: none !important;
    padding: 0 !important;

    display: flex !important;
    align-items: center !important;
    justify-content: center !important;

    font-size: 18px !important;
    font-weight: bold !important;
    line-height: 0 !important;

    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25) !important;
    transition: 0.18s ease-in-out;
    z-index: 99;
}

/* Hover lebih smooth */
#preview-container .btn-remove:hover,
.existing-photo .btn-delete-existing:hover {
    background: #c0392b !important;
    transform: scale(1.10);
}

/* Active klik */
#preview-container .btn-remove:active,
.existing-photo .btn-delete-existing:active {
    transform: scale(0.92);
}

/* Hilangkan efek fokus biru */
#preview-container .btn-remove:focus,
.existing-photo .btn-delete-existing:focus {
    outline: none !important;
    box-shadow: none !important;
}
</style>
@endpush
@push('js')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<link rel="stylesheet" href="{{ asset('assets/css/sidebar-fix.css') }}">
<script src="{{ asset('assets/js/sidebar-fix.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/css/ui-lock.css') }}">
<script src="{{ asset('assets/js/ui-lock.js') }}"></script>
<script>
$(document).ready(function() {
    // === Select2 Setup ===
    $('#id_sales, #id_asisten_manager, #jenis_sanksi, #deskripsi_sanksi').select2({
        placeholder: "-- Pilih --",
        width: '100%'
    });

    let selectedFiles = [];
    let currentIndex = 0;
    const MAX_FILES = 5;
    const $fileInput = $('#bukti');
    const $previewContainer = $('#preview-container');

    // === Upload & Preview ===
    $('#btn-upload').on('click', function() {
        $fileInput.val('');
        $fileInput.trigger('click');
    });

    function syncInputFiles() {
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        $fileInput[0].files = dt.files;
    }

    $fileInput.on('change', function() {
        const incoming = Array.from(this.files || []);
        const newFiles = incoming.filter(f => !selectedFiles.some(sf => sf.name === f.name && sf
            .size === f.size));
        selectedFiles = [...selectedFiles, ...newFiles].slice(0, MAX_FILES);
        renderPreview();
        syncInputFiles();
    });

    function renderPreview() {
        $previewContainer.empty();
        selectedFiles.forEach((file, index) => {
            if (!['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) return;
            const reader = new FileReader();
            reader.onload = e => {
                const html = `
                    <div class="position-relative m-1" style="display:inline-block;">
                        <img src="${e.target.result}" class="preview-img" data-index="${index}"
                             style="width:100px;height:100px;object-fit:cover;border-radius:10px;border:1px solid #ccc;cursor:pointer;">
                        <button type="button" class="btn btn-danger btn-sm btn-remove" data-index="${index}"
                             style="position:absolute;top:-8px;right:-8px;border-radius:50%;padding:2px 6px;">×</button>
                    </div>`;
                $previewContainer.append(html);
            };
            reader.readAsDataURL(file);
        });
    }

    $(document).on('click', '.btn-remove', function(e) {
        e.stopPropagation();
        const idx = Number($(this).data('index'));
        selectedFiles.splice(idx, 1);
        renderPreview();
        syncInputFiles();
    });

    // === Modal Preview ===
    $(document).on('click', '.preview-img', function() {
        currentIndex = Number($(this).data('index'));
        showModalImage(currentIndex);
        $('#modalPreview').modal({
            backdrop: 'static', // aktifkan overlay, tidak bisa klik luar
            keyboard: true,
            show: true
        });
    });

    function showModalImage(i) {
        const file = selectedFiles[i];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => $('#modalImage').attr('src', e.target.result);
        reader.readAsDataURL(file);
    }

    $('#modalCloseBtn').on('click', () => $('#modalPreview').modal('hide'));
    $('#modalNext').on('click', () => {
        if (!selectedFiles.length) return;
        currentIndex = (currentIndex + 1) % selectedFiles.length;
        showModalImage(currentIndex);
    });
    $('#modalPrev').on('click', () => {
        if (!selectedFiles.length) return;
        currentIndex = (currentIndex - 1 + selectedFiles.length) % selectedFiles.length;
        showModalImage(currentIndex);
    });

    // === Navigasi Keyboard ===
    $(document).on('keydown', e => {
        if (!$('#modalPreview').hasClass('show')) return;
        if (e.key === 'Escape') $('#modalPreview').modal('hide');
        if (e.key === 'ArrowRight') $('#modalNext').trigger('click');
        if (e.key === 'ArrowLeft') $('#modalPrev').trigger('click');
    });

    // === Kunci scroll saat modal terbuka ===
    $('#modalPreview').on('shown.bs.modal', function() {
        $('body').css('overflow', 'hidden');
        $('#modalPreview, #modalPreview .modal-body').css({
            'overflow': 'hidden',
            'touch-action': 'none'
        });

        // ubah backdrop biar gelap elegan
        $('.modal-backdrop')
            .addClass('show')
            .css({
                'background-color': 'rgba(0,0,0,0.7)',
                'backdrop-filter': 'blur(2px)'
            });

        $(document).on('touchmove.modalBlock', function(e) {
            if ($('#modalPreview').hasClass('show')) e.preventDefault();
        });
    });

    // === Pulihkan scroll saat modal tertutup ===
    $('#modalPreview').on('hidden.bs.modal', function() {
        $('#modalImage').attr('src', '');
        $('body').css('overflow', 'auto');
        $('#modalPreview, #modalPreview .modal-body').css({
            'overflow': '',
            'touch-action': ''
        });
        $('.modal-backdrop').removeAttr('style');
        $(document).off('touchmove.modalBlock');
    });

    // === Datepicker ===
    $('#tanggal').datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        onSelect: function(dateText) {
            const [d, m, y] = dateText.split('/');
            const bln = parseInt(m);
            const th = parseInt(y.length === 2 ? '20' + y : y);
            const kuartal = bln <= 3 ? 'Q1 ' + th : bln <= 6 ? 'Q2 ' + th : bln <= 9 ? 'Q3 ' + th :
                'Q4 ' + th;
            $('#kuartal').val(kuartal);
        }
    });

    // === Dropdown Dinamis Sales / ASS ===
    $('#id_sales').on('change', function() {
        const idSales = $(this).val();

        // Reset semua field terkait
        $('#nama_sales, #distributor, #nama_asisten_manager').val('');

        // Reset dropdown ASS TANPA DISABLE
        $('#id_asisten_manager')
            .html('<option value="">-- Pilih ASS --</option>')
            .trigger('change');

        if (!idSales) return;

        // Ambil detail sales
        $.getJSON(`/kecurangan/sales/${idSales}`, function(data) {

            $('#nama_sales').val(data.nama_sales);
            $('#distributor').val(data.distributor);

            // Ambil ASS
            if (data.distributor_id) {

                $.getJSON(`/kecurangan/asisten-manager/${data.distributor_id}`, function(res) {

                    // Jika tidak ada ASS
                    if (res.length === 0) {
                        $('#id_asisten_manager')
                            .html('<option value="">-- Tidak ada ASS --</option>');
                        return;
                    }

                    // Jika ada ASS
                    let opt = '<option value="">-- Pilih ASS --</option>';
                    res.forEach(a => {
                        opt +=
                            `<option value="${a.id}">${a.id} - ${a.nama}</option>`;
                    });


                    $('#id_asisten_manager')
                        .html(opt)
                        .trigger('change');
                });
            }
        });
    });

    $('#id_asisten_manager').on('change', function() {
        const txt = $(this).find('option:selected').text();
        const parts = txt.split(' - ');

        // Ambil semua setelah ID
        const nama = parts.slice(1).join(' - ').trim();

        $('#nama_asisten_manager').val(nama);
    });




    $('#jenis_sanksi').on('change', function() {
        const jenis = $(this).val();
        const $deskripsiSelect = $('#deskripsi_sanksi');
        const $nilaiInput = $('#nilai_sanksi');
        $deskripsiSelect.html('<option value="">-- Pilih Deskripsi --</option>');
        $nilaiInput.val('');
        if (!jenis) return;
        $.getJSON(`/sanksi/deskripsi/${jenis}`, function(data) {
            let options = '<option value="">-- Pilih Deskripsi --</option>';
            data.forEach(item => {
                options +=
                    `<option value="${item.keterangan}">${item.keterangan}</option>`;
            });
            $deskripsiSelect.html(options);
        });
    });

    $('#deskripsi_sanksi').on('change', function() {
        const jenis = $('#jenis_sanksi').val();
        const deskripsi = $(this).val();
        const $nilaiInput = $('#nilai_sanksi');
        if (!jenis || !deskripsi) return $nilaiInput.val('');

        // encode deskripsi supaya karakter khusus (mis. "/") tidak merusak path URL
        const encodedDeskripsi = encodeURIComponent(deskripsi);

        $.getJSON(`/sanksi/nilai/${jenis}/${encodedDeskripsi}`, function(data) {
            if (data && data.nilai !== undefined) {
                const formatted = new Intl.NumberFormat('id-ID').format(data.nilai);
                $nilaiInput.val('Rp ' + formatted);
            } else $nilaiInput.val('');
        }).fail(function() {
            // debug ringan: kosongkan jika gagal
            $nilaiInput.val('');
        });
    });

});
</script>
@endpush
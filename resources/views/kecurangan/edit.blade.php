@extends('layouts.app', [
'namePage' => 'Edit Kecurangan',
'class' => 'sidebar-mini',
'activePage' => 'kecurangan',
])

@section('content')
<div class="panel-header panel-header-sm" style="background: #dbd300ff"></div>

<div class="content" style="
    backdrop-filter: blur(12px);
    margin-top: -70px;
    padding: 30px;
    color: #333;
">
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
                    <h4 class="card-title mb-0 text-dark mt-4">{{ __('Edit Data Kecurangan') }}</h4>
                </div>

                <div class="card-body" style="background: rgba(255,255,255,0.7); border-radius: 0 0 20px 20px;">
                    {{-- FORM UPDATE (fields) --}}
                    <form action="{{ route('kecurangan.update', $kecurangan->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- ===================== BAGIAN SALES ===================== --}}
                        <h6 class="heading-small text-success mb-3" style="font-weight:600;">Detail Sales</h6>

                        <div class="form-group">
                            <label for="id_sales" class="text-dark font-weight-bold">{{ __('ID Sales') }}</label>
                            <select name="id_sales" id="id_sales" class="form-control select2" required
                                style="border-radius:12px;">
                                <option value="">-- Pilih ID Sales --</option>
                                @foreach($sales as $s)
                                <option value="{{ $s->id }}" {{ $kecurangan->id_sales == $s->id ? 'selected' : '' }}>
                                    {{ $s->id }} - {{ $s->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label class="text-dark font-weight-bold">{{ __('Nama Sales') }}</label>
                                    <input type="text" id="nama_sales" name="nama_sales" class="form-control" readonly
                                        style="border-radius:12px;" value="{{ $kecurangan->nama_sales }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label class="text-dark font-weight-bold">{{ __('Distributor') }}</label>
                                    <input type="text" id="distributor" name="distributor" class="form-control" readonly
                                        style="border-radius:12px;" value="{{ $kecurangan->distributor }}">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4" style="border-color:#29b14a;">

                        {{-- ===================== BAGIAN ASISTEN MANAGER ===================== --}}
                        <h6 class="heading-small text-success mb-3" style="font-weight:600;">Detail Asisten Manager</h6>

                        <div class="form-group">
                            <label for="id_asisten_manager"
                                class="text-dark font-weight-bold">{{ __('ID ASS') }}</label>
                            <select name="id_asisten_manager" id="id_asisten_manager" class="form-control select2"
                                style="border-radius:12px;">
                                <option value="">-- Pilih Asisten Manager --</option>
                                @foreach($asistenManagers as $am)
                                <option value="{{ $am->id }}"
                                    {{ $kecurangan->id_asisten_manager == $am->id ? 'selected' : '' }}>
                                    {{ $am->id }} - {{ $am->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label for="nama_asisten_manager"
                                        class="text-dark font-weight-bold">{{ __('Nama Asisten Manager') }}</label>
                                    <input type="text" id="nama_asisten_manager" name="nama_asisten_manager"
                                        class="form-control" readonly style="border-radius:12px;"
                                        value="{{ $kecurangan->nama_asisten_manager }}">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4" style="border-color:#29b14a;">

                        {{-- ===================== BAGIAN KEJADIAN ===================== --}}
                        <h6 class="heading-small text-success mb-3" style="font-weight:600;">Detail Kejadian</h6>

                        <div class="row">
                            <div class="col-md-3">
                                <label class="text-dark font-weight-bold">Jenis Sanksi</label>
                                <select name="jenis_sanksi" id="jenis_sanksi" class="form-control select2" required>
                                    <option value="">-- Pilih Jenis Sanksi --</option>
                                    @foreach($jenisSanksi as $jenis)
                                    <option value="{{ $jenis }}"
                                        {{ $jenis == $kecurangan->jenis_sanksi ? 'selected' : '' }}>
                                        {{ $jenis }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="text-dark font-weight-bold">Deskripsi Sanksi</label>
                                <select name="deskripsi_sanksi" id="deskripsi_sanksi" class="form-control select2"
                                    required>
                                    {{-- Jika sedang edit dan sudah ada nilai --}}
                                    @if(!empty($kecurangan->keterangan_sanksi))
                                    <option value="{{ $kecurangan->keterangan_sanksi }}" selected>
                                        {{ $kecurangan->keterangan_sanksi }}
                                    </option>
                                    @else
                                    <option value="">-- Pilih Deskripsi --</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="text-dark font-weight-bold">Nilai Sanksi (Rp)</label>
                                <input type="text" id="nilai_sanksi" name="nilai_sanksi" class="form-control"
                                    value="Rp {{ number_format($kecurangan->nilai_sanksi, 0, ',', '.') }}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label class="text-dark font-weight-bold">{{ __('Toko') }}</label>
                                    <input type="text" name="toko" class="form-control" required
                                        style="border-radius:12px;" value="{{ $kecurangan->toko }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label class="text-dark font-weight-bold">{{ __('Kunjungan') }}</label>
                                    <input type="text" name="kunjungan" class="form-control" required
                                        style="border-radius:12px;" value="{{ $kecurangan->kunjungan }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group has-label" title="Tanggal Kunjungan">
                                    <label class="text-dark font-weight-bold">{{ __('Tanggal') }}</label>
                                    <input type="text" name="tanggal" id="tanggal" class="form-control"
                                        placeholder="dd/mm/yyyy" required style="border-radius:12px;"
                                        value="{{ \Carbon\Carbon::parse($kecurangan->tanggal)->format('d/m/Y') }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label class="text-dark font-weight-bold">{{ __('Kuartal') }}</label>
                                    <input type="text" name="kuartal" id="kuartal" class="form-control" readonly
                                        style="border-radius:12px;" value="{{ $kecurangan->kuartal }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-label">
                            <label class="text-dark font-weight-bold">{{ __('Keterangan') }}</label>
                            <textarea name="keterangan" class="form-control"
                                style="border-radius:12px; width:40%; height:100px; padding:10px 14px; border:1px solid #E3E3E3;">{{ old('keterangan', $kecurangan->keterangan) }}</textarea>
                        </div>
                        <hr class="my-4" style="border-color:#29b14a;">

                        {{-- ===================== BAGIAN FOTO ===================== --}}
                        <h6 class="heading-small text-success mb-3" style="font-weight:600;">Bukti Kecurangan (Foto)
                        </h6>

                        <div class="form-group has-label">
                            <label class="text-dark font-weight-bold d-block mb-2">
                                {{ __('Foto Lama') }}
                            </label>

                            {{-- Container foto lama --}}
                            <div id="existing-container"
                                class="mt-2 d-flex flex-wrap gap-2 justify-content-start align-items-start">
                                @foreach($fotos as $foto)
                                <div class="position-relative m-1 existing-photo" data-id="{{ $foto->id }}"
                                    style="display:inline-block;">
                                    <img src="{{ $foto->url }}" alt="Foto Lama" class="existing-img"
                                        style="width:100px;height:100px;object-fit:cover;border-radius:10px;border:1px solid #ccc;cursor:pointer;">
                                    <button type="button" class="btn btn-danger btn-sm btn-delete-existing"
                                        data-id="{{ $foto->id }}"
                                        style="position:absolute;top:-8px;right:-8px;border-radius:50%;padding:2px 6px;">
                                        ×
                                    </button>
                                </div>
                                @endforeach
                            </div>

                            <hr style="border-color:#29b14a;">

                            <label class="text-dark font-weight-bold d-block mb-2">
                                {{ __('Upload Foto Baru (Maksimal total 5 foto)') }}
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
                                Format diperbolehkan: JPG, JPEG, PNG — maksimal 5 foto (termasuk foto lama).
                            </small>
                        </div>

                        <!-- Tombol Simpan -->
                        <div class="text-right mt-4">
                            <a href="{{ route('kecurangan.data') }}" class="btn btn-secondary btn-round">Batal</a>
                            <button type="submit" class="btn btn-success btn-round">
                                <i class="now-ui-icons"></i> Simpan
                            </button>
                        </div>
                    </form>
                    {{-- end form update --}}
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

                {{-- Tombol Navigasi --}}
                <button type="button" id="modalPrev" class="btn btn-link" style="position:absolute;left:20px;top:50%;transform:translateY(-50%);
                    font-size:40px;color:#333;text-decoration:none;opacity:0.6;">‹</button>
                <button type="button" id="modalNext" class="btn btn-link" style="position:absolute;right:20px;top:50%;transform:translateY(-50%);
                    font-size:40px;color:#333;text-decoration:none;opacity:0.6;">›</button>
            </div>

            {{-- Footer --}}
            <div class="modal-footer" style="border-top:none;justify-content:center;">
                <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


@push('js')
{{-- Custom css --}}
<link href="{{ asset('css/kecurangan.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<style>
/* ===================== BACKDROP (ELEGAN) ===================== */
.modal-backdrop.show {
    opacity: 0.7 !important;
    background-color: rgba(0, 0, 0, 0.7) !important;
    backdrop-filter: blur(3px);
}

/* ===================== NON-SCROLL SAAT MODAL TERBUKA ===================== */
body.modal-open {
    overflow: hidden !important;
}

.modal {
    overflow: hidden !important;
}

/* ===================== TOMBOL MODAL (Z-INDEX & INTERAKSI) ===================== */
#modalCloseBtn,
#modalPrev,
#modalNext {
    z-index: 2102 !important;
    pointer-events: auto !important;
}

input:invalid,
textarea:invalid,
select:invalid {
    box-shadow: none !important;
    border-color: #ced4da !important;
    /* warna abu normal */
}

input:focus,
textarea:focus,
select:focus {
    border-color: #4caf50 !important;
    /* hijau atau sesuai tema */
}
</style>

<script>
$(document).ready(function() {
    // ===================== SELECT2 =====================
    $('#jenis_sanksi, #deskripsi_sanksi, #id_sales, #id_asisten_manager').select2({
        placeholder: "-- Pilih --",
        allowClear: false,
        width: '100%'
    });

    // ===================== VARIABEL =====================
    let selectedFiles = [];
    let currentIndex = 0;
    const MAX_FILES = 5;
    const $fileInput = $('#bukti');
    const $previewContainer = $('#preview-container');
    const $existingContainer = $('#existing-container');

    // ===================== HANDLE FOTO =====================
    function collectAllPreviewElements() {
        const arr = [];
        $existingContainer.find('img.existing-img').each(function() {
            arr.push($(this));
        });
        $previewContainer.find('img.preview-img').each(function() {
            arr.push($(this));
        });
        return arr;
    }

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
        const existingCount = $existingContainer.find('.existing-photo').length;
        const spaceLeft = MAX_FILES - existingCount;

        if (spaceLeft <= 0) {
            alert('Jumlah foto sudah mencapai batas maksimal (' + MAX_FILES + ').');
            $fileInput.val('');
            return;
        }

        selectedFiles = [...selectedFiles, ...newFiles].slice(0, spaceLeft);
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
                        <img src="${e.target.result}" alt="${file.name}" class="preview-img" data-index="${index}"
                            style="width:100px;height:100px;object-fit:cover;border-radius:10px;border:1px solid #ccc;cursor:pointer;">
                        <button type="button" class="btn btn-danger btn-sm btn-remove-new" data-index="${index}"
                            style="position:absolute;top:-8px;right:-8px;border-radius:50%;padding:2px 6px;">×</button>
                    </div>`;
                $previewContainer.append(html);
            };
            reader.readAsDataURL(file);
        });
    }

    $(document).on('click', '.btn-remove-new', function(e) {
        e.stopPropagation();
        const idx = Number($(this).data('index'));
        selectedFiles.splice(idx, 1);
        renderPreview();
        syncInputFiles();
    });

    $(document).on('click', '.btn-delete-existing', function(e) {
        e.stopPropagation();
        const $wrap = $(this).closest('.existing-photo');
        const fotoId = $wrap.data('id');
        $('form').append(`<input type="hidden" name="deleted_photos[]" value="${fotoId}">`);
        $wrap.remove();
    });

    // ===================== MODAL PREVIEW FOTO (GAYA BARU) =====================
    $(document).on('click', '.existing-img, .preview-img', function() {
        const allEls = collectAllPreviewElements();
        let idx = allEls.findIndex(el => el[0] === this);
        if (idx === -1) return;
        currentIndex = idx;
        showModalImageByIndex(currentIndex);
        $('#modalPreview').modal({
            backdrop: 'static',
            keyboard: true,
            show: true
        });
    });

    function showModalImageByIndex(index) {
        const allEls = collectAllPreviewElements();
        if (!allEls[index]) return;
        $('#modalImage').attr('src', allEls[index].attr('src') || '');
    }

    $(document).on('click', '#modalCloseBtn', () => $('#modalPreview').modal('hide'));
    $(document).on('click', '#modalNext', () => {
        const allEls = collectAllPreviewElements();
        if (!allEls.length) return;
        currentIndex = (currentIndex + 1) % allEls.length;
        showModalImageByIndex(currentIndex);
    });
    $(document).on('click', '#modalPrev', () => {
        const allEls = collectAllPreviewElements();
        if (!allEls.length) return;
        currentIndex = (currentIndex - 1 + allEls.length) % allEls.length;
        showModalImageByIndex(currentIndex);
    });
    $(document).on('keydown', function(e) {
        if (!$('#modalPreview').hasClass('show')) return;
        if (e.key === 'Escape') $('#modalPreview').modal('hide');
        else if (e.key === 'ArrowRight') $('#modalNext').trigger('click');
        else if (e.key === 'ArrowLeft') $('#modalPrev').trigger('click');
    });

    $('#modalPreview').on('show.bs.modal', function() {
        $('body').css('overflow', 'hidden');
    }).on('hidden.bs.modal', function() {
        $('#modalImage').attr('src', '');
        $('body').css('overflow', 'auto');
    });

    // ===================== VALIDASI SUBMIT =====================
    $('form').on('submit', function() {
        syncInputFiles();
        const existingCount = $existingContainer.find('.existing-photo').length;
        if (existingCount + selectedFiles.length > MAX_FILES) {
            alert('Total foto tidak boleh lebih dari ' + MAX_FILES + '.');
            return false;
        }
        return true;
    });

    // ===================== AJAX SALES =====================
    $('#id_sales').on('change', function() {
        const idSales = $(this).val();
        const $namaSales = $('#nama_sales');
        const $distributor = $('#distributor');
        const $idAsisten = $('#id_asisten_manager');
        const $namaAsisten = $('#nama_asisten_manager');

        $namaSales.val('');
        $distributor.val('');
        $namaAsisten.val('');
        $idAsisten.html('<option value="">Pilih Asisten Manager</option>').trigger('change');

        if (!idSales) return;

        $.getJSON(`/kecurangan/sales/${idSales}`, function(data) {
            $namaSales.val(data.nama_sales);
            $distributor.val(data.distributor);
            if (data.distributor_id) {
                $.getJSON(`/kecurangan/asisten-manager/${data.distributor_id}`, function(res) {
                    let options = '<option value="">Pilih Asisten Manager</option>';
                    res.forEach(am => {
                        options +=
                            `<option value="${am.id}">${am.id} - ${am.nama}</option>`;
                    });
                    $idAsisten.html(options).trigger('change');
                });
            }
        });
    });

    $('#id_asisten_manager').on('change', function() {
        const selectedText = $(this).find('option:selected').text();
        const nama = selectedText.split('-').slice(1).join('-').trim();
        $('#nama_asisten_manager').val(nama);
    });

    // ===================== AUTOLOAD SAAT EDIT =====================
    const initialSales = $('#id_sales').val();
    const currentAsistenId = "{{ $kecurangan->id_asisten_manager ?? '' }}";
    if (initialSales) {
        $.getJSON(`/kecurangan/sales/${initialSales}`, function(data) {
            $('#nama_sales').val(data.nama_sales);
            $('#distributor').val(data.distributor);
            if (data.distributor_id) {
                $.getJSON(`/kecurangan/asisten-manager/${data.distributor_id}`, function(res) {
                    let options = '<option value="">Pilih Asisten Manager</option>';
                    res.forEach(am => {
                        options +=
                            `<option value="${am.id}" ${am.id == currentAsistenId ? 'selected' : ''}>${am.id} - ${am.nama}</option>`;
                    });
                    $('#id_asisten_manager').html(options).trigger('change');
                });
            }
        });
    }

    // ===================== DATEPICKER =====================
    $('#tanggal').datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showAnim: 'slideDown',
        onSelect: function(dateText) {
            const [d, m, y] = dateText.split('/');
            const bln = parseInt(m);
            const th = parseInt(y);
            const kuartal = bln <= 3 ? 'Q1 ' + th : bln <= 6 ? 'Q2 ' + th : bln <= 9 ? 'Q3 ' + th :
                'Q4 ' + th;
            $('#kuartal').val(kuartal);
        }
    });

    // ===================== SANKSI =====================
    $('#jenis_sanksi').on('change', function() {
        const jenis = $(this).val();
        $.getJSON(`/sanksi/deskripsi/${jenis}`, function(data) {
            let options = '<option value="">-- Pilih Deskripsi --</option>';
            data.forEach(item => {
                options +=
                    `<option value="${item.keterangan}">${item.keterangan}</option>`;
            });
            $('#deskripsi_sanksi').html(options).trigger('change');
        });
    });

    $('#deskripsi_sanksi').on('change', function() {
        const jenis = $('#jenis_sanksi').val();
        const deskripsi = $(this).val();
        $.getJSON(`/sanksi/nilai/${jenis}/${deskripsi}`, function(data) {
            const formatted = new Intl.NumberFormat('id-ID').format(data.nilai);
            $('#nilai_sanksi').val('Rp ' + formatted);
        });
    });
});
</script>
@endpush

@endsection
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
                role="alert"
                style="background: rgba(41,177,74,0.2); border: 1px solid #29b14a; color: #155724; border-radius: 12px;">
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
                role="alert"
                style="background: rgba(231,76,60,0.2); border: 1px solid #e74c3c; color: #721c24; border-radius: 12px;">
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
                            <label class="text-dark font-weight-bold">ID Sales *</label>
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

                        {{-- ===================== DETAIL ASS ===================== --}}
                        <h6 class="heading-small text-success mb-3" style="font-weight:600;">Detail Asisten Manager</h6>

                        <div class="form-group">
                            <label class="text-dark font-weight-bold">ID ASS *</label>
                            <select name="id_ass" id="id_ass" class="form-control select2" required
                                style="border-radius:12px;">
                                <option value="">-- Pilih ASS --</option>
                            </select>
                        </div>

                        <div class="col-md-3 pl-0">
                            <div class="form-group has-label">
                                <label class="text-dark font-weight-bold">Nama ASS *</label>
                                <input type="text" id="nama_ass" name="nama_ass" class="form-control" readonly
                                    style="border-radius:12px;">
                            </div>
                        </div>

                        <hr class="my-4" style="border-color:#29b14a;">

                        {{-- ===================== DETAIL KEJADIAN ===================== --}}
                        <h6 class="heading-small text-success mb-3" style="font-weight:600;">Detail Kejadian</h6>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="text-dark font-weight-bold">Jenis Sanksi *</label>
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
                                    <label class="text-dark font-weight-bold">Deskripsi Sanksi *</label>
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
                                <label class="text-dark font-weight-bold">Toko (Customer)</label>
                                <select name="toko" id="customer_id" class="form-control select2"
                                    style="border-radius:12px;">
                                    <option value="">-- Tidak Isi Toko --</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="text-dark font-weight-bold">Kunjungan</label>
                                <input type="text" name="kunjungan" class="form-control" style="border-radius:12px;">
                            </div>

                            <div class="col-md-3">
                                <label class="text-dark font-weight-bold">Tanggal *</label>
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
                            <label class="text-dark font-weight-bold d-block mb-2">Upload Foto (Maksimal total 5
                                foto)</label>
                            <button type="button" id="btn-upload" class="btn btn-outline-success btn-round mb-3"
                                style="border-radius:12px;padding:8px 16px;">
                                <i class="now-ui-icons ui-1_simple-add"></i> Pilih Foto
                            </button>

                            <input type="file" name="bukti[]" id="bukti" accept=".jpg,.jpeg,.png" multiple hidden>

                            <div id="preview-container"
                                class="mt-2 d-flex flex-wrap gap-2 justify-content-start align-items-start"></div>

                            <small class="form-text text-muted mt-2">Format diperbolehkan: JPG, JPEG, PNG — maksimal 5
                                foto.</small>
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
        <div class="modal-content border-0"
            style="background:rgba(255,255,255,0.97); border-radius:15px; box-shadow:0 4px 25px rgba(0,0,0,0.3); overflow:hidden; position:relative;">
            <div class="modal-header d-flex justify-content-between align-items-center" style="border-bottom:none;">
                <h5 class="modal-title text-success" style="font-weight:600;">Bukti Kecurangan</h5>
            </div>

            <div class="modal-body d-flex justify-content-center align-items-center"
                style="height:75vh; overflow:hidden; position:relative;">
                <img id="modalImage" src="" alt="Preview"
                    style="max-width:90%; max-height:90%; object-fit:contain; border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.2); transition:0.3s;">
                <button type="button" id="modalPrev" class="btn btn-link"
                    style="position:absolute; left:0; top:0; height:100%; width:120px; display:flex; align-items:center; justify-content:flex-start; padding-left:25px; font-size:42px; color:#333; opacity:0.7;">‹</button>
                <button type="button" id="modalNext" class="btn btn-link"
                    style="position:absolute; right:0; top:0; height:100%; width:120px; display:flex; align-items:center; justify-content:flex-end; padding-right:25px; font-size:42px; color:#333; opacity:0.7;">›</button>
            </div>

            <div class="modal-footer" style="border-top:none; justify-content:center;">
                <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection


@push('styles')
{{-- ========== LOAD CSS ========== --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('assets/css/sidebar-fix.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/ui-lock.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/global-focus-input.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/global-background-wrapper.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/global-header.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/global-navbar.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/global-btn.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/global-btn-variant.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/global-pagination.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/global-search-bar.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/global-backdrop.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/modal-navigation-buttons.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/preview-container.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/calendar-ui.css') }}">

@endpush

@push('js')
<script>
    window.KECURANGAN_CONFIG = {
        salesDetailUrl: "{{ url('/kecurangan/sales') }}",
        assBySalesUrl: "{{ url('/kecurangan/ass') }}",
        customerBySalesUrl: "{{ url('/kecurangan/customer') }}",
        sanksiDeskripsiUrl: "{{ url('/sanksi/deskripsi') }}",
        nilaiSanksiUrl: "{{ url('/kecurangan/nilai') }}"
    };
</script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="{{ asset('assets/js/sidebar-fix.js') }}"></script>
<script src="{{ asset('assets/js/ui-lock.js') }}"></script>
<script src="{{ asset('assets/js/create-kecurangan.js') }}"></script>
@endpush

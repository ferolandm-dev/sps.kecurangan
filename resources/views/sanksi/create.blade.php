@extends('layouts.app', [
'namePage' => 'Tambah Sanksi',
'class' => 'sidebar-mini',
'activePage' => 'sanksi',
])

@section('content')
<div class="panel-header panel-header-sm panel-header-sps"></div>

<div class="content">
    <div class="row justify-content-center">
        <div class="col-md-12">

            {{-- ✅ ALERT SUCCESS --}}
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-lg" role="alert" style="
                background: linear-gradient(135deg, #29b14a 0%, #34d058 100%);
                color: #fff;
                border: none;
                border-radius: 14px;
                padding: 14px 18px;
                font-weight: 500;
                letter-spacing: 0.3px;
                box-shadow: 0 4px 12px rgba(41,177,74,0.3);
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 25px;
            ">
                <div class="d-flex align-items-center">
                    <i class="now-ui-icons ui-1_bell-53 mr-2" style="font-size:18px;"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="
                    color:#fff;
                    opacity:0.8;
                    font-size:22px;
                    margin-left:10px;
                ">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            {{-- ⚠️ ALERT ERROR --}}
            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-lg" role="alert" style="
                background: linear-gradient(135deg, #e74c3c 0%, #ff6b6b 100%);
                color: #fff;
                border: none;
                border-radius: 14px;
                padding: 14px 18px;
                font-weight: 500;
                letter-spacing: 0.3px;
                box-shadow: 0 4px 12px rgba(231,76,60,0.3);
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 25px;
            ">
                <div class="d-flex align-items-center">
                    <i class="now-ui-icons ui-1_bell-53 mr-2" style="font-size:18px;"></i>
                    <span>{!! session('error') !!}</span>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="
                    color:#fff;
                    opacity:0.8;
                    font-size:22px;
                    margin-left:10px;
                ">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            {{-- ⚠️ ALERT VALIDASI --}}
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-lg" role="alert" style="
                background: linear-gradient(135deg, #e74c3c 0%, #ff6b6b 100%);
                color: #fff;
                border: none;
                border-radius: 14px;
                padding: 14px 18px;
                font-weight: 500;
                letter-spacing: 0.3px;
                box-shadow: 0 4px 12px rgba(231,76,60,0.3);
                margin-bottom: 25px;
            ">
                <div class="d-flex align-items-start">
                    <i class="now-ui-icons ui-1_bell-53 mr-2" style="font-size:18px;margin-top:2px;"></i>
                    <ul class="mb-0 pl-2">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="
                    color:#fff;
                    opacity:0.8;
                    font-size:22px;
                    margin-left:10px;
                ">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            {{-- 💾 FORM TAMBAH SANGSI --}}
            <div class="card" style="border-radius:20px;">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="card-title mb-0 text-dark">{{ __('Tambah Sanksi Baru') }}</h4>
                </div>

                <div class="card-body" style="background: rgba(255,255,255,0.5); border-radius: 0 0 20px 20px;">
                    <form method="POST" action="{{ route('sanksi.store') }}">
                        @csrf

                        {{-- Jenis Sanksi --}}
                        <div class="form-group">
                            <label for="jenis">{{ __('Jenis Sanksi') }}</label>
                            <select name="jenis" id="jenis" class="form-control select2" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="Sanksi">Sanksi</option>
                                <option value="Non-Sanksi">Non-Sanksi</option>
                            </select>
                        </div>


                        {{-- Keterangan --}}
                        <div class="form-group">
                            <label for="keterangan">{{ __('Keterangan') }}</label>
                            <textarea name="keterangan" id="keterangan" rows="3" class="form-control"
                                placeholder="Tuliskan keterangan atau alasan sanksi..." style="border: 1px solid #ced4da;
                                    border-radius: 6px;
                                    padding: 10px 12px;
                                    resize: none;
                                    background: #fff;"></textarea>
                        </div>
                        {{-- Nilai (Rupiah) --}}
                        <div class="form-group">
                            <label for="nilai">{{ __('Nilai (Rupiah)') }}</label>
                            <input type="number" name="nilai" id="nilai" class="form-control"
                                placeholder="Contoh: 50000" min="0" step="100" required>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="text-right mt-4">
                            <a href="{{ route('sanksi.index') }}" class="btn btn-secondary btn-round">Batal</a>
                            <button type="submit" class="btn btn-success btn-round"
                                style="background:#29b14a;border:none;">
                                <i class="now-ui-icons ui-1_check"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@push('js')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
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
    transition: none !important; /* matikan transisi container */
}

.navbar-soft .nav-link i,
.navbar-soft .navbar-brand {
    transition: color .25s ease, transform .25s ease !important; /* biarkan hover tetap smooth */
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
</style>
<script>
$(document).ready(function() {
    $('#jenis').select2({
        placeholder: "-- Pilih Jenis --",
        allowClear: false,
        width: '100%'
    });
});
</script>
@endpush
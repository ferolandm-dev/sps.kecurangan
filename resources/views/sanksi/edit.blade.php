@extends('layouts.app', [
'namePage' => 'Edit Sanksi',
'class' => 'sidebar-mini',
'activePage' => 'sanksi',
])

@section('content')

<div class="panel-header panel-header-sm panel-header-sps"></div>

<div class="content">
    <div class="row justify-content-center">
        <div class="col-md-12">

            {{-- ===========================================================
                 ALERT: SUCCESS (jika update berhasil)
            =========================================================== --}}
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-lg" role="alert" style="background: linear-gradient(135deg, #29b14a 0%, #34d058 100%);
                color:#fff; border:none; border-radius:14px; padding:14px 18px;">

                <div class="d-flex align-items-center">
                    <i class="now-ui-icons ui-1_bell-53 mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>

                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif


            {{-- ===========================================================
                 ALERT: ERROR (jika terjadi error custom)
            =========================================================== --}}
            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-lg" role="alert" style="background: linear-gradient(135deg, #e74c3c 0%, #ff6b6b 100%);
                color:#fff; border:none; border-radius:14px; padding:14px 18px;">

                <div class="d-flex align-items-center">
                    <i class="now-ui-icons ui-1_bell-53 mr-2"></i>
                    <span>{!! session('error') !!}</span>
                </div>

                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif


            {{-- ===========================================================
                 ALERT: VALIDASI FORM (Error bawaan Laravel)
            =========================================================== --}}
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-lg" role="alert" style="background: linear-gradient(135deg, #e74c3c 0%, #ff6b6b 100%);
                color:#fff; border-radius:14px; padding:14px 18px;">

                <div class="d-flex align-items-start">
                    <i class="now-ui-icons ui-1_bell-53 mr-2"></i>

                    <ul class="mb-0 pl-2">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif


            {{-- ===========================================================
                 FORM EDIT SAN KSI
            =========================================================== --}}
            <div class="card" style="border-radius:20px;">

                {{-- Header Card --}}
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="card-title mb-0 text-dark">Edit Data Sanksi</h4>
                </div>

                {{-- Body Card --}}
                <div class="card-body" style="background: rgba(255,255,255,0.5); border-radius:0 0 20px 20px;">

                    {{-- FORM UPDATE --}}
                    <form method="POST" action="{{ route('sanksi.update', $sanksi->ID) }}">
                        @csrf
                        @method('PUT')

                        {{-- ==========================================
                             INPUT: Jenis Sanksi
                        =========================================== --}}
                        <div class="form-group">
                            <label for="jenis">Jenis Sanksi *</label>
                            <select name="jenis" id="jenis" class="form-control select2" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="Sanksi" {{ $sanksi->JENIS == 'Sanksi' ? 'selected' : '' }}>Sanksi
                                </option>
                                <option value="Non-Sanksi" {{ $sanksi->JENIS == 'Non-Sanksi' ? 'selected' : '' }}>
                                    Non-Sanksi</option>
                            </select>
                        </div>

                        {{-- ==========================================
                             INPUT: Keterangan
                        =========================================== --}}
                        <div class="form-group">
                            <label for="keterangan">Keterangan *</label>

                            <textarea name="keterangan" id="keterangan" rows="3" class="form-control"
                                placeholder="Tuliskan keterangan atau alasan sanksi..."
                                style="border:1px solid #ced4da; border-radius:6px; padding:10px 12px; resize:none; background:#fff;">{{ old('KETERANGAN', $sanksi->KETERANGAN) }}</textarea>
                        </div>

                        {{-- ==========================================
                             INPUT: Nilai Sanksi
                        =========================================== --}}
                        <div class="form-group">
                            <label for="nilai">Nilai (Rupiah) *</label>
                            <input type="number" name="nilai" id="nilai" class="form-control"
                                placeholder="Contoh: 50000" min="0" step="100"
                                value="{{ old('NILAI', $sanksi->NILAI) }}" required>
                        </div>

                        {{-- ==========================================
                             TOMBOL AKSI
                        =========================================== --}}
                        <div class="text-right mt-4">
                            <a href="{{ route('sanksi.index') }}" class="btn btn-secondary btn-round">
                                Batal
                            </a>

                            <button type="submit" class="btn btn-success btn-round"
                                style="background:#29b14a;border:none;">
                                Simpan
                            </button>
                        </div>

                    </form>
                </div>

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

@endpush

@push('js')
<script src="{{ asset('assets/js/sidebar-fix.js') }}"></script>
<script src="{{ asset('assets/js/ui-lock.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('assets/js/edit-sanksi.js') }}"></script>
@endpush
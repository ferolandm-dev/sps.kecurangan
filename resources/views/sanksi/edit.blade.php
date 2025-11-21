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

            {{-- ALERT SUCCESS --}}
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-lg" role="alert" style="
                background: linear-gradient(135deg, #29b14a 0%, #34d058 100%);
                color: #fff;border:none;border-radius:14px;padding:14px 18px;">
                <div class="d-flex align-items-center">
                    <i class="now-ui-icons ui-1_bell-53 mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            {{-- ALERT ERROR --}}
            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-lg" role="alert" style="
                background: linear-gradient(135deg, #e74c3c 0%, #ff6b6b 100%);
                color:#fff;border:none;border-radius:14px;padding:14px 18px;">
                <div class="d-flex align-items-center">
                    <i class="now-ui-icons ui-1_bell-53 mr-2"></i>
                    <span>{!! session('error') !!}</span>
                </div>
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            {{-- ALERT VALIDASI --}}
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-lg" role="alert" style="
                background: linear-gradient(135deg, #e74c3c 0%, #ff6b6b 100%);
                color:#fff;border-radius:14px;padding:14px 18px;">
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

            {{-- FORM EDIT --}}
            <div class="card" style="border-radius:20px;">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="card-title mb-0 text-dark">Edit Data Sanksi</h4>
                </div>

                <div class="card-body" style="background: rgba(255,255,255,0.5); border-radius:0 0 20px 20px;">
                    <form method="POST" action="{{ route('sanksi.update', $sanksi->ID) }}">
                        @csrf
                        @method('PUT')

                        {{-- Jenis Sanksi --}}
                        <div class="form-group">
                            <label for="jenis">Jenis Sanksi</label>
                            <select name="jenis" id="jenis" class="form-control select2" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="Sanksi" {{ $sanksi->JENIS == 'Sanksi' ? 'selected' : '' }}>Sanksi
                                </option>
                                <option value="Non-Sanksi" {{ $sanksi->JENIS == 'Non-Sanksi' ? 'selected' : '' }}>
                                    Non-Sanksi
                                </option>
                            </select>
                        </div>

                        {{-- Keterangan --}}
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="3" class="form-control"
                                placeholder="Tuliskan keterangan atau alasan sanksi..." style="border: 1px solid #ced4da;
                            border-radius: 6px;
                            padding: 10px 12px;
                            resize: none;
                            background: #fff;">{{ old('KETERANGAN', $sanksi->KETERANGAN) }}</textarea>

                        </div>

                        {{-- Nilai --}}
                        <div class="form-group">
                            <label for="nilai">Nilai (Rupiah)</label>
                            <input type="number" name="nilai" id="nilai" class="form-control"
                                placeholder="Contoh: 50000" min="0" step="100"
                                value="{{ old('NILAI', $sanksi->NILAI) }}" required>
                        </div>

                        {{-- Tombol --}}
                        <div class="text-right mt-4">
                            <a href="{{ route('sanksi.index') }}" class="btn btn-secondary btn-round">Batal</a>
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

{{-- CSS --}}
<link rel="stylesheet" href="{{ asset('assets/css/sidebar-fix.css') }}">
<script src="{{ asset('assets/js/sidebar-fix.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/css/ui-lock.css') }}">
<script src="{{ asset('assets/js/ui-lock.js') }}"></script>

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
}

body,
.wrapper,
.main-panel {
    background: linear-gradient(140deg, #29b14a 0%, #c7c500 50%, #dbd300 92%) !important;
    background-attachment: fixed !important;
}

.panel-header-sps {
    background: transparent !important;
}

.content {
    background: transparent !important;
}

/* Navbar */
.navbar-soft {
    background: linear-gradient(90deg, #29b14a 0%, #dbd300 85%) !important;
    height: 95px !important;
    border-bottom-left-radius: 20px;
    border-bottom-right-radius: 20px;
}

.navbar-soft .navbar-brand {
    color: #fff !important;
    font-weight: 700;
}

/* Global Button Style */
.btn {
    border-radius: 12px !important;
    font-weight: 600 !important;
    transition: 0.25s !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
}

.btn:hover {
    transform: translateY(-2px);
}

/* Specific Buttons */
.btn-success {
    background: linear-gradient(135deg, #29b14a, #34d058) !important;
    color: #fff !important;
}

.btn-secondary {
    background: linear-gradient(135deg, #bfc2c7, #d6d8db) !important;
    color: #333 !important;
}

/* Round Style */
.btn-round {
    border-radius: 30px !important;
}
</style>
@endpush

@push('js')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('#jenis').select2({
        placeholder: "-- Pilih Jenis --",
        width: '100%'
    });
});
</script>
@endpush
@extends('layouts.app', [
'namePage' => 'Tambah Asisten Manager',
'class' => 'sidebar-mini',
'activePage' => 'asisten_managers',
])

@section('content')
<div class="panel-header panel-header-sm"></div>

<div class="content">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">{{ __('Tambah Asisten Manager Baru') }}</h4>
                </div>

                <div class="card-body">
                    {{-- ALERT ERROR (jika ID sudah terdaftar) --}}
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {!! session('error') !!}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('asisten_manager.store') }}">
                        @csrf

                        {{-- Distributor --}}
                        <div class="form-group">
                            <label for="id_distributor">{{ __('Distributor') }}</label>
                            <select name="id_distributor" id="id_distributor" class="form-control select2" required>
                                <option value="">-- Pilih Distributor --</option>
                                @foreach($distributors as $distributor)
                                <option value="{{ $distributor->id }}">
                                    {{ $distributor->id }} - {{ $distributor->distributor }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- ID Asisten Manager --}}
                        <div class="form-group">
                            <label for="id">{{ __('ID Asisten Manager') }}</label>
                            <input type="text" name="id" id="id" maxlength="6"
                                class="form-control @error('id') is-invalid @enderror"
                                oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');" required>
                        </div>

                        {{-- Nama Asisten Manager --}}
                        <div class="form-group">
                            <label for="nama">{{ __('Nama Asisten Manager') }}</label>
                            <input type="text" name="nama" id="nama" class="form-control" required>
                        </div>

                        <div class="text-right">
                            <a href="{{ route('asisten_manager.index') }}"
                                class="btn btn-secondary btn-round">Batal</a>
                            <button type="submit" class="btn btn-success btn-round">
                                <i class="now-ui-icons ui-1_check"></i> {{ __('Simpan') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('#id_distributor').select2({
        placeholder: "-- Pilih Distributor --",
        allowClear: false,
        width: '100%'
    });
});
</script>
@endpush
@endsection
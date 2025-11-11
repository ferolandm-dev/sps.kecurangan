@extends('layouts.app', [
'namePage' => 'Edit Distributor',
'class' => 'sidebar-mini',
'activePage' => 'distributors',
])

@section('content')
<div class="panel-header panel-header-sm"></div>

<div class="content">
    <div class="row justify-content-center">
        {{-- 🔹 Ubah col-md-8 → col-md-10 agar ukuran sama --}}
        <div class="col-md-12">
            <div class="card">
                {{-- 🔹 Header diseragamkan dengan tombol Kembali di kanan --}}
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="now-ui-icons business_badge"></i> {{ __('Edit Distributor') }}
                    </h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('distributors.update', $distributor->id) }}">
                        @csrf
                        @method('PUT')

                        {{-- ID Distributor --}}
                        <div class="form-group">
                            <label for="id">{{ __('ID Distributor') }}</label>
                            <input type="text" name="id" id="id" class="form-control" value="{{ $distributor->id }}"
                                readonly>
                        </div>

                        {{-- Nama Distributor --}}
                        <div class="form-group">
                            <label for="distributor">{{ __('Nama Distributor') }}</label>
                            <input type="text" name="distributor" id="distributor" class="form-control"
                                value="{{ $distributor->distributor }}" required>
                        </div>

                        {{-- Status Distributor --}}
                        <div class="form-group">
                            <label>{{ __('Status Distributor') }}</label>
                            <div class="btn-group btn-group-toggle d-flex justify-content-start" data-toggle="buttons">
                                <label
                                    class="btn btn-round btn-outline-success {{ strtolower($distributor->status) == 'aktif' ? 'active' : '' }} mr-2">
                                    <input type="radio" name="status" id="status-aktif" value="Aktif"
                                        {{ strtolower($distributor->status) == 'aktif' ? 'checked' : '' }}
                                        autocomplete="off">
                                    <i class="now-ui-icons ui-1_check mr-1"></i> Aktif
                                </label>

                                <label
                                    class="btn btn-round btn-outline-danger {{ strtolower($distributor->status) == 'nonaktif' ? 'active' : '' }}">
                                    <input type="radio" name="status" id="status-nonaktif" value="Nonaktif"
                                        {{ strtolower($distributor->status) == 'nonaktif' ? 'checked' : '' }}
                                        autocomplete="off">
                                    <i class="now-ui-icons ui-1_simple-remove mr-1"></i> Nonaktif
                                </label>
                            </div>
                        </div>

                        {{-- Tombol Simpan --}}
                        <div class="text-right">
                            <a href="{{ route('distributors.index') }}" class="btn btn-secondary btn-round">Batal</a>
                            <button type="submit" class="btn btn-success btn-round">
                                <i class="now-ui-icons "></i> {{ __('Simpan') }}
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
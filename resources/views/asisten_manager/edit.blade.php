@extends('layouts.app', [
'namePage' => 'Edit Asisten Manager',
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
                    <h4 class="card-title mb-0">
                        <i class="now-ui-icons users_single-02"></i> {{ __('Edit Data Asisten Manager') }}
                    </h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('asisten_manager.update', $asistenManager->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- ID Asisten Manager (non-editable) --}}
                        <div class="form-group">
                            <label for="id">{{ __('ID Asisten Manager') }}</label>
                            <input type="text" name="id" id="id" class="form-control" value="{{ $asistenManager->id }}"
                                readonly>
                        </div>

                        {{-- ID Distributor (non-editable) --}}
                        <div class="form-group">
                            <label for="id_distributor">{{ __('ID Distributor') }}</label>
                            <input type="text" id="id_distributor" class="form-control"
                                value="{{ $asistenManager->id_distributor }}" readonly>
                        </div>

                        {{-- Nama Asisten Manager --}}
                        <div class="form-group">
                            <label for="nama">{{ __('Nama Asisten Manager') }}</label>
                            <input type="text" name="nama" id="nama" class="form-control"
                                value="{{ $asistenManager->nama }}" required>
                        </div>

                        {{-- Status --}}
                        <div class="form-group">
                            <label>{{ __('Status Asisten Manager') }}</label>
                            <div class="btn-group btn-group-toggle d-flex justify-content-start" data-toggle="buttons">
                                <label
                                    class="btn btn-round btn-outline-success {{ $asistenManager->status == 'Aktif' ? 'active' : '' }} mr-2">
                                    <input type="radio" name="status" value="Aktif"
                                        {{ $asistenManager->status == 'Aktif' ? 'checked' : '' }} autocomplete="off">
                                    <i class="now-ui-icons ui-1_check mr-1"></i> Aktif
                                </label>
                                <label
                                    class="btn btn-round btn-outline-danger {{ $asistenManager->status == 'Nonaktif' ? 'active' : '' }}">
                                    <input type="radio" name="status" value="Nonaktif"
                                        {{ $asistenManager->status == 'Nonaktif' ? 'checked' : '' }} autocomplete="off">
                                    <i class="now-ui-icons ui-1_simple-remove mr-1"></i> Nonaktif
                                </label>
                            </div>
                        </div>

                        <div class="text-right">
                            <a href="{{ route('asisten_manager.index') }}"
                                class="btn btn-secondary btn-round">Batal</a>
                            <button type="submit" class="btn btn-success btn-round">
                                <i class="now-ui-icons"></i> {{ __('Simpan') }}
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
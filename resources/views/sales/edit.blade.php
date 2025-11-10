@extends('layouts.app', [
'namePage' => 'Edit Sales',
'class' => 'sidebar-mini',
'activePage' => 'sales',
])

@section('content')
<div class="panel-header panel-header-sm"></div>

<div class="content">
    <div class="row justify-content-center">
        {{-- 🔹 ubah dari col-md-8 ke col-md-10 agar lebar sama --}}
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="now-ui-icons users_single-02"></i> {{ __('Edit Data Sales') }}
                    </h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('sales.update', $sales->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- ID Sales (non-editable) --}}
                        <div class="form-group">
                            <label for="id">{{ __('ID Sales') }}</label>
                            <input type="text" name="id" id="id" class="form-control" value="{{ $sales->id }}" readonly>
                        </div>

                        {{-- ID Distributor (non-editable) --}}
                        <div class="form-group">
                            <label for="id_distributor">{{ __('ID Distributor') }}</label>
                            <input type="text" id="id_distributor" class="form-control"
                                value="{{ $sales->id_distributor }}" readonly>
                        </div>

                        {{-- Nama Sales --}}
                        <div class="form-group">
                            <label for="nama">{{ __('Nama Sales') }}</label>
                            <input type="text" name="nama" id="nama" class="form-control" value="{{ $sales->nama }}"
                                required>
                        </div>

                        {{-- Status --}}
                        <div class="form-group">
                            <label>{{ __('Status Sales') }}</label>
                            <div class="btn-group btn-group-toggle d-flex justify-content-start" data-toggle="buttons">
                                <label
                                    class="btn btn-round btn-outline-success {{ $sales->status == 'Aktif' ? 'active' : '' }} mr-2">
                                    <input type="radio" name="status" value="Aktif"
                                        {{ $sales->status == 'Aktif' ? 'checked' : '' }} autocomplete="off">
                                    <i class="now-ui-icons ui-1_check mr-1"></i> Aktif
                                </label>
                                <label
                                    class="btn btn-round btn-outline-danger {{ $sales->status == 'Nonaktif' ? 'active' : '' }}">
                                    <input type="radio" name="status" value="Nonaktif"
                                        {{ $sales->status == 'Nonaktif' ? 'checked' : '' }} autocomplete="off">
                                    <i class="now-ui-icons ui-1_simple-remove mr-1"></i> Nonaktif
                                </label>
                            </div>
                        </div>

                        <div class="text-right">
                            <a href="{{ route('sales.index') }}" class="btn btn-secondary btn-round">Batal</a>
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
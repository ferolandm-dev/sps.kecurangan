@extends('layouts.app', [
'namePage' => 'Tambah Distributor',
'class' => 'sidebar-mini',
'activePage' => 'distributors',
])

@section('content')
<div class="panel-header panel-header-sm"></div>

<div class="content">
    <div class="row justify-content-center">
        {{-- Ubah col-md-8 jadi col-md-10 agar ukuran sama --}}
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">{{ __('Tambah Distributor') }}</h4>
                </div>

                <div class="card-body">

                    {{-- ALERT ERROR (jika ID Distributor sudah terdaftar) --}}
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {!! session('error') !!}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    {{-- ALERT SUCCESS --}}
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {!! session('success') !!}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    {{-- ALERT ERROR VALIDASI LAIN --}}
                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    <form action="{{ route('distributors.store') }}" method="POST">
                        @csrf

                        {{-- ID Distributor --}}
                        <div class="form-group">
                            <label for="id">{{ __('ID Distributor') }}</label>
                            <input type="text" name="id" id="id" value="{{ old('id') }}" maxlength="6"
                                class="form-control @error('id') is-invalid @enderror"
                                oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');" required>
                        </div>

                        {{-- Nama Distributor --}}
                        <div class="form-group">
                            <label for="distributor">{{ __('Nama Distributor') }}</label>
                            <input type="text" name="distributor" id="distributor" value="{{ old('distributor') }}"
                                class="form-control @error('distributor') is-invalid @enderror" required>
                        </div>

                        <input type="hidden" name="status" value="Aktif">

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
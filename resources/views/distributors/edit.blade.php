@extends('layouts.app', [
'namePage' => 'Edit Distributor',
'class' => 'sidebar-mini',
'activePage' => 'distributors',
])

@section('content')
<div class="panel-header panel-header-sm" style="background: #dbd300ff"></div>

<div class="content" style="
    backdrop-filter: blur(12px);
    margin-top: -70px;
    padding: 30px;
    color: #333;
">
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
                    <i class="now-ui-icons ui-1_check mr-2" style="font-size:18px;"></i>
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

            {{-- ✏️ FORM EDIT DISTRIBUTOR --}}
            <div class="card" style="border-radius: 20px;">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="card-title mb-0 text-dark">
                        <i class="now-ui-icons "></i> {{ __('Edit Distributor') }}
                    </h4>
                </div>

                <div class="card-body" style="background: rgba(255,255,255,0.5); border-radius: 0 0 20px 20px;">
                    <form method="POST" action="{{ route('distributors.update', $distributor->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="id">{{ __('ID Distributor') }}</label>
                            <input type="text" name="id" id="id" class="form-control" value="{{ $distributor->id }}"
                                readonly>
                        </div>

                        <div class="form-group">
                            <label for="distributor">{{ __('Nama Distributor') }}</label>
                            <input type="text" name="distributor" id="distributor" class="form-control"
                                value="{{ $distributor->distributor }}" required>
                        </div>

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

                        <div class="text-right mt-4">
                            <a href="{{ route('distributors.index') }}" class="btn btn-secondary btn-round">Batal</a>
                            <button type="submit" class="btn btn-success btn-round"
                                style="background:#29b14a;border:none;">
                                <i class="now-ui-icons"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
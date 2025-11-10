@extends('layouts.app', [
'namePage' => 'Master Kecurangan',
'class' => 'sidebar-mini',
'activePage' => 'kecurangan',
])

@section('content')
<div class="panel-header panel-header-sm"></div>
<div class="content">
    <div class="row">
        <div class="col-md-12">

            {{-- ALERT SUCCESS --}}
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">{{ __('Master Kecurangan') }}</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('kecurangan.store') }}" method="POST">
                        @csrf

                        <h6 class="heading-small text-muted mb-3">Detail SALES</h6>

                        {{-- ID Sales --}}
                        <div class="form-group">
                            <label for="id_sales">{{ __('ID Sales') }}</label>
                            <select name="id_sales" id="id_sales" class="form-control select2" required>
                                <option value="">-- Pilih ID Sales --</option>
                                @foreach($sales as $s)
                                <option value="{{ $s->id }}">{{ $s->id }} - {{ $s->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label>{{ __('Nama Sales') }}</label>
                                    <input type="text" id="nama_sales" name="nama_sales" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label>{{ __('Distributor') }}</label>
                                    <input type="text" id="distributor" name="distributor" class="form-control"
                                        readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label>{{ __('Toko') }}</label>
                                    <input type="text" name="toko" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="heading-small text-muted mb-3">Detail Kejadian</h6>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label>{{ __('Kunjungan') }}</label>
                                    <input type="text" name="kunjungan" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group has-label" title="Tanggal Kunjungan">
                                    <label>{{ __('Tanggal') }}</label>
                                    <input type="text" name="tanggal" id="tanggal" class="form-control"
                                        placeholder="dd/mm/yy" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group has-label">
                                    <label>{{ __('Keterangan') }}</label>
                                    <input type="text" name="keterangan" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-success btn-round">
                                <i class="now-ui-icons"></i> Simpan
                            </button>
                        </div>

                    </form>
                </div> {{-- end card-body --}}
            </div> {{-- end card --}}
        </div>
    </div>
</div>

@push('js')
{{-- Select2 untuk dropdown searchable --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- jQuery UI Datepicker --}}
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<script>
$(document).ready(function() {

    // --- Select2 ID Sales ---
    $('#id_sales').select2({
        placeholder: "-- Pilih ID Sales --",
        allowClear: false,
        width: '100%'
    });

    // --- AJAX Auto Fill Nama & Distributor ---
    $('#id_sales').on('change', function() {
        const id = $(this).val();
        const $nama = $('#nama_sales');
        const $dist = $('#distributor');

        if (!id) {
            $nama.val('');
            $dist.val('');
            return;
        }

        $nama.val('Memuat...');
        $dist.val('Memuat...');

        $.ajax({
            url: `/kecurangan/sales/${id}`,
            type: 'GET',
            success: function(data) {
                $nama.val(data.nama_sales || '');
                $dist.val(data.distributor || '');
            },
            error: function() {
                $nama.val('Gagal memuat');
                $dist.val('Gagal memuat');
            }
        });
    });

    // --- Datepicker untuk input tanggal ---
    $('#tanggal').datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showAnim: 'slideDown'
    }).attr('placeholder', 'dd/mm/yy'); // tampilkan placeholder dalam input

    // --- Kosongkan tanggal jika data tersimpan ---
    @if(session('success'))
    $('#tanggal').val('');
    @endif
});
</script>
@endpush


@endsection
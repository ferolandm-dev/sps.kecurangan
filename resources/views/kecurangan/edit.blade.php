@extends('layouts.app', [
'namePage' => 'Edit Kecurangan',
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
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
            </div>
            @endif

            {{-- ALERT ERROR --}}
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
            </div>
            @endif

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">{{ __('Edit Data Kecurangan') }}</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('kecurangan.update', $kecurangan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- ===================== BAGIAN SALES ===================== --}}
                        <h6 class="heading-small text-muted mb-3">Detail SALES</h6>

                        {{-- ID Sales --}}
                        <div class="form-group">
                            <label for="id_sales">{{ __('ID Sales') }}</label>
                            <select name="id_sales" id="id_sales" class="form-control select2" required>
                                <option value="">-- Pilih ID Sales --</option>
                                @foreach($sales as $s)
                                <option value="{{ $s->id }}" {{ $kecurangan->id_sales == $s->id ? 'selected' : '' }}>
                                    {{ $s->id }} - {{ $s->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label>{{ __('Nama Sales') }}</label>
                                    <input type="text" id="nama_sales" name="nama_sales" class="form-control"
                                        value="{{ $kecurangan->nama_sales }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label>{{ __('Distributor') }}</label>
                                    <input type="text" id="distributor" name="distributor" class="form-control"
                                        value="{{ $kecurangan->distributor }}" readonly>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- ===================== BAGIAN ASISTEN MANAGER ===================== --}}
                        <h6 class="heading-small text-muted mb-3">Detail Asisten Manager</h6>

                        <div class="form-group">
                            <label for="id_asisten_manager">{{ __('ID Asisten Manager') }}</label>
                            <select name="id_asisten_manager" id="id_asisten_manager" class="form-control select2">
                                <option value="">-- Pilih Asisten Manager --</option>
                                @foreach($asistenManagers as $am)
                                <option value="{{ $am->id }}"
                                    {{ $kecurangan->id_asisten_manager == $am->id ? 'selected' : '' }}>
                                    {{ $am->id }} - {{ $am->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label for="nama_asisten_manager">{{ __('Nama Asisten Manager') }}</label>
                                    <input type="text" id="nama_asisten_manager" name="nama_asisten_manager"
                                        class="form-control" value="{{ $kecurangan->nama_asisten_manager }}" readonly>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- ===================== BAGIAN KEJADIAN ===================== --}}
                        <h6 class="heading-small text-muted mb-3">Detail Kejadian</h6>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label>{{ __('Toko') }}</label>
                                    <input type="text" name="toko" class="form-control" value="{{ $kecurangan->toko }}"
                                        required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label>{{ __('Kunjungan') }}</label>
                                    <input type="text" name="kunjungan" class="form-control"
                                        value="{{ $kecurangan->kunjungan }}" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label>{{ __('Tanggal') }}</label>
                                    <input type="text" name="tanggal" id="tanggal" class="form-control"
                                        value="{{ \Carbon\Carbon::parse($kecurangan->tanggal)->format('d/m/Y') }}"
                                        required>
                                </div>
                            </div>

                            {{-- Kuartal --}}
                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label>{{ __('Kuartal') }}</label>
                                    <input type="text" name="kuartal" id="kuartal" class="form-control"
                                        value="{{ $kecurangan->kuartal }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group has-label">
                                    <label>{{ __('Keterangan') }}</label>
                                    <input type="text" name="keterangan" class="form-control"
                                        value="{{ $kecurangan->keterangan }}">
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-success btn-round">
                                <i class="now-ui-icons"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@push('js')
{{-- Select2 --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- jQuery UI Datepicker --}}
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<script>
$(document).ready(function() {
    // Inisialisasi Select2
    $('#id_sales, #id_asisten_manager').select2({
        placeholder: "-- Pilih --",
        allowClear: true,
        width: '100%'
    });

    // Saat ubah tanggal → update kuartal otomatis
    $('#tanggal').datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        onSelect: function(dateText) {
            const [day, month, year] = dateText.split('/');
            const bulan = parseInt(month);
            const tahun = parseInt(year.length === 2 ? '20' + year : year);
            let kuartal = '';

            if (bulan >= 1 && bulan <= 3) kuartal = 'Q1 ' + tahun;
            else if (bulan >= 4 && bulan <= 6) kuartal = 'Q2 ' + tahun;
            else if (bulan >= 7 && bulan <= 9) kuartal = 'Q3 ' + tahun;
            else if (bulan >= 10 && bulan <= 12) kuartal = 'Q4 ' + tahun;

            $('#kuartal').val(kuartal);
        }
    });

    // === Saat pilih Sales ===
    $('#id_sales').on('change', function() {
        const idSales = $(this).val();
        const $namaSales = $('#nama_sales');
        const $distributor = $('#distributor');
        const $idAsisten = $('#id_asisten_manager');
        const $namaAsisten = $('#nama_asisten_manager');

        $namaSales.val('');
        $distributor.val('');
        $namaAsisten.val('');
        $idAsisten.html('<option value="">Pilih Asisten Manager</option>').trigger('change');

        if (!idSales) return;

        $.ajax({
            url: `/kecurangan/sales/${idSales}`,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $namaSales.val(data.nama_sales);
                $distributor.val(data.distributor);

                if (data.distributor_id) {
                    $.ajax({
                        url: `/kecurangan/asisten-manager/${data.distributor_id}`,
                        type: 'GET',
                        dataType: 'json',
                        success: function(res) {
                            let options =
                                '<option value="">Pilih Asisten Manager</option>';
                            res.forEach(am => {
                                options +=
                                    `<option value="${am.id}">${am.id} - ${am.nama}</option>`;
                            });
                            $idAsisten.html(options).trigger('change');
                        }
                    });
                }
            }
        });
    });

    // Saat pilih asisten manager
    $('#id_asisten_manager').on('change', function() {
        const selectedText = $(this).find('option:selected').text();
        const nama = selectedText.split('-').slice(1).join('-').trim();
        $('#nama_asisten_manager').val(nama);
    });
});
</script>
@endpush
@endsection
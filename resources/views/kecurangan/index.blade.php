@extends('layouts.app', [
'namePage' => 'Master Kecurangan',
'class' => 'sidebar-mini',
'activePage' => 'kecurangan',
])

@section('content')
<div class="panel-header panel-header-sm" style="background: #c3be25ff"></div>

<div class="content" style="
    backdrop-filter: blur(12px);
    margin-top: -70px;
    padding: 30px;
    color: #333;
">
    <div class="row">
        <div class="col-md-12">

            {{-- ALERT SUCCESS --}}
            @if(session('success'))
            <div class="alert alert-success alert-with-icon alert-dismissible fade show" data-notify="container"
                role="alert" style="
                    background: rgba(41,177,74,0.2);
                    border: 1px solid #29b14a;
                    color: #155724;
                    border-radius: 12px;
                ">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color:#155724;">
                    <i class="now-ui-icons ui-1_simple-remove"></i>
                </button>
                <span data-notify="icon" class="now-ui-icons ui-1_check"></span>
                <span data-notify="message">{{ session('success') }}</span>
            </div>
            @endif

            {{-- ALERT ERROR --}}
            @if(session('error'))
            <div class="alert alert-danger alert-with-icon alert-dismissible fade show" data-notify="container"
                role="alert" style="
                    background: rgba(231,76,60,0.2);
                    border: 1px solid #e74c3c;
                    color: #721c24;
                    border-radius: 12px;
                ">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color:#721c24;">
                    <i class="now-ui-icons ui-1_simple-remove"></i>
                </button>
                <span data-notify="icon" class="now-ui-icons ui-1_bell-53"></span>
                <span data-notify="message">{{ session('error') }}</span>
            </div>
            @endif

            <div class="card" style="border-radius: 20px;">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap"
                    style="border-radius: 20px 20px 0 0; background: rgba(255,255,255,0.5);">
                    <h4 class="card-title mb-0 text-dark mt-4">{{ __('Master Kecurangan') }}</h4>
                </div>

                <div class="card-body" style="background: rgba(255,255,255,0.7); border-radius: 0 0 20px 20px;">
                    <form action="{{ route('kecurangan.store') }}" method="POST">
                        @csrf

                        {{-- ===================== BAGIAN SALES ===================== --}}
                        <h6 class="heading-small text-success mb-3" style="font-weight:600;">Detail Sales</h6>

                        <div class="form-group">
                            <label for="id_sales" class="text-dark font-weight-bold">{{ __('ID Sales') }}</label>
                            <select name="id_sales" id="id_sales" class="form-control select2" required
                                style="border-radius:12px;">
                                <option value="">-- Pilih ID Sales --</option>
                                @foreach($sales as $s)
                                <option value="{{ $s->id }}">{{ $s->id }} - {{ $s->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label class="text-dark font-weight-bold">{{ __('Nama Sales') }}</label>
                                    <input type="text" id="nama_sales" name="nama_sales" class="form-control" readonly
                                        style="border-radius:12px;">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label class="text-dark font-weight-bold">{{ __('Distributor') }}</label>
                                    <input type="text" id="distributor" name="distributor" class="form-control" readonly
                                        style="border-radius:12px;">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4" style="border-color:#29b14a;">

                        {{-- ===================== BAGIAN ASISTEN MANAGER ===================== --}}
                        <h6 class="heading-small text-success mb-3" style="font-weight:600;">Detail Asisten Manager</h6>

                        <div class="form-group">
                            <label for="id_asisten_manager"
                                class="text-dark font-weight-bold">{{ __('ID Asisten Manager') }}</label>
                            <select name="id_asisten_manager" id="id_asisten_manager" class="form-control select2"
                                style="border-radius:12px;">
                                <option value="">-- Pilih Asisten Manager --</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label for="nama_asisten_manager"
                                        class="text-dark font-weight-bold">{{ __('Nama Asisten Manager') }}</label>
                                    <input type="text" id="nama_asisten_manager" name="nama_asisten_manager"
                                        class="form-control" readonly style="border-radius:12px;">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4" style="border-color:#29b14a;">

                        {{-- ===================== BAGIAN KEJADIAN ===================== --}}
                        <h6 class="heading-small text-success mb-3" style="font-weight:600;">Detail Kejadian</h6>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label class="text-dark font-weight-bold">{{ __('Toko') }}</label>
                                    <input type="text" name="toko" class="form-control" required
                                        style="border-radius:12px;">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label class="text-dark font-weight-bold">{{ __('Kunjungan') }}</label>
                                    <input type="text" name="kunjungan" class="form-control" required
                                        style="border-radius:12px;">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group has-label" title="Tanggal Kunjungan">
                                    <label class="text-dark font-weight-bold">{{ __('Tanggal') }}</label>
                                    <input type="text" name="tanggal" id="tanggal" class="form-control"
                                        placeholder="dd/mm/yyyy" required style="border-radius:12px;">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label class="text-dark font-weight-bold">{{ __('Kuartal') }}</label>
                                    <input type="text" name="kuartal" id="kuartal" class="form-control" readonly
                                        style="border-radius:12px;">
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-label">
                            <label class="text-dark font-weight-bold">{{ __('Keterangan') }}</label>
                            <input type="text" name="keterangan" class="form-control" style="border-radius:12px;">
                        </div>

                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-success btn-round"
                                style="background:#29b14a;border:none;padding:8px 20px;">
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
    $('#id_sales, #id_asisten_manager').select2({
        placeholder: "-- Pilih --",
        allowClear: true,
        width: '100%'
    });

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

    $('#id_asisten_manager').on('change', function() {
        const selectedText = $(this).find('option:selected').text();
        const nama = selectedText.split('-').slice(1).join('-').trim();
        $('#nama_asisten_manager').val(nama);
    });

    $('#tanggal').datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showAnim: 'slideDown',
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

    @if(session('success'))
    $('#tanggal').val('');
    $('#kuartal').val('');
    $('#id_sales').val('').trigger('change');
    $('#id_asisten_manager').val('').trigger('change');
    @endif
});
</script>
@endpush
@endsection
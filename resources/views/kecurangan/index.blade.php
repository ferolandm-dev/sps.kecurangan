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

<<<<<<< HEAD
=======
                        {{-- ===================== BAGIAN SALES ===================== --}}
>>>>>>> recovery-branch
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
<<<<<<< HEAD
=======

>>>>>>> recovery-branch
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
<<<<<<< HEAD

                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label>{{ __('Toko') }}</label>
                                    <input type="text" name="toko" class="form-control" required>
=======
                        </div>

                        <hr class="my-4">

                        {{-- ===================== BAGIAN ASISTEN MANAGER ===================== --}}
                        <h6 class="heading-small text-muted mb-3">Detail Asisten Manager</h6>

                        {{-- ID Asisten Manager --}}
                        <div class="form-group">
                            <label for="id_asisten_manager">{{ __('ID Asisten Manager') }}</label>
                            <select name="id_asisten_manager" id="id_asisten_manager" class="form-control select2">
                                <option value="">-- Pilih Asisten Manager --</option>
                                {{-- opsi akan dimuat via AJAX --}}
                            </select>
                        </div>

                        {{-- Nama Asisten Manager --}}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label for="nama_asisten_manager">{{ __('Nama Asisten Manager') }}</label>
                                    <input type="text" id="nama_asisten_manager" name="nama_asisten_manager"
                                        class="form-control" readonly>
>>>>>>> recovery-branch
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

<<<<<<< HEAD
=======
                        {{-- ===================== BAGIAN KEJADIAN ===================== --}}
>>>>>>> recovery-branch
                        <h6 class="heading-small text-muted mb-3">Detail Kejadian</h6>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group has-label">
<<<<<<< HEAD
=======
                                    <label>{{ __('Toko') }}</label>
                                    <input type="text" name="toko" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group has-label">
>>>>>>> recovery-branch
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

<<<<<<< HEAD
=======
                            {{-- Kolom Kuartal Otomatis --}}
                            <div class="col-md-3">
                                <div class="form-group has-label">
                                    <label>{{ __('Kuartal') }}</label>
                                    <input type="text" name="kuartal" id="kuartal" class="form-control" readonly>
                                </div>
                            </div>
                        </div>

                        {{-- Kolom Keterangan --}}
                        <div class="row">
>>>>>>> recovery-branch
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
<<<<<<< HEAD

                    </form>
                </div> {{-- end card-body --}}
            </div> {{-- end card --}}
=======
                    </form>
                </div>
            </div>

>>>>>>> recovery-branch
        </div>
    </div>
</div>

@push('js')
<<<<<<< HEAD
{{-- Select2 untuk dropdown searchable --}}
=======
{{-- Select2 --}}
>>>>>>> recovery-branch
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- jQuery UI Datepicker --}}
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<script>
$(document).ready(function() {
<<<<<<< HEAD

    // --- Select2 ID Sales ---
    $('#id_sales').select2({
        placeholder: "-- Pilih ID Sales --",
=======
    $('#id_sales, #id_asisten_manager').select2({
        placeholder: "-- Pilih --",
>>>>>>> recovery-branch
        allowClear: false,
        width: '100%'
    });

<<<<<<< HEAD
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
=======
    // === Ambil data sales ===
    $('#id_sales').on('change', function() {
        const idSales = $(this).val();
        const $namaSales = $('#nama_sales');
        const $distributor = $('#distributor');
        const $idAsisten = $('#id_asisten_manager');
        const $namaAsisten = $('#nama_asisten_manager');

        // reset
        $namaSales.val('');
        $distributor.val('');
        $namaAsisten.val('');
        $idAsisten.html('<option value="">-- Pilih Asisten Manager --</option>');

        if (!idSales) return;

        $.ajax({
            url: `/kecurangan/sales/${idSales}`,
            type: 'GET',
            success: function(data) {
                $namaSales.val(data.nama_sales);
                $distributor.val(data.distributor);

                if (data.distributor_id) {
                    $.ajax({
                        url: `/kecurangan/asisten-manager/${data.distributor_id}`,
                        type: 'GET',
                        success: function(res) {
                            let options =
                                '<option value="">-- Pilih Asisten Manager --</option>';
                            res.forEach(am => {
                                options +=
                                    `<option value="${am.id}">${am.id} - ${am.nama}</option>`;
                            });
                            $idAsisten.html(options);
                        }
                    });
                }
>>>>>>> recovery-branch
            }
        });
    });

<<<<<<< HEAD
    // --- Datepicker untuk input tanggal ---
=======
    // === Saat memilih Asisten Manager ===
    $('#id_asisten_manager').on('change', function() {
        const selectedText = $(this).find('option:selected').text();
        const nama = selectedText.split('-').slice(1).join('-').trim();
        $('#nama_asisten_manager').val(nama);
    });

    // === Datepicker & Kuartal Otomatis ===
>>>>>>> recovery-branch
    $('#tanggal').datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
<<<<<<< HEAD
        showAnim: 'slideDown'
    }).attr('placeholder', 'dd/mm/yy'); // tampilkan placeholder dalam input

    // --- Kosongkan tanggal jika data tersimpan ---
    @if(session('success'))
    $('#tanggal').val('');
=======
        showAnim: 'slideDown',
        onSelect: function(dateText) {
            const [day, month, year] = dateText.split('/');
            const bulan = parseInt(month);
            let kuartal = '';

            if (bulan >= 1 && bulan <= 3) kuartal = 'Q1';
            else if (bulan >= 4 && bulan <= 6) kuartal = 'Q2';
            else if (bulan >= 7 && bulan <= 9) kuartal = 'Q3';
            else if (bulan >= 10 && bulan <= 12) kuartal = 'Q4';

            $('#kuartal').val(kuartal);
        }
    });

    // Kosongkan tanggal kalau sukses simpan
    @if(session('success'))
    $('#tanggal').val('');
    $('#kuartal').val('');
>>>>>>> recovery-branch
    @endif
});
</script>
@endpush

<<<<<<< HEAD

=======
>>>>>>> recovery-branch
@endsection
@extends('layouts.app', [
'namePage' => 'Data Kecurangan',
'class' => 'sidebar-mini',
'activePage' => 'data_kecurangan',
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
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-lg" role="alert" style=" background: linear-gradient(135deg, #29b14a 0%, #34d058 100%); color: #fff;
                border: none;
                border-radius: 14px;
                padding: 14px 18px;
                font-weight: 500;
                letter-spacing: 0.3px;
                box-shadow: 0 4px 12px rgba(41,177,74,0.3);
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 25px;">
                <div class="d-flex align-items-center">
                    <i class="now-ui-icons ui-1_bell-53 mr-2" style="font-size:18px;"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="
                    color:#fff;
                    opacity:0.8;
                    font-size:22px;
                    margin-left:10px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif


            {{-- ✅ CARD DATA KECURANGAN --}}
            <div class="card" style="border-radius: 20px;">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="card-title mb-0 text-dark">{{ __('Data Kecurangan') }}</h4>

                    <div class="d-flex align-items-center flex-wrap gap-2">
                        {{-- Tombol Tambah Distributor --}}
                        @if (checkAccess('Master', 'Master Kecurangan', 'create'))
                        <a href="{{ route('kecurangan.index') }}" class="btn btn-primary btn-icon btn-round"
                            style="background:#29b14a;border:none; margin-right: 10px;" title="Tambah Distributor">
                            <i class="now-ui-icons ui-1_simple-add"></i>
                        </a>
                        @endif
                        {{-- 🔍 Form Pencarian --}}
                        <form action="{{ route('kecurangan.data') }}" method="GET"
                            class="d-flex align-items-center mr-2" style="margin-top: 10px;">
                            <div class="input-group" style="width:250px;">
                                <input type="text" name="search" class="form-control" placeholder="Cari kecurangan..."
                                    value="{{ request('search') }}"
                                    style="height:38px;border-radius:30px 0 0 30px;padding-left:15px;margin-top:10px;">
                                <div class="input-group-append">
                                    <button class="btn btn-success btn-round" type="submit"
                                        style="height:38px;border-radius:0 30px 30px 0;background:#29b14a;border:none;">
                                        <i class="now-ui-icons ui-1_zoom-bold"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                        {{-- 🔄 Tombol Tampilkan Semua / Halaman --}}
                        @if (request()->has('all'))
                        <a href="{{ route('kecurangan.data', request()->except('all')) }}"
                            class="btn btn-warning btn-round mr-2"
                            style="background:#eee733;color:#000;border:none;margin-top:10px;"
                            title="Tampilkan Halaman">
                            <i class="now-ui-icons arrows-1_refresh-69"></i> Tampilkan Halaman
                        </a>
                        @else
                        <a href="{{ route('kecurangan.data', array_merge(request()->query(), ['all' => true])) }}"
                            class="btn btn-success btn-round mr-2"
                            style="background:#29b14a;border:none;margin-top:10px;" title="Tampilkan Semua Data">
                            <i class="now-ui-icons ui-1_zoom-bold"></i> Tampilkan Semua
                        </a>
                        @endif

                        {{-- ✅ Export Excel & PDF --}}
                        @if (checkAccess('Data', 'Data Kecurangan', 'print'))
                        <a href="{{ route('kecurangan.exportExcel') }}"
                            class="btn btn-success btn-round mr-2 d-flex align-items-center"
                            style="margin-top:10px;background:#29b14a;border:none;" title="Export Excel">
                            <i class="now-ui-icons files_single-copy-04 mr-1"></i> Excel
                        </a>

                        <a href="{{ route('kecurangan.exportPDF') }}"
                            class="btn btn-danger btn-round d-flex align-items-center"
                            style="margin-top:10px;background:#e74c3c;border:none;" title="Export PDF">
                            <i class="now-ui-icons files_paper mr-1"></i> PDF
                        </a>
                        @endif
                    </div>
                </div>

                {{-- 📅 FILTER TANGGAL --}}
                <div class="card-header d-flex align-items-center flex-wrap" style="gap: 10px;">
                    <form action="{{ route('kecurangan.data') }}" method="GET"
                        class="d-flex align-items-center flex-wrap" style="gap:10px; margin-top:10px;">
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}"
                            style="width:200px; height:38px;" title="Tanggal Mulai">
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}"
                            style="width:200px; height:38px;" title="Tanggal Akhir">

                        <button type="submit" class="btn btn-success btn-round"
                            style="height:38px;background:#29b14a;border:none;" title="Filter">
                            <i class="now-ui-icons ui-1_zoom-bold"></i> Filter
                        </button>

                        <a href="{{ route('kecurangan.data') }}" class="btn btn-secondary btn-round"
                            style="height:38px;" title="Reset Filter">
                            <i class="now-ui-icons arrows-1_refresh-69"></i> Reset
                        </a>
                    </form>
                </div>

                {{-- 📋 TABEL DATA --}}
                <div class="card-body" style="background: rgba(255,255,255,0.5); border-radius: 0 0 20px 20px;">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0"
                            style="color:#333; table-layout:auto; width:100%; border-collapse:separate; border-spacing:0 4px;">

                            <table class="table table-hover text-nowrap align-middle mb-0" style="color:#333;">
                                <thead style="color:#29b14a;">
                                    <tr>
                                        <th style="text-align:center;">#</th>
                                        <th>
                                            <a href="{{ route('kecurangan.data', array_merge(request()->query(), [
                                            'sort_by' => 'id_sales',
                                            'sort_order' => (request('sort_by') === 'id_sales' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                                        ])) }}" class="text-success text-decoration-none">
                                                ID Sales
                                            </a>
                                        </th>
                                        <th>
                                            <a href="{{ route('kecurangan.data', array_merge(request()->query(), [
                                            'sort_by' => 'nama_sales',
                                            'sort_order' => (request('sort_by') === 'nama_sales' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                                        ])) }}" class="text-success text-decoration-none">
                                                Nama Sales
                                            </a>
                                        </th>
                                        <th>Distributor</th>
                                        <th>Nama ASS</th>
                                        <th>Toko</th>
                                        <th class="text-center">Kunjungan</th>
                                        <th>
                                            <a href="{{ route('kecurangan.data', array_merge(request()->query(), [
                                            'sort_by' => 'tanggal',
                                            'sort_order' => (request('sort_by') === 'tanggal' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                                        ])) }}" class="text-success text-decoration-none">
                                                Tanggal
                                            </a>
                                        </th>
                                        <th style="min-width: 400px; width: 45%;">Keterangan</th>
                                        <th>Kuartal</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($kecurangan as $index => $item)
                                    <tr style="vertical-align: top;">
                                        <td style="text-align:center; vertical-align: top;">
                                            {{ $loop->iteration + (method_exists($kecurangan, 'firstItem') ? $kecurangan->firstItem() - 1 : 0) }}
                                        </td>
                                        <td style="vertical-align: top;">{{ $item->id_sales }}</td>
                                        <td style="vertical-align: top;">{{ $item->nama_sales }}</td>
                                        <td style="vertical-align: top;">{{ $item->distributor }}</td>
                                        <td style="vertical-align: top;">{{ $item->nama_asisten_manager }}</td>
                                        <td style="vertical-align: top;">{{ $item->toko }}</td>
                                        <td style="text-align:center; vertical-align: top;">{{ $item->kunjungan }}</td>
                                        <td style="vertical-align: top;">
                                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>

                                        <td
                                            style="white-space: normal; word-wrap: break-word; text-align: justify; line-height: 1.5em; width: 45%; max-width: 600px; vertical-align: top;">
                                            {{ $item->keterangan ?? '-' }}
                                        </td>

                                        <td style="vertical-align: top;">{{ $item->kuartal }}</td>
                                        <td class="text-center" style="vertical-align: top;">
                                            <button class="btn btn-info btn-icon btn-sm btn-round btn-lihat-bukti"
                                                data-id="{{ $item->id }}" title="Lihat Bukti"
                                                style="background:#17a2b8;border:none;">
                                                <i class="now-ui-icons media-1_album"></i>
                                            </button>
                                            @if($item->validasi == 0)
                                            <form action="{{ route('kecurangan.validasi', $item->id) }}" method="POST"
                                                style="display:inline-block;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-icon btn-sm btn-round"
                                                    title="Validasi Data"
                                                    onclick="return confirm('Validasi data ini?');"
                                                    style="background:#29b14a;border:none;">
                                                    <i class="now-ui-icons ui-1_check"></i>
                                                </button>
                                            </form>

                                            <a href="{{ route('kecurangan.edit', $item->id) }}"
                                                class="btn btn-warning btn-icon btn-sm btn-round" title="Edit Data"
                                                style="background:#f39c12;border:none;">
                                                <i class="now-ui-icons ui-2_settings-90"></i>
                                            </a>

                                            <form action="{{ route('kecurangan.destroy', $item->id) }}" method="POST"
                                                style="display:inline-block;"
                                                onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-icon btn-sm btn-round"
                                                    title="Hapus Data" style="background:#e74c3c;border:none;">
                                                    <i class="now-ui-icons ui-1_simple-remove"></i>
                                                </button>
                                            </form>
                                            @else
                                            <span class="badge"
                                                style="background:#29b14a;color:white;border-radius:10px;padding:6px 10px;">
                                                Tervalidasi
                                            </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="11" class="text-center text-muted">Belum ada data kecurangan</td>
                                    </tr>
                                    @endforelse
                                </tbody>

                            </table>
                    </div>

                    {{-- 📄 Pagination --}}
                    @if (!request()->has('all'))
                    <div class="d-flex justify-content-center mt-3">
                        {{ $kecurangan->links('pagination::bootstrap-4') }}
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
{{-- ===================== MODAL LIHAT BUKTI ===================== --}}
<div class="modal fade" id="modalBukti" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:1000px;">
        <div class="modal-content border-0" style="background:rgba(255,255,255,0.98);
                border-radius:15px;
                position:relative;
                box-shadow:0 4px 25px rgba(0,0,0,0.3);
                display:flex;
                align-items:center;
                justify-content:center;
                overflow:hidden;">

            {{-- Tombol Tutup --}}
            <button type="button" id="modalCloseBtn" style="
        position:absolute;top:10px;right:20px;
        font-size:32px;background:none;border:none;
        cursor:pointer;z-index:2102;">&times;</button>

            {{-- Tombol Navigasi --}}
            <button type="button" id="modalPrev" style="
        position:absolute;left:20px;top:50%;
        transform:translateY(-50%);
        font-size:40px;background:none;border:none;
        cursor:pointer;z-index:2102;">‹</button>

            <button type="button" id="modalNext" style="
        position:absolute;right:20px;top:50%;
        transform:translateY(-50%);
        font-size:40px;background:none;border:none;
        cursor:pointer;z-index:2102;">›</button>

            {{-- Isi Modal --}}
            <div class="d-flex justify-content-center align-items-center" style="height:80vh;">
                <img id="modalImage" src="" alt="Preview"
                    style="max-width:90%;max-height:90%;object-fit:contain;border-radius:10px;">
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
$(document).ready(function() {
    let fotoList = [];
    let currentIndex = 0;

    // Klik tombol lihat bukti
    $('.btn-lihat-bukti').on('click', function() {
        const id = $(this).data('id');
        fotoList = [];
        currentIndex = 0;

        $('#modalBukti').modal({
            backdrop: false,
            keyboard: true,
            show: true
        });

        $.ajax({
            url: `/kecurangan/${id}/bukti`,
            method: 'GET',
            beforeSend: function() {
                $('#modalImage').attr('src', '').attr('alt', 'Memuat...');
            },
            success: function(response) {
                if (!response.length) {
                    $('#modalImage').attr('alt', 'Tidak ada foto.');
                    return;
                }
                fotoList = response.map(f => f.url);
                showModalImage(currentIndex);
            },
            error: function() {
                $('#modalImage').attr('alt', 'Gagal memuat foto.');
            }
        });
    });

    function showModalImage(index) {
        if (!fotoList.length) return;
        $('#modalImage').attr('src', fotoList[index]);
    }

    $('#modalNext').on('click', function() {
        if (!fotoList.length) return;
        currentIndex = (currentIndex + 1) % fotoList.length;
        showModalImage(currentIndex);
    });

    $('#modalPrev').on('click', function() {
        if (!fotoList.length) return;
        currentIndex = (currentIndex - 1 + fotoList.length) % fotoList.length;
        showModalImage(currentIndex);
    });

    $('#modalCloseBtn').on('click', function() {
        $('#modalBukti').modal('hide');
    });

    $(document).on('keydown', function(e) {
        if (!$('#modalBukti').hasClass('show')) return;
        if (e.key === 'Escape') $('#modalBukti').modal('hide');
        else if (e.key === 'ArrowRight') $('#modalNext').trigger('click');
        else if (e.key === 'ArrowLeft') $('#modalPrev').trigger('click');
    });

    $('#modalBukti').on('hidden.bs.modal', function() {
        $('#modalImage').attr('src', '');
        fotoList = [];
        currentIndex = 0;
    });

    // FIX posisi modal agar tidak ikut scroll
    $('#modalBukti').on('shown.bs.modal', function() {
        $('body').css('overflow', 'hidden');
    }).on('hidden.bs.modal', function() {
        $('body').css('overflow', 'auto');
    });
});
</script>
@endpush
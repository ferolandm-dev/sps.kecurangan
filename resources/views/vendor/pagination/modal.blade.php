@if ($paginator->hasPages())
<nav class="mt-2">
    <ul class="pagination pagination-sm justify-content-center" style="gap:4px;">

        {{-- First --}}
        <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link modal-page" href="{{ $paginator->url(1) }}">&laquo;</a>
        </li>

        {{-- Prev --}}
        <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link modal-page" href="{{ $paginator->previousPageUrl() }}">&lsaquo;</a>
        </li>

        @php
        $total = $paginator->lastPage();
        $current = $paginator->currentPage();
        $maxPages = 5;

        $start = max(1, $current - floor($maxPages / 2));
        $end = min($total, $start + $maxPages - 1);
        @endphp

        @for ($i = $start; $i <= $end; $i++) <li class="page-item {{ $i == $current ? 'active' : '' }}">
            <a class="page-link modal-page" href="{{ $paginator->url($i) }}">{{ $i }}</a>
            </li>
            @endfor

            {{-- Next --}}
            <li class="page-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
                <a class="page-link modal-page" href="{{ $paginator->nextPageUrl() }}">&rsaquo;</a>
            </li>

            {{-- Last --}}
            <li class="page-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
                <a class="page-link modal-page" href="{{ $paginator->url($paginator->lastPage()) }}">&raquo;</a>
            </li>

    </ul>
</nav>
@endif
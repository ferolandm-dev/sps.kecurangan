@if ($paginator->hasPages())
<nav>
    <ul class="pagination justify-content-center">

        {{-- Go to First Page («) --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">&laquo;</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->url(1) }}" rel="first">&laquo;</a>
            </li>
        @endif


        {{-- Previous Arrow (<) --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">&lsaquo;</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a>
            </li>
        @endif


        @php
            // Selalu tampilkan 7 halaman
            $total = $paginator->lastPage();
            $current = $paginator->currentPage();
            $maxPages = 5;

            // Hitung start
            $start = max(1, $current - floor($maxPages / 2));

            // Hitung end
            $end = $start + $maxPages - 1;
            if ($end > $total) {
                $end = $total;
                $start = max(1, $end - $maxPages + 1);
            }
        @endphp

        {{-- Numbered Pages --}}
        @for ($i = $start; $i <= $end; $i++)
            @if ($i == $current)
                <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
            @endif
        @endfor


        {{-- Next Arrow (>) --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">&rsaquo;</span>
            </li>
        @endif


        {{-- Go to Last Page (») --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}" rel="last">&raquo;</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">&raquo;</span>
            </li>
        @endif

    </ul>
</nav>
@endif

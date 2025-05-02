@if ($paginator->hasPages())
    <div class="pagination pagination-rounded mx-3 mt-4 d-flex justify-content-center">
        <ul class="pagination pagination-md">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link rounded-circle">‹</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link rounded-circle" href="{{ $paginator->previousPageUrl() }}" rel="prev">‹</a>
                </li>
            @endif

            {{-- Page Numbers --}}
            @php
                $current = $paginator->currentPage();
                $last = $paginator->lastPage();

                $start = max($current - 1, 1);
                $end = min($start + 2, $last);

                if ($end - $start < 2) {
                    $start = max($end - 2, 1);
                }
            @endphp

            @for ($i = $start; $i <= $end; $i++)
                @if ($i == $current)
                    <li class="page-item active">
                        <span class="page-link rounded-circle">{{ $i }}</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link rounded-circle" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                    </li>
                @endif
            @endfor

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link rounded-circle" href="{{ $paginator->nextPageUrl() }}" rel="next">›</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link rounded-circle">›</span>
                </li>
            @endif
        </ul>
    </div>
@endif

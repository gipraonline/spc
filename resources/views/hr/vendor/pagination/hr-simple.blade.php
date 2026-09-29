@if ($paginator->hasPages())
<nav class="hr-pagination" role="navigation" aria-label="Pagination">
    <span class="hr-pagination-summary">
        Showing {{ $paginator->firstItem() }}&ndash;{{ $paginator->lastItem() }} of {{ $paginator->total() }}
    </span>
    <span class="hr-pagination-links">
        @if ($paginator->onFirstPage())
            <span class="hr-page-btn disabled"><i class="fa-solid fa-angle-left"></i></span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="hr-page-btn" rel="prev"><i class="fa-solid fa-angle-left"></i></a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="hr-page-btn ellipsis">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="hr-page-btn active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="hr-page-btn">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="hr-page-btn" rel="next"><i class="fa-solid fa-angle-right"></i></a>
        @else
            <span class="hr-page-btn disabled"><i class="fa-solid fa-angle-right"></i></span>
        @endif
    </span>
</nav>
@endif

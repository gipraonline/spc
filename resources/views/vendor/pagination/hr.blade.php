@if ($paginator->hasPages())
    @once
        <style>
            .hr-pager-list {
                display: flex;
                align-items: center;
                justify-content: center;
                flex-wrap: wrap;
                gap: 6px;
                list-style: none;
                margin: 0;
                padding: 0;
            }
            .hr-pager-list li {
                margin: 0;
                padding: 0;
                list-style: none;
            }
            .hr-pager-list a,
            .hr-pager-list span {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 36px;
                height: 36px;
                padding: 0 10px;
                border: 1px solid #e5e7eb;
                border-radius: 999px;
                font-size: 13px;
                font-weight: 600;
                color: #0f4d2e;
                background: #fff;
                text-decoration: none;
                transition: background .15s;
            }
            .hr-pager-list a:hover {
                background: #f0f5f2;
            }
            .hr-pager-list .active span {
                background: #0f4d2e;
                border-color: #0f4d2e;
                color: #fff;
            }
            .hr-pager-list .disabled span {
                color: #b8bfc6;
                background: #f9fafb;
                cursor: not-allowed;
            }
        </style>
    @endonce

    <nav role="navigation" aria-label="Pagination">
        <ul class="hr-pager-list">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li class="disabled"><span>&lsaquo;</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a></li>
            @endif

            {{-- Page numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="disabled"><span>{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a></li>
            @else
                <li class="disabled"><span>&rsaquo;</span></li>
            @endif

        </ul>
    </nav>
@endif
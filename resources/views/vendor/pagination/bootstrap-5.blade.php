@if ($paginator->hasPages())
    <nav class="pagination-wrapper">
        <div class="pagination-container">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="pagination-btn pagination-btn-disabled">
                    <i class="bi bi-chevron-left"></i> Previous
                </span>
            @else
                <a class="pagination-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                    <i class="bi bi-chevron-left"></i> Previous
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="pagination-dots">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="pagination-btn pagination-btn-active">{{ $page }}</span>
                        @else
                            <a class="pagination-btn" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a class="pagination-btn" href="{{ $paginator->nextPageUrl() }}" rel="next">
                    Next <i class="bi bi-chevron-right"></i>
                </a>
            @else
                <span class="pagination-btn pagination-btn-disabled">
                    Next <i class="bi bi-chevron-right"></i>
                </span>
            @endif
        </div>
    </nav>
@endif

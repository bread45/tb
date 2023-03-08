<div class="row">
    <div class="col-12">
        <nav aria-label="Page navigation">    
            @if ($paginator->hasPages())
            <ul class="pagination justify-content-center">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <a class="page-link prev-link" href="#" tabindex="-1" aria-disabled="true">Prev</a>
                </li>
                @else
                <li class="page-item"><a class="page-link prev-link" href="{{ $paginator->previousPageUrl() }}" href="#" tabindex="-1" aria-disabled="true"> Previous</a></li>
                @endif
                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                <li class="page-item active"><span>{{ $element }}</span></li>
                @endif
                {{-- Array Of Links --}}
                @if (is_array($element))
                @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                <li class="page-item active"><span><a class="page-link">{{ $page }}</a></span></li>
                @else
                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                @endif
                @endforeach
                @endif
                @endforeach
                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                <li class="page-item"><a href="{{ $paginator->nextPageUrl() }}" class="page-link next-link">Next</a></li>
                @else
                <li class="page-item disabled"><span><a class="page-link next-link" href="#">Next</a></span></li>
                @endif
            </ul>
            @endif
        </nav>
    </div>
</div>
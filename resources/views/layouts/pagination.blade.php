@if ($paginator->hasPages())

    <nav class="pagination">
        <ul class="page-numbers">
            @if ($paginator->onFirstPage())
            <li><a class="prev page-numbers">Önceki</a></li>
            @else
                <li><a class="prev page-numbers" href="{{ $paginator->previousPageUrl() }}">Önceki</a></li>
            @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li><span aria-current="page" class="page-numbers current">{{ $element }}</span></li>
                    @endif
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                    <li><span aria-current="page" class="page-numbers current">{{ $page }}</span></li>
                            @else
                                    <li><a class="page-numbers" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <li><a class="next page-numbers" href="{{ $paginator->nextPageUrl() }}">Sonraki</a></li>
                @else
                    <li><a class="next page-numbers">Sonraki</a></li>
                @endif
        </ul>
    </nav>
@endif

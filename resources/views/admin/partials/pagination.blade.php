@if ($paginator->hasPages())
    <ul class="ad-pager">
        <li class="{{ $paginator->onFirstPage() ? 'disabled' : '' }}"><a href="{{ $paginator->previousPageUrl() ?? '#' }}" aria-label="Previous"><i class="fa fa-angle-left"></i></a></li>
        @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
            <li class="{{ $page === $paginator->currentPage() ? 'active' : '' }}"><a href="{{ $url }}">{{ $page }}</a></li>
        @endforeach
        <li class="{{ $paginator->hasMorePages() ? '' : 'disabled' }}"><a href="{{ $paginator->nextPageUrl() ?? '#' }}" aria-label="Next"><i class="fa fa-angle-right"></i></a></li>
    </ul>
@endif

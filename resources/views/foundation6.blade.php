@unless ($breadcrumbs->isEmpty())
    <nav aria-label="You are here:" role="navigation">
        <ul class="breadcrumbs" itemscope itemtype="https://schema.org/BreadcrumbList">
            @php $i = 1; @endphp
            @foreach ($breadcrumbs as $breadcrumb)

                @if ($loop->last)
                    <li class="current"><span class="show-for-sr">Current:</span> {{ $breadcrumb->title }}<meta itemprop="position" content="{{ $i }}" /></li>
                @elseif ($breadcrumb->url)
                    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="{{ $breadcrumb->url }}"><span itemprop="name">{{ $breadcrumb->title }}</span></a><meta itemprop="position" content="{{ $i }}" /></li>
                @else
                    <li class="disabled"><span itemprop="name">{{ $breadcrumb->title }}</span><meta itemprop="position" content="{{ $i }}" /></li>
                @endif
                @php $i++; @endphp
            @endforeach
        </ul>
    </nav>
@endunless

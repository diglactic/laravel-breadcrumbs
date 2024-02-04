@unless ($breadcrumbs->isEmpty())
    <nav class="breadcrumb" aria-label="breadcrumbs">
        <ul itemscope itemtype="https://schema.org/BreadcrumbList">
            @foreach ($breadcrumbs as $breadcrumb)
            @php $i = 1; @endphp
                @if ($loop->last)
                    @if ($breadcrumb->url)
                        <li class="is-active" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="{{ $breadcrumb->url }}" aria-current="page"><span itemprop="name">{{ $breadcrumb->title }}</span></a><meta itemprop="position" content="{{ $i }}" /></li>
                    @else
                        <li class="is-active"><a aria-current="page"><span itemprop="name">{{ $breadcrumb->title }}</span></a><meta itemprop="position" content="{{ $i }}" /></li>
                    @endif
                @else
                    @if ($breadcrumb->url)
                        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="{{ $breadcrumb->url }}"><span itemprop="name">{{ $breadcrumb->title }}</span></a><meta itemprop="position" content="{{ $i }}" /></li>
                    @else
                        <li class="is-active"><a itemprop="item"><span itemprop="name">{{ $breadcrumb->title }}</span></a><meta itemprop="position" content="{{ $i }}" /></li>
                    @endif
                @endif
                @php $i++; @endphp
            @endforeach
        </ul>
    </nav>
@endunless

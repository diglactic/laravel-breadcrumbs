@unless ($breadcrumbs->isEmpty())
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
            @php $i = 1; @endphp
            @foreach ($breadcrumbs as $breadcrumb)

                @if ($breadcrumb->url && !$loop->last)
                    <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="{{ $breadcrumb->url }}"><span itemprop="name">{{ $breadcrumb->title }}</span></a><meta itemprop="position" content="{{ $i }}" /></li>
                @else
                    <li class="breadcrumb-item active" aria-current="page"><span itemprop="name">{{ $breadcrumb->title }}</span><meta itemprop="position" content="{{ $i }}" /></li>
                @endif
                @php $i++; @endphp
            @endforeach
        </ol>
    </nav>
@endunless

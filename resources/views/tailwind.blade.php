@unless ($breadcrumbs->isEmpty())
    <nav class="container mx-auto">
        <ol class="p-4 rounded flex flex-wrap bg-gray-300 text-sm text-gray-800" itemscope itemtype="https://schema.org/BreadcrumbList">
            @php $i = 1; @endphp
            @foreach ($breadcrumbs as $breadcrumb)

                @if ($breadcrumb->url && !$loop->last)
                    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <a itemprop="item" href="{{ $breadcrumb->url }}" class="text-blue-600 hover:text-blue-900 hover:underline focus:text-blue-900 focus:underline">
                            <span itemprop="name">{{ $breadcrumb->title }}</span>
                        </a>
                        <meta itemprop="position" content="{{ $i }}" />
                    </li>
                @else
                    <li>
                        <span itemprop="name">{{ $breadcrumb->title }}</span><meta itemprop="position" content="{{ $i }}" />
                    </li>
                @endif

                @unless($loop->last)
                    <li class="text-gray-500 px-2">
                        /
                    </li>
                @endif
                @php $i++; @endphp
            @endforeach
        </ol>
    </nav>
@endunless

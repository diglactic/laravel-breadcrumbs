@if (count($breadcrumbs))
    <nav class="container-fluid px-1">
        <ol class="list-reset py-4 pl-4 rounded flex bg-gray-300 text-gray-800">
            @foreach ($breadcrumbs as $breadcrumb)
                @if ($breadcrumb->url && !$loop->last)
                    <li class="px-2"><a href="{{ $breadcrumb->url }}" class="no-underline text-blue-600">{{ $breadcrumb->title }}</a></li>
                @else
                    <li class="px-2">{{ $breadcrumb->title }}</li>
                @endif
                @if(!$loop->last)
                    <li class="text-gray-500">/</li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif



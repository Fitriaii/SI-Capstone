@if ($paginator->hasPages())
    <nav class="flex items-center justify-center mt-2 space-x-1">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-2 py-1 text-sm text-gray-500 bg-gray-200 border border-gray-300 rounded cursor-not-allowed">
                &laquo;
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="px-2 py-1 text-sm text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-100">
                &laquo;
            </a>
        @endif

        {{-- First Page --}}
        @if ($paginator->currentPage() > 3)
            <a href="{{ $paginator->url(1) }}"
               class="px-2 py-1 text-sm text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-100">
                1
            </a>
        @endif

        {{-- Ellipsis for middle pages --}}
        @if ($paginator->currentPage() > 4)
            <span class="px-2 py-1 text-sm text-gray-500 bg-white border border-gray-300 rounded cursor-not-allowed">...</span>
        @endif

        {{-- Page Links --}}
        @foreach ($elements as $element)
            {{-- Array of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    {{-- If page is active --}}
                    @if ($page == $paginator->currentPage())
                        <span class="px-2 py-1 text-sm text-white bg-blue-500 border border-blue-500 rounded">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           class="px-2 py-1 text-sm text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-100">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Ellipsis for middle pages --}}
        @if ($paginator->currentPage() < $paginator->lastPage() - 2)
            <span class="px-2 py-1 text-sm text-gray-500 bg-white border border-gray-300 rounded cursor-not-allowed">...</span>
        @endif

        {{-- Last Page --}}
        @if ($paginator->currentPage() < $paginator->lastPage() - 2)
            <a href="{{ $paginator->url($paginator->lastPage()) }}"
               class="px-2 py-1 text-sm text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-100">
                {{ $paginator->lastPage() }}
            </a>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="px-2 py-1 text-sm text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-100">
                &raquo;
            </a>
        @else
            <span class="px-2 py-1 text-sm text-gray-500 bg-gray-200 border border-gray-300 rounded cursor-not-allowed">
                &raquo;
            </span>
        @endif
    </nav>
@endif

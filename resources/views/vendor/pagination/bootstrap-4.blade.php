@if ($paginator->hasPages())
    <nav aria-label="分頁導覽">
        <ul class="pagination justify-content-center">
            {{-- 上一頁 --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" aria-hidden="true">&laquo; 上一頁</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" 
                       href="{{ $paginator->previousPageUrl() }}" 
                       rel="prev" 
                       title="前往上一頁 (第 {{ $paginator->currentPage() - 1 }} 頁)"
                       aria-label="前往上一頁 (第 {{ $paginator->currentPage() - 1 }} 頁)">
                        &laquo; 上一頁
                    </a>
                </li>
            @endif

            {{-- 數字頁碼 --}}
            @foreach ($elements as $element)
                {{-- 省略號 "..." --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- 數字頁碼陣列 --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link" title="目前在第 {{ $page }} 頁">
                                    {{ $page }}
                                    <span class="sr-only">(目前在第 {{ $page }} 頁)</span>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" 
                                   href="{{ $url }}" 
                                   title="前往第 {{ $page }} 頁" 
                                   aria-label="前往第 {{ $page }} 頁">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- 下一頁 --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" 
                       href="{{ $paginator->nextPageUrl() }}" 
                       rel="next" 
                       title="前往下一頁 (第 {{ $paginator->currentPage() + 1 }} 頁)"
                       aria-label="前往下一頁 (第 {{ $paginator->currentPage() + 1 }} 頁)">
                        下一頁 &raquo;
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" aria-hidden="true">下一頁 &raquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif

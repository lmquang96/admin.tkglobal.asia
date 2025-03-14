<div class="mt-4">
  <nav aria-label="Page navigation example" style="display: flex;">
    <ul class="pagination">
      @if (!$paginator->onFirstPage())
      <li class="page-item"><a class="page-link" href="{{ $paginator->url(1) }}"><i class="mdi mdi-chevron-double-left"></i></a></li>
      <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}"><i class="mdi mdi-chevron-left"></i></a></li>
      @else
      <li class="page-item not-active" style="opacity: 0.5;"><a class="page-link" href="javascript:void(0);" style="cursor: not-allowed;"><i class="mdi mdi-chevron-double-left"></i></a></li>
      <li class="page-item not-active" style="opacity: 0.5;"><a class="page-link" href="javascript:void(0);" style="cursor: not-allowed;"><i class="mdi mdi-chevron-left"></i></a></li>
      @endif
      @if ($paginator->currentPage() > 3)
      <li class="page-item">
        <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
      </li>
      <span class="align-self-center ms-2 me-2">...</span>
      @endif
      @foreach (range(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page)
      <li class="page-item{{ $page == $paginator->currentPage() ? ' active' : '' }}"><a class="page-link" href="{{ $page == $paginator->currentPage() ? 'javascript:void(0);' : $paginator->url($page) }}">{{ $page }}</a></li>
      @endforeach
      @if ($paginator->currentPage() < $paginator->lastPage() - 2)
      <span class="align-self-center ms-2 me-2">...</span>
      <li class="page-item">
        <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
      </li>
      @endif
      @if ($paginator->hasMorePages())
      <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}"><i class="mdi mdi-chevron-right"></i></a></li>
      <li class="page-item"><a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}"><i class="mdi mdi-chevron-double-right"></i></a></li>
      @else
      <li class="page-item not-active" style="opacity: 0.5;"><a class="page-link" href="javascript:void(0);" style="cursor: not-allowed;"><i class="mdi mdi-chevron-right"></i></a></li>
      <li class="page-item not-active" style="opacity: 0.5;"><a class="page-link" href="javascript:void(0);" style="cursor: not-allowed;"><i class="mdi mdi-chevron-double-right"></i></a></li>
      @endif
    </ul>
    @php
    $lastItemInPage = $paginator->currentPage() * $paginator->perPage();
    if ($lastItemInPage > $paginator->total()) {
        $lastItemInPage = $paginator->total();
    }
  @endphp
  <div class="ms-2 align-self-start align-self-md-center">(Kết quả từ
    {{ ($paginator->currentPage() - 1) * $paginator->perPage() + 1 }} - {{ $lastItemInPage }} trên tổng
    {{ $paginator->total() }})</div>
  </nav>
</div>
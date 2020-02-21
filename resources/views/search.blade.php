@extends('layouts.base')

@if(request('keyword'))
  @section('title', sprintf('%s | BookWalker 探索號', request('keyword')))
@endif

@section('main')
  <input
    class="d-none"
    id="dialog-toggle"
    type="checkbox"
  >

  <dialog
    open
    class="fixed-top vw-100 vh-100 border-0 advanced-search"
  >
    <label class="fixed-top w-100 h-100 advanced-search-wrapper" for="dialog-toggle"></label>
    <section class="h-100 mx-auto overflow-auto card advanced-search-container">
      <article class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">進階搜尋</h5>

        <label
          class="btn btn-light"
          for="dialog-toggle"
          type="button"
        >
          <svg viewBox="0 0 24 24">
            <path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
          </svg>
        </label>
      </article>

      <article class="card-body">
        <div class="alert alert-primary" role="alert">
          <span>因伺服器資源有限無法呈現所有清單，當遇到列表遺漏之狀況，請嘗試使用更精確的關鍵字搜尋</span>
        </div>

        <button
          class="mb-3 btn btn-block btn-success"
          form="search"
          type="submit"
        >
          送出
        </button>

        @include('form.advanced-search', [
          'name' => 'types',
          'label' => '書籍類型',
          'pluck' => 'type.name',
        ])

        @include('form.advanced-search', [
          'name' => 'categories',
          'label' => '分類',
          'pluck' => 'category.name',
        ])

        @include('form.advanced-search', [
          'name' => 'authors',
          'label' => '作者',
          'pluck' => 'authors.*.name',
        ])

        @include('form.advanced-search', [
          'name' => 'writers',
          'label' => '原著',
          'pluck' => 'writers.*.name',
        ])

        @include('form.advanced-search', [
          'name' => 'illustrators',
          'label' => '插畫',
          'pluck' => 'illustrators.*.name',
        ])

        @include('form.advanced-search', [
          'name' => 'translators',
          'label' => '譯者',
          'pluck' => 'translators.*.name',
        ])

        @include('form.advanced-search', [
          'name' => 'publishers',
          'label' => '出版社',
          'pluck' => 'publisher.name',
        ])

        @include('form.advanced-search', [
          'name' => 'tags',
          'label' => '標籤',
          'pluck' => 'tags.*.name',
        ])

        <button
          class="mb-3 btn btn-block btn-success"
          form="search"
          type="submit"
        >
          送出
        </button>
      </article>
    </section>
  </dialog>

  @include('form.form')

  @include('components.books')
@endsection

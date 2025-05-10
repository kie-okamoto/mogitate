<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>商品検索ページ</title>
  <link rel="stylesheet" href="{{ asset('css/products/sanitize.css') }}">
  <link rel="stylesheet" href="{{ asset('css/products/search.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital@1&display=swap" rel="stylesheet">
</head>

<body>

  <header class="header">
    <div class="header__inner">
      <div class="header__logo">mogitate</div>
    </div>
  </header>

  <main class="main">
    <div class="product-search">
      <h2 class="product-search__title">商品を検索する</h2>

      <form action="{{ route('products.search') }}" method="GET" class="search-form">
        <div class="form-group">
          <label>商品名で検索</label>
          <input type="text" name="keyword" placeholder="商品名を入力" value="{{ request('keyword') }}">
        </div>

        <div class="form-group">
          <label>価格順で並び替え</label>
          <select name="sort">
            <option value="">選択してください</option>
            <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>価格が安い順</option>
            <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>価格が高い順</option>
          </select>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn--yellow">検索する</button>
          <a href="{{ route('products.index') }}" class="btn btn--gray">戻る</a>
        </div>
      </form>

      @if(isset($products))
      <div class="product-search__results">
        <h3>検索結果</h3>

        @if($products->isEmpty())
        <p>該当する商品はありませんでした。</p>
        @else
        <div class="product-search__list">
          @foreach($products as $product)
          <div class="product-card">
            <a href="{{ route('products.show', $product->id) }}">
              <img src="{{ asset('storage/images/' . $product->image) }}" alt="{{ $product->name }}" class="product-card__image">
              <p class="product-card__name">{{ $product->name }}</p>
              <p class="product-card__price">¥{{ number_format($product->price) }}</p>
            </a>
          </div>
          @endforeach
        </div>

        <div class="pagination">
          {{ $products->appends(request()->query())->links() }}
        </div>
        @endif
      </div>
      @endif

    </div>
  </main>

</body>

</html>
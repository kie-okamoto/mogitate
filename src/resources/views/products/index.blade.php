<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>商品一覧ページ</title>
  <link rel="stylesheet" href="{{ asset('css/products/sanitize.css') }}">
  <link rel="stylesheet" href="{{ asset('css/products/index.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital@1&display=swap" rel="stylesheet">
</head>

<body>

  <header class="header">
    <div class="header__inner">
      <div class="header__logo">mogitate</div>
    </div>
  </header>

  <main class="main">
    <div class="product-index">
      <div class="product-index__header">
        <h2 class="product-index__title">商品一覧</h2>
        <a href="{{ route('products.create') }}" class="product-index__add-button">＋ 商品を追加</a>
      </div>

      <div class="product-index__content">
        <aside class="product-index__sidebar">

          <form action="{{ route('products.search') }}" method="GET" class="product-index__search-form">
            <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="商品名で検索">
            <button type="submit" class="btn btn--yellow">検索</button>

            <div class="product-index__sort">
              <label for="sort">価格順で表示</label>
              <select name="sort" id="sort" onchange="this.form.submit()">
                <option value="">価格で並べ替え</option>
                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>低い順</option>
                <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>高い順</option>
              </select>
            </div>
          </form>

          <!-- 並び替えタグ表示 -->
          @if(request('sort'))
          <div class="sort-tag">
            {{ request('sort') == 'asc' ? '低い順に表示' : '高い順に表示' }}
            <a href="{{ route('products.index') }}" class="sort-tag__reset">×</a>
          </div>
          @endif
        </aside>

        <section class="product-index__list">
          @forelse ($products as $product)
          <a href="{{ route('products.show', $product->id) }}" class="product-card">
            <img src="{{ asset('storage/images/' . $product->image) }}" alt="{{ $product->name }}" class="product-card__image">
            <div class="product-card__info">
              <span class="product-card__name">{{ $product->name }}</span>
              <span class="product-card__price">¥{{ number_format($product->price) }}</span>
            </div>
          </a>
          @empty
          <p>該当する商品が見つかりませんでした。</p>
          @endforelse
        </section>
      </div>

      <div class="product-index__pagination">
        {{ $products->appends(request()->query())->onEachSide(2)->links('vendor.pagination.bootstrap-4') }}
      </div>
    </div>
  </main>

</body>

</html>
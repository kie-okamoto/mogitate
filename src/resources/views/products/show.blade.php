<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>{{ $product->name }} の詳細</title>
  <link rel="stylesheet" href="{{ asset('css/products/sanitize.css') }}">
  <link rel="stylesheet" href="{{ asset('css/products/show.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital@1&display=swap" rel="stylesheet">
</head>

<body>
  <header class="header">
    <div class="header__inner">
      <div class="header__logo">mogitate</div>
    </div>
  </header>

  <main class="main">
    <div class="product-show">
      <div class="product-show__nav">
        <a href="{{ route('products.index') }}">商品一覧 ＞ {{ $product->name }}</a>
      </div>

      <div class="product-show__content">
        <div class="product-show__image">
          <img src="{{ asset('storage/images/' . $product->image) }}" alt="{{ $product->name }}">
        </div>

        <div class="product-show__form">
          <h2>{{ $product->name }}</h2>
          <p>¥{{ number_format($product->price) }}</p>
          <p class="product-show__description">{{ $product->description }}</p>
        </div>
      </div>

      <div class="product-show__actions">
        <a href="{{ route('products.index') }}" class="btn btn--gray">戻る</a>
      </div>
    </div>
  </main>
</body>

</html>
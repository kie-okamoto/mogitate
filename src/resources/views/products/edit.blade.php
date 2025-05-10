<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>商品編集ページ</title>
  <link rel="stylesheet" href="{{ asset('css/products/sanitize.css') }}">
  <link rel="stylesheet" href="{{ asset('css/products/edit.css') }}">
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

      <!-- 編集フォーム -->
      <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="product-show__nav">
          <a href="{{ route('products.index') }}">商品一覧 ＞ {{ $product->name }}</a>
        </div>

        <!-- 画像と基本情報 -->
        <div class="product-show__content">
          <div class="product-show__image">
            <img src="{{ asset('storage/images/' . $product->image) }}" alt="{{ $product->name }}">
            <div class="custom-file-wrapper">
              <label class="custom-file-label" for="imageInput">
                ファイルを選択
                <input type="file" name="image" id="imageInput" class="custom-file-input">
              </label>
              <span class="current-file-name" id="fileNameDisplay">
                {{ old('image') ? old('image') : $product->image }}
              </span>
            </div>
            @foreach($errors->get('image') as $message)
            <p class="error">{{ $message }}</p>
            @endforeach
          </div>

          <div class="product-show__form">
            <div class="form-group">
              <label>商品名</label>
              <input type="text" name="name" class="input--product-name" value="{{ old('name', $product->name) }}">
              @foreach($errors->get('name') as $message)
              <p class="error">{{ $message }}</p>
              @endforeach
            </div>

            <div class="form-group">
              <label>値段</label>
              <input type="text" name="price" class="input--product-price" value="{{ old('price', $product->price) }}">
              @foreach($errors->get('price') as $message)
              <p class="error">{{ $message }}</p>
              @endforeach
            </div>

            <div class="form-group">
              <label>季節</label>
              <div class="season-options">
                @php
                $selectedSeasons = old('seasons', $product->seasons->pluck('id')->toArray());
                @endphp
                @foreach($seasons as $season)
                <label>
                  <input type="checkbox" name="seasons[]" value="{{ $season->id }}" class="season-checkbox"
                    {{ in_array($season->id, $selectedSeasons) ? 'checked' : '' }}>
                  {{ $season->name }}
                </label>
                @endforeach
              </div>
              @error('seasons')
              <div class="error" style="color: red;">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>

        <div class="form-group form-group--description" style="margin-top: 40px;">
          <label for="description">商品説明</label>
          <textarea name="description" id="description">{{ old('description', $product->description) }}</textarea>
          @foreach($errors->get('description') as $message)
          <p class="error">{{ $message }}</p>
          @endforeach
        </div>

        <!-- 戻る＆保存 -->
        <div class="product-show__actions" style="display: flex; justify-content: center; margin-top: 32px; gap: 32px;">
          <a href="{{ route('products.index') }}" class="btn btn--gray">戻る</a>
          <button type="submit" class="btn btn--yellow">変更を保存</button>
        </div>
      </form>

      <!-- 削除ボタン：form外に設置 -->
      <div class="product-show__delete" style="display: flex; justify-content: flex-end; margin-top: 16px;">
        <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" style="margin: 0;">
          @csrf
          <button type="submit" class="btn btn--delete" style="background: none; border: none; cursor: pointer; padding: 0;">
            <img src="{{ asset('images/trash-icon.svg') }}" alt="削除" style="width: 24px; height: 24px;">
          </button>
        </form>
      </div>

    </div>
  </main>

  <script>
    document.getElementById('imageInput').addEventListener('change', function(event) {
      const fileName = event.target.files[0]?.name || '{{ $product->image }}';
      document.getElementById('fileNameDisplay').textContent = fileName;
    });
  </script>
</body>

</html>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>商品登録ページ</title>
  <link rel="stylesheet" href="{{ asset('css/products/sanitize.css') }}">
  <link rel="stylesheet" href="{{ asset('css/products/create.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital@1&display=swap" rel="stylesheet">
</head>

<body>
  <header class="header">
    <div class="header__inner">
      <div class="header__logo">mogitate</div>
    </div>
  </header>

  <main class="main">
    <div class="product-register">
      <h2 class="product-register__title">商品登録</h2>

      <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- 商品名 -->
        <div class="form-group">
          <label>商品名 <span class="required">必須</span></label>
          <input type="text" name="name" placeholder="商品名を入力" value="{{ old('name') }}">
          @error('name')
          <p class="error">{{ $message }}</p>
          @enderror
        </div>

        <!-- 値段 -->
        <div class="form-group">
          <label>値段 <span class="required">必須</span></label>
          <input type="text" name="price" placeholder="値段を入力" value="{{ old('price') }}">
          @error('price')
          <p class="error">{{ $message }}</p>
          @enderror
        </div>

        <!-- 商品画像 -->
        <div class="form-group">
          <label>商品画像 <span class="required">必須</span></label>

          @php
          $tmpImage = old('tmp_image') ?? session('tmp_image');
          @endphp

          @if ($tmpImage)
          <div class="form-group">
            <label>アップロード済み画像：</label><br>
            <img src="{{ asset('storage/tmp/' . $tmpImage) }}" alt="プレビュー画像" width="200">
            <input type="hidden" name="tmp_image" value="{{ $tmpImage }}">
          </div>
          @else
          <div class="image-preview" style="margin-bottom: 10px;">
            <img id="imagePreview" src="#" alt="画像プレビュー" style="display: none; max-width: 300px;">
          </div>
          @endif

          <div class="file-wrapper" style="display: flex; align-items: center; gap: 10px;">
            <label class="custom-file">
              <input type="file" name="image" class="custom-file-input" onchange="previewImage(event)" accept="image/jpeg,image/png,image/jpg">
              <span class="custom-file-label">ファイルを選択</span>
            </label>
            <span id="file-name-label" class="file-name-label" style="font-size: 14px; color: #333;"></span>
          </div>

          @error('image')
          <p class="error">{{ $message }}</p>
          @enderror
        </div>



        <!-- 季節 -->
        <div class="form-group">
          <label>季節 <span class="required">必須</span> <span class="note">複数選択可</span></label>
          <div class="season-options">
            @foreach(['春', '夏', '秋', '冬'] as $season)
            <label>
              <input type="checkbox" name="seasons[]" value="{{ $season }}"
                class="season-checkbox"
                {{ is_array(old('seasons')) && in_array($season, old('seasons')) ? 'checked' : '' }}>
              {{ $season }}
            </label>
            @endforeach
          </div>
          @error('seasons')
          <p class="error">{{ $message }}</p>
          @enderror
        </div>

        <!-- 商品説明 -->
        <div class="form-group">
          <label>商品説明 <span class="required">必須</span></label>
          <textarea name="description" placeholder="商品の説明を入力">{{ old('description') }}</textarea>
          @error('description')
          <p class="error">{{ $message }}</p>
          @enderror
        </div>

        <!-- ボタン -->
        <div class="form-actions">
          <a href="{{ route('products.index') }}" class="btn btn--gray">戻る</a>
          <button type="submit" class="btn btn--yellow">登録</button>
        </div>

      </form>
    </div>
  </main>

  <!-- JavaScript（画像プレビュー + ファイル名表示） -->
  <script>
    function previewImage(event) {
      const input = event.target;
      const file = input.files[0];

      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const preview = document.getElementById('imagePreview');
          preview.src = e.target.result;
          preview.style.display = 'block';
        };
        reader.readAsDataURL(file);

        const fileNameLabel = document.getElementById('file-name-label');
        fileNameLabel.textContent = file.name;
      }
    }
  </script>

</body>

</html>
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // 検索機能
        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        // 並び替え機能
        if ($request->filled('sort')) {
            if ($request->sort === 'asc') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort === 'desc') {
                $query->orderBy('price', 'desc');
            }
        }

        $products = $query->paginate(6);

        return view('products.index', [
            'products' => $products,
            'keyword' => $request->keyword,
            'sort' => $request->sort,
        ]);
    }

    public function search(Request $request)
    {
        $query = Product::query();

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        if ($request->filled('sort')) {
            if ($request->sort === 'asc') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort === 'desc') {
                $query->orderBy('price', 'desc');
            }
        }

        $products = $query->paginate(6);

        return view('products.index', [
            'products' => $products,
            'keyword' => $request->keyword,
            'sort' => $request->sort,
        ]);
    }

    public function show($productId)
    {
        $product = Product::findOrFail($productId);
        return view('products.show', compact('product'));
    }

    public function create()
    {
        return view('products.create');
    }

    private function getSeasonIdByName($seasonName)
    {
        $seasonMap = [
            '春' => 1,
            '夏' => 2,
            '秋' => 3,
            '冬' => 4,
        ];

        return $seasonMap[$seasonName] ?? null;
    }

    public function store(ProductStoreRequest $request)
    {
        $validated = $request->validated();
        $imageFileName = '';

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename = $originalName . '_' . time() . '.' . $extension;

            $file->storeAs('images', $filename, 'public');
            $imageFileName = $filename;

            session(['tmp_image' => $filename]);
        } elseif ($request->filled('tmp_image')) {
            $tmpImageName = $request->input('tmp_image');
            $tmpImagePath = storage_path('app/public/tmp/' . $tmpImageName);

            if (file_exists($tmpImagePath)) {
                $originalName = pathinfo($tmpImageName, PATHINFO_FILENAME);
                $extension = pathinfo($tmpImageName, PATHINFO_EXTENSION);
                $finalFileName = $originalName . '_' . time() . '.' . $extension;
                $newPath = storage_path('app/public/images/' . $finalFileName);

                rename($tmpImagePath, $newPath);
                $imageFileName = $finalFileName;
            }
        }

        $product = Product::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'image' => $imageFileName,
        ]);

        foreach ($validated['seasons'] as $seasonName) {
            $seasonId = $this->getSeasonIdByName($seasonName);
            if ($seasonId) {
                $product->seasons()->attach($seasonId);
            }
        }

        session()->forget('tmp_image');

        return redirect()->route('products.index')->with('message', '商品を登録しました！');
    }


    public function edit($productId)
    {
        $product = Product::with('seasons')->findOrFail($productId);
        $productSeason = $product->seasons->pluck('id')->toArray();
        $seasons = \App\Models\Season::all();

        return view('products.edit', compact('product', 'productSeason', 'seasons'));
    }

    public function update(ProductUpdateRequest $request, $productId)
    {
        $validated = $request->validated();
        $product = Product::findOrFail($productId);

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = $originalName . '_' . time() . '.' . $extension;

            if ($product->image && Storage::disk('public')->exists('images/' . $product->image)) {
                Storage::disk('public')->delete('images/' . $product->image);
            }

            $file->storeAs('images', $fileName, 'public');
            $product->image = $fileName;
        }

        $product->name = $validated['name'];
        $product->price = $validated['price'];
        $product->description = $validated['description'];
        $product->save();

        $seasonIds = array_map('intval', $validated['seasons'] ?? []);
        $product->seasons()->sync($seasonIds);

        return redirect()->route('products.index')->with('message', '商品情報を更新しました！');
    }


    public function destroy($productId)
    {
        $product = Product::findOrFail($productId);

        // 画像ファイルの削除
        if ($product->image && Storage::disk('public')->exists('images/' . $product->image)) {
            Storage::disk('public')->delete('images/' . $product->image);
        }

        // 中間テーブルの関連解除
        $product->seasons()->detach();

        // 商品レコード削除
        $product->delete();

        return redirect()->route('products.index')->with('message', '商品を削除しました！');
    }
}

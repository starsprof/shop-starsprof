<?php
/**
 * Created by PhpStorm.
 * User: НАТАША
 * Date: 27.08.2019
 * Time: 14:20
 */

namespace App\Http\Controllers;


use App\Category;
use App\Product;
use Illuminate\Http\Request;

class PageController
{
    public function index()
    {

        $products = Product::paginate(16);
        return view('pages.collection', compact('products'));


    }

    public function detail(Request $request)
    {
        $product_id = $request->all('id');
        $product = Product::find($product_id)->first();
        return view('pages.detail', compact('product'));
    }

    public function category(Request $request)
    {
        $categoryId = (int)$request->all()['id'];

        $categoriesIds = Category::find($categoryId)->children()->pluck('id');
        $categoriesIds[] = $categoryId;
        $parentCategoryId = Category::findOrFail($categoryId)->parent_id;


        $productSizes = Product::whereIn('category_id', $categoriesIds)->get('size')->pluck('size')->toArray();
        $sizes = array_merge(...$productSizes);
        $sizes = array_unique($sizes);
        $selectedSizes = [];
        if(isset($request->all()['size'])){
            $selectedSizes = $request->all()['size'];
            $conditions = [];
            foreach ($selectedSizes as $size) {
                    $conditions[] = ['size', 'like', '%' . $size . '%'];
            }
            $products = Product::whereIn('category_id', $categoriesIds)->where($conditions)->paginate(16);

        }else {
            $products = Product::whereIn('category_id', $categoriesIds)->paginate(16);
        }

        return view('pages.collection', compact('products', 'parentCategoryId', 'categoryId', 'sizes', 'selectedSizes'));
    }


}

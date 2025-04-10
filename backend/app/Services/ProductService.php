<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductService
{

    public function getProductsCount() {
        return Product::count();
    }
 
    public function createProduct(array $data)
    {
        $validator = Validator::make($data, [
            'product_code' => 'required|unique:products|max:255',
            'description' => 'required',
            'category_id' => 'nullable|exists:product_categories,id',
            'origin' => 'nullable|string',
            'destination' => 'nullable|string',
            'ipi' => 'nullable|numeric',
            'icms' => 'nullable|numeric',
            'suggested_retail_price' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        return Product::create($data);
    }


    public function updateProduct(Product $product, array $data)
    {
        $validator = Validator::make($data, [
            'product_code' => 'required|max:255|unique:products,product_code,' . $product->id,
            'description' => 'required',
            'category_id' => 'nullable|exists:product_categories,id',
            'origin' => 'nullable|string',
            'destination' => 'nullable|string',
            'ipi' => 'nullable|numeric',
            'icms' => 'nullable|numeric',
            'suggested_retail_price' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $product->update($data);

        return $product;
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();
    }

    public function getAllProducts()
    {
        return Product::all();
    }


    public function getProductById($id)
    {
        return Product::findOrFail($id);
    }

    public function getProductByCodeId($productCode)
    {
        return Product::where('product_code', $productCode)->firstOrFail();
    }
    
}

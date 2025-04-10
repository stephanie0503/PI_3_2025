<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function __construct
    (
        protected ProductService $productService
    ) {}

    public function index()
    {
        $products = $this->productService->getAllProducts();
        
        return response()->json($products);
    }

    
    public function store(Request $request)
    {
        try {
            $this->productService->createProduct($request->all());
            return redirect()->route('products.index')->with('success', 'Product created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }

    public function show($id)
    {
        $product = $this->productService->getProductByCodeId($id);

        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        try {
            $product = $this->productService->getProductById($id);
            $this->productService->updateProduct($product, $request->all());
            return redirect()->route('products.index')->with('success', 'Product updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }

    public function destroy($id)
    {
        $product = $this->productService->getProductById($id);
        $this->productService->deleteProduct($product);
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}

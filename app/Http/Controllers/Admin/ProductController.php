<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\ProductMaster;
// use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductExport;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{

// public function export(Request $request)
// {
//     return Excel::download(
//         new ProductExport($request->all()),
//         'products.xlsx'
//     );
// }

    public function search(Request $request)
    {
        session([
            'product_search' => $request->search,
            'product_status' => $request->status,
        ]);

        return redirect()->route('admin.products.index');
    }

    public function clearSearch()
    {
        session()->forget([
            'product_search',
            'product_status',
        ]);

        return redirect()->route('admin.products.index');
    }
    public function index(Request $request)
    {
        $search = session('product_search');
        $status = session('product_status');

        $products = ProductMaster::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('c_product_code', 'LIKE', "%{$search}%")
                    ->orWhere('c_product_name', 'LIKE', "%{$search}%");
                });
            })
            ->when(!empty($status), function ($query) use ($status) {
                $query->where('c_status', $status);
            })
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

   public function store(Request $request)
    {
        $validated = $request->validate([
                'c_product_name'    => 'required|string|max:255',
                'c_product_code'    => 'required|string|unique:product_masters,c_product_code',
                'n_purchase_price'  => 'required|numeric|min:0',
                'n_selling_price'   => 'required|numeric|min:0|gt:n_purchase_price',
                'n_mrp'             => 'required|numeric|min:0|gte:n_selling_price',
                'c_status'          => 'required|in:Y,N',
            ], [
                'c_product_name.required'   => 'Product name cannot be empty.',
                'c_product_code.required'   => 'Product code cannot be empty.',
                'c_product_code.unique'     => 'Product code already exists.',
                'n_purchase_price.required' => 'Purchase price cannot be empty.',
                'n_selling_price.required'  => 'Selling price cannot be empty.',
                'n_selling_price.gt'        => 'Selling price must be greater than purchase price.',
                'n_mrp.required'            => 'MRP cannot be empty.',
                'n_mrp.gte'                 => 'MRP must be greater than or equal to selling price.',
            ]);
            ProductMaster::create($validated);

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product created successfully.');
    }

    public function edit(ProductMaster $product)
    {
        return view('admin.products.edit', compact('product'));
    }
    public function update(Request $request, ProductMaster $product)
    {
        $validated = $request->validate([
            'c_product_name'   => 'required|string|max:255',
            'c_product_code'   => ['required','string',
                Rule::unique('product_masters', 'c_product_code')
                    ->ignore($product->n_product_id, 'n_product_id'),
            ],
            'n_purchase_price' => 'required|numeric|min:0',
            'n_selling_price'  => 'required|numeric|min:0|gt:n_purchase_price',
            'n_mrp'            => 'required|numeric|min:0|gte:n_selling_price',
            'c_status'         => 'required|in:Y,N',
        ], [
            'c_product_name.required'   => 'Product name cannot be empty.',
            'c_product_code.required'   => 'Product code cannot be empty.',
            'c_product_code.unique'     => 'Product code already exists.',
            'n_purchase_price.required' => 'Purchase price cannot be empty.',
            'n_selling_price.required'  => 'Selling price cannot be empty.',
            'n_selling_price.gt'        => 'Selling price must be greater than purchase price.',
            'n_mrp.required'            => 'MRP cannot be empty.',
            'n_mrp.gte'                 => 'MRP must be greater than or equal to selling price.',
        ]);

        $product->update($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }


    public function destroy(ProductMaster $product)
    {
        // Update product status to 'D' (Deleted)
        $product->update([
            'c_status' => 'D',
        ]);

        // Soft delete the product
        $product->delete();

        return redirect()->route('admin.products.index')
                        ->with('success', 'Product deleted successfully.');
    }
}
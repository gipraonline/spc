<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProductExport;
use App\Http\Controllers\Controller;
use App\Models\CategoryMaster;
use App\Models\ProductMaster;
// use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
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
            ->when(! empty($status), function ($query) use ($status) {
                $query->where('c_status', $status);
            })
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = CategoryMaster::where('c_status', 'Y')
            ->with('children')
            ->whereNull('n_parent_category_id')
            ->orderBy('c_category_name')
            ->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'n_category_id' => ['required',
                Rule::exists('category_masters', 'n_category_id')->where('c_status', 'Y'),
            ],
            'c_product_name' => 'required|string|max:255',
            'c_product_code' => 'required|string|unique:product_masters,c_product_code',
            'n_mrp' => 'required|numeric|min:0',
            'c_unit' => ['nullable', 'string', 'max:50'],
            'c_hsn_code' => 'required|string|max:20',
            'n_gst_percentage' => 'required|numeric|min:0|max:100',
            'c_status' => 'required|in:Y,N',
        ], [
            'n_category_id.required' => 'Please select a category.',
            'n_category_id.exists' => 'Please select a valid active category.',
            'c_product_name.required' => 'Product name cannot be empty.',
            'c_product_code.required' => 'Product code cannot be empty.',
            'c_product_code.unique' => 'Product code already exists.',
            'n_mrp.required' => 'MRP cannot be empty.',
            'c_hsn_code.required' => 'HSN code cannot be empty.',
            'n_gst_percentage.required' => 'GST percentage cannot be empty.',
            'n_gst_percentage.max' => 'GST percentage cannot be greater than 100.',
        ]);
        ProductMaster::create($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(ProductMaster $product)
    {
        $categories = CategoryMaster::where('c_status', 'Y')
            ->with('children')
            ->whereNull('n_parent_category_id')
            ->orderBy('c_category_name')
            ->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, ProductMaster $product)
    {

        $validated = $request->validate([
            'n_category_id' => ['required',
                Rule::exists('category_masters', 'n_category_id')
                    ->where('c_status', 'Y'),
            ],
            'c_product_name' => 'required|string|max:255',
            'c_product_code' => ['required', 'string',
                Rule::unique('product_masters', 'c_product_code')
                    ->ignore($product->n_product_id, 'n_product_id'),
            ],
            'n_mrp' => 'required|numeric|min:0',
            'c_unit' => ['nullable', 'string', 'max:50'],
            'c_hsn_code' => 'required|string|max:20',
            'n_gst_percentage' => 'required|numeric|min:0|max:100',
            'c_status' => 'required|in:Y,N',
        ], [
            'n_category_id.required' => 'Please select a category.',
            'n_category_id.exists' => 'Please select a valid active category.',
            'c_product_name.required' => 'Product name cannot be empty.',
            'c_product_code.required' => 'Product code cannot be empty.',
            'c_product_code.unique' => 'Product code already exists.',
            'n_mrp.required' => 'MRP cannot be empty.',
            'c_hsn_code.required' => 'HSN code cannot be empty.',
            'n_gst_percentage.required' => 'GST percentage cannot be empty.',
            'n_gst_percentage.max' => 'GST percentage cannot be greater than 100.',
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

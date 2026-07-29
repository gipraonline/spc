<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\ProductMaster;
use App\Models\ProductIncentive;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductExport;

class ProductController extends Controller
{

public function export(Request $request)
{
    return Excel::download(
        new ProductExport($request->all()),
        'products.xlsx'
    );
}
    public function index(Request $request)
    {
        // $products = ProductMaster::paginate(15);
    $search = $request->search;
    $status = $request->status;


    $products = ProductMaster::query()

        ->when($search, function ($query) use ($search) {

            $query->where('c_product_code', 'LIKE', "%{$search}%")
                  ->orWhere('c_product_name', 'LIKE', "%{$search}%");

        })

        ->when($status, function ($query) use ($status) {

            $query->where('c_status', $status);
         })
        ->paginate(15)
        ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

   
    public function store(Request $request)
    {
        $validated = $request->validate([
            'c_product_name' => 'required|string',
            // 'c_product_code' => 'required|string',
            'c_product_code' => 'required|string|unique:product_masters,c_product_code',
            // 'n_selling_price' => 'required|numeric|min:0',
            'n_selling_price' => 'required|numeric|min:0|gt:n_purchase_price',
            'n_purchase_price' => 'required|numeric|min:0',
            'c_status' => 'required|in:Y,N'

         ],[
            'c_product_code.unique' => 'Product code already exists',
            'c_product_code.required' => 'Product code cannot be empty',
            'n_selling_price.gt' => 'Selling price must be greater than purchase price',
        ]);


        $productMaster = ProductMaster::create($validated);


        $input = $request->all();
        $active = $request->c_active_incentive;

        if ($active == 1) {
            $input['n_product_id'] = $productMaster->n_product_id;
            $productIncentives = ProductIncentive::create($input);
        }


        return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
    }

    public function show(ProductMaster $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(ProductMaster $product)
    {
        //echo $item;exit;
        //$product=ProductMaster::find($product->n_product_id);
        // $productIncentives=ProductIncentive::where('n_product_id',$product->n_product_id)->get();
        $product = ProductMaster::with('incentives')->find($product->n_product_id);
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, ProductMaster $product)
    {
        // print_r($request->all());exit;
        $validated = $request->validate([
            'c_product_name' => 'required|string',
            //'c_product_code' => 'required|string',
            'c_product_code' => 'required|string',
            // 'n_selling_price' => 'required|numeric|min:0',
            'n_selling_price' => 'required|numeric|min:0|gt:n_purchase_price',
            'n_purchase_price' => 'required|numeric|min:0',
            'c_status' => 'required|in:Y,N',
           ],[

            'c_product_code.required' => 'Product code cannot be empty',
            'n_selling_price.gt' => 'Selling price must be greater than purchase price',
        ]);

        $product->update($validated);


        $input = $request->all();
        $active = $request->c_active_incentive;
        if ($active == 1) {
            $productIncentives = ProductIncentive::where('n_product_id', $product->n_product_id)->first();
            $productIncentives->update($input);
        }


        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(ProductMaster $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');
    }
}
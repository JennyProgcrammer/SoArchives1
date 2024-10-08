<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index() {
        // Get the authenticated user
        $user = Auth::user();
    
        // Fetch only the products posted by the authenticated seller
        $products = Product::where('UserID', $user->id)->get();  //eto nadagdag
    
        // Pass the products data to the view
        return view('profile.profile-seller', compact('products', 'user'));
    }

    public function create() {
        return view('profile.profile-seller'); //recheck later dapat ma return siya sa seller profile na products modal
    }


    public function store(Request $request) 
    {
        try {
            $data = $request->validate([
                'name' => 'required',
                'qty' => 'required|numeric',
                'price' => 'required|numeric|regex:/^\d{0,7}(\.\d{1,2})?$/',
                'description' => 'required',
                'prodimg.*' => ['required', 'mimes:jpeg,jpg,png', 'max:5000'] // Allow multiple image types with a maximum size of 2MB
            ]);
    
            
            // Get the authenticated user
            $user = Auth::user();
    
            // Initialize an empty array to store the paths of uploaded images
            $imagePaths = [];
    
            // Store each uploaded image in a folder specific to the user
            foreach ($request->file('prodimg') as $file) {
                $imagePath = $file->store("public/products/{$user->id}");
                $imagePaths[] = $imagePath;
            }
    
            // Create a new product record
            $newProduct = Product::create([
                'ProductName' => $data['name'],
                'ProductDescription' => $data['description'],
                'Price' => $data['price'],
                'Quantity' => $data['qty'],
                'ProductImage' => implode(',', $imagePaths), // Store paths as comma-separated values
                'UserID' => $user->id // Associate the product with the authenticated seller
            ]);
   
            // used try-catch block to handle any exceptions that may occur
            return back()->with('message', 'The post has been added!')->with('type', 'success');

            
            // the $e variable is to allow you to access information about the exception that occurred, 
            // such as the error message, the stack trace, or any other relevant data. This can be useful for logging, 
            // debugging, or providing more detailed error messages to the user
        } catch (\Exception $e) {
            return back()->with('message', 'Error creating the product, your file might not be supported')->with('type', 'error');
        }
    }

    public function edit(Product $product) {
        return view('profile.profile-seller', ['product' => $product]);
    }

    public function update(Product $product, Request $request) {


        try {
            // Get the authenticated user
            $user = Auth::user();
    
            // Retrieve the existing product from the database
            $product = Product::findOrFail($product->ProductID);
    
            // Update the product attributes with the new data
            $product->ProductName = $request->input('Pname');
            $product->ProductDescription = $request->input('Pdescription');
            $product->Price = $request->input('Pprice');
            $product->Quantity = $request->input('Pqty');
    
            // Check if a new image is uploaded
            if ($request->hasFile('PImage')) {
                // Store the new image and update the product's image path
                $imagePath = $request->file('PImage')->store("public/products/{$user->id}");
                $product->ProductImage = $imagePath;
            }
    
            if ($product->save()) {
                return back()->with('message', 'Product Updated Successfully')->with('type', 'success');
            } else {
                return back()->with('message', 'Error updating the product')->with('type', 'error');
            }
        } catch (\Exception $e) {
            // Log the exception or handle it in a more appropriate way
            error_log('Error updating product: ' . $e->getMessage());
            return back()->with('message', 'An error occurred while updating the product')->with('type', 'error');
        }
    }
    
    
    public function destroy(Product $product){
        
        try {
            // Delete the product
            if ($product->delete()) {
                return back()->with('message', 'Product deleted successfully')->with('type', 'success');
            } else {
                return back()->with('message', 'Error deleting the product')->with('type', 'error');
            }
        } catch (\Exception $e) {
            // the $e variable is to allow you to access information about the exception that occurred,
            // such as the error message, the stack trace, or any other relevant data. This can be useful for logging,
            // debugging, or providing more detailed error messages to the user
            return back()->with('message', 'Error deleting the product, please try again later')->with('type', 'error');
        }

    
    }

    public function calculateTotalPrice(Request $request)
    {
        // Get the selected product IDs from the form submission
        $selectedProductIDs = $request->input('selected_products', []);

        // Fetch the products based on the selected IDs
        $selectedProducts = Product::whereIn('ProductID', $selectedProductIDs)->get();

        // Calculate the total price
        $totalPrice = $selectedProducts->sum('Price');

        // Pass the total price to the view
        return view('total-price', ['totalPrice' => $totalPrice]);
    }


    public function search(Request $request)
    {

        $user = Auth::user();

        $search = $request->search;

        $products = Product::where('UserID', $user->id)
            ->where('ProductName', 'like', "%$search%")->get();

        return view('profile.profile-seller', compact('products', 'search', 'user'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductVariant;
class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::where('user_id', Auth::id())
            ->with('productVariant.product') // Lấy thông tin sản phẩm và biến thể
            ->get();
    
        return view('cart', compact('carts'));
    }

    public function store(Request $request)
    {
        $cart = Cart::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_variant_id' => $request->product_variant_id
            ],
            [
                'price' => $request->price,
                'quantity' => $request->quantity,
            ]
        );

        return response()->json(['message' => 'Cart updated successfully', 'cart' => $cart]);
    }

    public function edit($id)
    {
        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        return view('cart-edit', compact('cart'));
    }
    
    public function update(Request $request, $id)
    {
        // Find the cart item
        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
    
        // Get the variant and its available quantity
        $variant = ProductVariant::find($cart->product_variant_id);
        if (!$variant) {
            return response()->json(['error' => 'Biến thể sản phẩm không hợp lệ.'], 400);
        }
    
        // Validate the requested quantity
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
    
        // Check if the requested quantity is available
        if ($request->quantity > $variant->quantity) {
            return response()->json(['error' => 'Số lượng yêu cầu vượt quá số lượng tồn kho.'], 400);
        }
    
        // Update the cart item quantity
        $cart->update([
            'quantity' => $request->quantity,
        ]);
    
        return response()->json(['message' => 'Cập nhật số lượng thành công!', 'cart' => $cart]);
    }
    
    

    public function destroy($id)
    {
        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cart->delete();
    
        return response()->json(['message' => 'Sản phẩm đã được xóa khỏi giỏ hàng!']);
    }
    
    public function addToCart(Request $request)
    {
        // Validate input
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);
    
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thêm vào giỏ hàng.');
        }
    
        // Get the variant and its available quantity
        $variant = ProductVariant::find($request->variant_id);
        if (!$variant) {
            return redirect()->back()->with('error', 'Biến thể sản phẩm không hợp lệ.');
        }
    
        // Check if the requested quantity is available
        if ($request->quantity > $variant->quantity) {
            return redirect()->back()->with('error', 'Số lượng yêu cầu vượt quá số lượng tồn kho.');
        }
    
        // Check if the product is already in the cart
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_variant_id', $request->variant_id)
            ->first();
    
        if ($cartItem) {
            // Update the quantity if the item already exists in the cart
            $cartItem->quantity += $request->quantity;
    
            // Check again after updating if the quantity exceeds available stock
            if ($cartItem->quantity > $variant->quantity) {
                return redirect()->back()->with('error', 'Số lượng yêu cầu vượt quá số lượng tồn kho.');
            }
    
            $cartItem->save();
        } else {
            // Add the product to the cart
            Cart::create([
                'user_id' => Auth::id(),
                'product_variant_id' => $request->variant_id,
                'price' => $variant->price,
                'quantity' => $request->quantity,
            ]);
        }
    
        return redirect()->back()->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
    }
    
    
}

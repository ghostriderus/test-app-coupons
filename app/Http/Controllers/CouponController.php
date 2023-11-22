<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Coupon;
use Auth, Carbon\Carbon;

class CouponController extends Controller
{
    public array $categories = [
        'Фрукты и овощи',
        'Напитки',
        'Выпечка',
        'Снеки',
        'Посуда',
        'Хозтовары',
    ];
    
    /**
     * Display promocode forms
     */
    public function index(): View
    {
        return view('coupons.index');
    }
    
    /**
     * Display promocode info
     */
    public function show(int $cid): View
    {
        $coupon = Coupon::where([
            ['user_id', Auth::id()],
            ['id', $cid]
        ])->first();
        return view('coupons.show',[
            'coupon' => $coupon
        ]);
    }
    
    /**
     * Search promocode
     */
    public function search(Request $request): View
    {
        $request->validate([
            'promocode' => ['required','regex:/^[A-Z]{5}[\d]{4}$/i'],
        ]);
        $coupon = Coupon::where([
            ['user_id', Auth::id()],
            ['promocode', $request->promocode]
        ])->first();
        return view('coupons.show',[
            'coupon' => $coupon
        ]);
    }
    
    /**
     * Create new promocode
     */
    public function store(): RedirectResponse
    {
        $prevCoupon = Coupon::where('user_id', Auth::id())
            ->where('created_at', '>', Carbon::now()->subMinutes(90)->toDateTimeString())->first();
        if($prevCoupon){
            return redirect("/coupons/{$prevCoupon->id}");
        }
        $category = $this->categories[array_rand($this->categories)];
        $discount = (rand(1, 5)) * 5;
        $promocode = $this->generatePromocode();
        
        $coupon = Coupon::create([
            'promocode' => $promocode,
            'user_id' => Auth::id(),
            'discount' => $discount,
            'category' => $category
        ]);
        
        return redirect("/coupons/{$coupon->id}");
    }
    
    /**
     * Generate random promocode
     */
    public function generatePromocode(): string
    {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $digits = "0123456789";
        
        $charstr = "";
        $numstr = "";
        
        for ($i = 0; $i < 5; $i++) {
            $charstr .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        for ($i = 0; $i < 4; $i++) {
            $numstr .= $digits[mt_rand(0, strlen($digits) - 1)];
        }
        $code = $charstr . $numstr;
        if(Coupon::where('code', $code)->exists()){
            $code = $this->generatePromocode();
        }
        return $code;
    }
}

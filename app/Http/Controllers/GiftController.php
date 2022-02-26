<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gift;
use App\Models\Purchase;
use App\Models\Menu;
use App\Models\Owner;
use Illuminate\Support\Str;
use App\Http\Requests\GiftRequest;

class GiftController extends Controller
{
    public function store(GiftRequest $request)
    {
        $input = $request->validated();

        $domain = config('services.stripe.domain_url');
        $url = $domain . 'gift/' . Str::random(48);

        $item = Gift::create([
            'purchase_id' => $input['purchase_id'],
            'url' => $url,
            'display' => $input['display'],
        ]);

        //purchases
        $purchase = Purchase::where('id', $item->purchase_id)->first();
        $item->quantity = $purchase->quantity;

        //menus
        $menu = Menu::where('id', $purchase->menu_id)->first();
        $item->menu_image = $menu->image;
        $item->menu_name = $menu->name;
        $item->menu_price = $menu->price;

        return response()->json(['data' => $item]);
    }

    public function show(Request $request)
    {
        $item = Gift::where('url', 'LIKE', "%$request->url%")->first();

        //purchases
        $purchase = Purchase::where('id', $item->purchase_id)->first();
        $item->quantity = $purchase->quantity;

        //menus
        $menu = Menu::where('id', $purchase->menu_id)->first();
        $item->menu_image = $menu->image;
        $item->menu_name = $menu->name;
        $item->menu_price = $menu->price;

        //owners
        $owner = Owner::where('id', $menu->owner_id)->first();
        $item->owner_name = $owner->name;


        if (!$item) {
            return response()->json(['message' => 'Not found']);
        }

        return response()->json(['data' => $item]);
    }
}

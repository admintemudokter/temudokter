<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function pricing()
    {
        $onlinePrice = Setting::getValue('online_price', 25000);
        $onlineDiscount = Setting::getValue('online_discount', 0);
        $homecarePrice = Setting::getValue('homecare_price', 150000);
        $homecareDiscount = Setting::getValue('homecare_discount', 0);

        return view('admin.setting.pricing', compact(
            'onlinePrice', 'onlineDiscount', 'homecarePrice', 'homecareDiscount'
        ));
    }

    public function updatePricing(Request $request)
    {
        $data = $request->validate([
            'online_price'      => 'required|numeric|min:0',
            'online_discount'   => 'required|numeric|min:0',
            'homecare_price'    => 'required|numeric|min:0',
            'homecare_discount' => 'required|numeric|min:0',
        ]);

        Setting::setValue('online_price', $data['online_price']);
        Setting::setValue('online_discount', $data['online_discount']);
        Setting::setValue('homecare_price', $data['homecare_price']);
        Setting::setValue('homecare_discount', $data['homecare_discount']);

        return redirect()->route('admin.settings.pricing')
            ->with('success', 'Pengaturan harga dan diskon berhasil disimpan.');
    }
}

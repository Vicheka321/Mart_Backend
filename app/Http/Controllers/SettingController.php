<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        // mock data for preview / existing settings
        $settings = [
            'store_name' => 'Darita Mart',
            'store_email' => 'admin@daritamart.com',
            'store_phone' => '+855 12 345 678',
            'timezone' => 'Asia/Phnom_Penh',
            'currency' => 'USD',
            'language' => 'en',

            'primary_color' => '#5B5CEB',
            'secondary_color' => '#16A34A',
            'sidebar_style' => 'light',
            'layout_style' => 'default',
            'font_family' => 'Inter',
            'base_font_size' => '14px',
            'compact_sidebar' => false,

            'default_country' => 'Cambodia',
            'default_province' => 'Phnom Penh',
            'date_format' => 'd M Y',
            'time_format' => '12h',

            'cod_enabled' => true,
            'aba_enabled' => true,
            'khqr_enabled' => true,
            'paypal_enabled' => false,

            'free_shipping_threshold' => '25',
            'default_shipping_fee' => '1.50',
            'estimated_delivery_days' => '1-2',

            'auto_confirm_orders' => false,
            'auto_complete_after_days' => 3,
            'low_stock_threshold' => 10,

            'mail_from_name' => 'Darita Mart',
            'mail_from_email' => 'noreply@daritamart.com',

            'sms_sender' => 'DaritaMart',
            'push_enabled' => true,
            'order_push' => true,
            'stock_push' => true,
            'marketing_push' => false,

            'maintenance_mode' => false,
            'maintenance_message' => 'We are improving the system. Please come back soon.',
        ];

        return view('Admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'nullable|string|max:255',
            'store_email' => 'nullable|email|max:255',
            'store_phone' => 'nullable|string|max:50',

            'timezone' => 'nullable|string|max:100',
            'currency' => 'nullable|string|max:20',
            'language' => 'nullable|string|max:20',

            'primary_color' => 'nullable|string|max:20',
            'secondary_color' => 'nullable|string|max:20',
            'sidebar_style' => 'nullable|string|max:50',
            'layout_style' => 'nullable|string|max:50',
            'font_family' => 'nullable|string|max:100',
            'base_font_size' => 'nullable|string|max:20',

            'default_country' => 'nullable|string|max:100',
            'default_province' => 'nullable|string|max:100',
            'date_format' => 'nullable|string|max:50',
            'time_format' => 'nullable|string|max:20',

            'free_shipping_threshold' => 'nullable|numeric',
            'default_shipping_fee' => 'nullable|numeric',
            'estimated_delivery_days' => 'nullable|string|max:50',

            'auto_complete_after_days' => 'nullable|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',

            'mail_from_name' => 'nullable|string|max:255',
            'mail_from_email' => 'nullable|email|max:255',

            'sms_sender' => 'nullable|string|max:100',
            'maintenance_message' => 'nullable|string|max:500',
        ]);

        // checkbox normalize
        $validated['compact_sidebar'] = $request->has('compact_sidebar');
        $validated['cod_enabled'] = $request->has('cod_enabled');
        $validated['aba_enabled'] = $request->has('aba_enabled');
        $validated['khqr_enabled'] = $request->has('khqr_enabled');
        $validated['paypal_enabled'] = $request->has('paypal_enabled');

        $validated['auto_confirm_orders'] = $request->has('auto_confirm_orders');

        $validated['push_enabled'] = $request->has('push_enabled');
        $validated['order_push'] = $request->has('order_push');
        $validated['stock_push'] = $request->has('stock_push');
        $validated['marketing_push'] = $request->has('marketing_push');

        $validated['maintenance_mode'] = $request->has('maintenance_mode');

        // TODO:
        // save to settings table / .env / key-value settings table if you already have one

        return back()->with('success', 'Settings updated successfully.');
    }
}
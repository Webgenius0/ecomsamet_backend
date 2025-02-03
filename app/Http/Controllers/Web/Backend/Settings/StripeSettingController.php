<?php

namespace App\Http\Controllers\Web\Backend\Settings;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class StripeSettingController extends Controller {
    /**
     * Display stripe settings page.
     *
     * @return View
     */
    public function index(): View {
        return view('backend.layouts.settings.stripe_settings');
    }

    /**
     * Update stripe settings.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse {
        $request->validate([
            'STRIPE_PK' => 'nullable|string',
            'STRIPE_SK' => 'nullable|string',
        ]);
        try {
            $envContent = File::get(base_path('.env'));
            $lineBreak  = "\n";
            $envContent = preg_replace([
                '/STRIPE_PK=(.*)\s/',
                '/STRIPE_SK=(.*)\s/',
            ], [
                'STRIPE_PK=' . $request->STRIPE_PK . $lineBreak,
                'STRIPE_SK=' . $request->STRIPE_SK . $lineBreak,
            ], $envContent);

            if ($envContent !== null) {
                File::put(base_path('.env'), $envContent);
            }
            return redirect()->back()->with('t-success', 'Stripe Setting Update successfully.');
        } catch (Exception) {
            return redirect()->back()->with('t-error', 'Stripe Setting Update Failed');
        }
    }
}

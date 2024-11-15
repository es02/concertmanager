<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Validator;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Plan;
use App\Models\Tenant;
use Carbon\Carbon;

class SettingsController extends Controller
{
    public function getSettings(Request $request) {
        $user =  Auth::user();
        $roles = [];
        foreach ($user->getRoleNames() as $role){
            $roles[] = $role;
        }

        Log::debug('Users roles: {roles}', ['roles' => $roles]);

        // Only allow Admin users to view settings
        if ($user->hasRole('god')) {
            $tenant = Tenant::where('fqdn', $request->host())->first();

            $plan = Plan::where('id', $tenant->plan_id)->first();
            $plans = Plan::all();

            $remaining = 0;

            Log::debug('Incoming host: {name}', ['name' => $tenant->name]);

            return Inertia::render('Settings/Show', [
                'tenant' => $tenant,
                'plan' => $plan,
                'plans' => $plans,
                'trial' => $remaining
            ]);
        } else {
            abort(403);
        }
    }

    public function updateTenantData(Request $request) {
        $user =  Auth::user();

        // Only allow Admin users to update settings
        if ($user->hasRole('god')) {
            $tenant = Tenant::find($request->id);

            $tenant->name = $request->name;
            $tenant->fqdn = $request->fqdn;                     // expected to auto fill as tenant_name.master_domain.com - changing this requires DNS in place for the new address
            $tenant->plan_id = $request->plan_id;
            $tenant->payment_token = $request->payment_token;   // Stripe token for plan payment
            $tenant->state = $request->state;

            $tenant->save();

            return back()->with('status', 'tenant-updated');
        } else {
            abort(403);
        }
    }
}

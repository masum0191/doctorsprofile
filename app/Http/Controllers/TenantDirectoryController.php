<?php
namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Setting;

class TenantDirectoryController extends Controller
{
    public function index()
    {
        // server-rendered list page (your Blade with filters/JS)
        return view('directory.index');
    }

    public function show(string $slug)
    {
        $tenant = Tenant::where(function ($w) use ($slug) {
                $w->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(`data`, '$.slug')) = ?", [$slug]);
            })->firstOrFail();

        // pull tenant-scoped settings
        tenancy()->initialize($tenant);
        try {
            $settings = Setting::query()->first();
        } finally {
            tenancy()->end();
        }

        // basic canonical SEO bits
        return view('directory.show', [
            'tenant'   => $tenant,
            'settings' => $settings,
        ]);
    }
      
}
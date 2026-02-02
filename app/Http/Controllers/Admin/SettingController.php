<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingUpdateRequest;
use App\Models\Admin\Setting;
use App\Services\Admin\SettingService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SettingController extends Controller implements HasMiddleware
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Settings Update', only: ['goToSection']),
        ];
    }
    public function create()
    {
        return view('backend.setting.form');
    }
    public function store(SettingUpdateRequest $request)
    {
        Setting::create($request->validated());
        return redirect()->route('admin.setting.index')->with('success', 'Setting created successfully');
    }
    //
    public function index()
    {
        $data['items'] = Setting::latest()->get();
        return view('backend.setting.list', $data);
    }

    public function update(SettingUpdateRequest $request)
    {
        $section = $request->getSection(url()->previous());
        $this->settingService->updateSettings($request->all(), $request->allFiles(), $section);

        return back()->with('success', 'Settings updated successfully');
    }

    /**
     * Update specific settings fields via AJAX
     */
    public function updateFields(\Illuminate\Http\Request $request)
    {
        $data = $request->except(['_token', '_method']);

        \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
            foreach ($data as $key => $value) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        });

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Settings updated successfully'
            ]);
        }

        return back()->with('success', 'Settings updated successfully');
    }

    /**
     * Navigate to specific setting section
     */
    public function goToSection($slug)
    {
        $data = [];

        // Define permissions for each section
        $permissions = [
            'site' => 'Settings Site',
            'activation' => 'Settings Activation',
            'contact' => 'Settings Contact',
            'logos-favicon' => 'Settings Logo & Favicon',
            'social-media' => 'Settings Social Media',
            'store' => 'Settings Store',
            'order' => 'Settings Order',
        ];

        // Check permissions
        if (array_key_exists($slug, $permissions) && !auth()->user()->can($permissions[$slug])) {
            abort(403, 'User does not have the right permissions.');
        }

        // Map slug to view
        $viewMap = [
            'site' => 'index',
            'contact' => 'contact',
            'logos-favicon' => 'logo_favicon',
            'social-media' => 'social_media',
            'activation' => 'activation',
            'store' => 'store',
            'order' => 'order',
        ];

        // Get view or redirect to default
        $view = $viewMap[$slug] ?? null;

        if (!$view) {
            return redirect()->route('admin.setting.edit', 'site');
        }

        return view('backend.setting.' . $view, $data);
    }

}

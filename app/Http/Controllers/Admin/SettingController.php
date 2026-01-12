<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Setting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

class SettingController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Settings Update',only: ['goToSection']),
        ];
    }
    public function create(){
        return view('backend.setting.form');
    }
    public function store(Request $request){
        $request->validate([
            'key' => 'required|unique:settings',
        ]);
        Setting::create($request->all());
        return redirect()->route('admin.setting.index')->with('success','Setting created successfully');
    }
    //
    public function index(){
        $data['items'] = Setting::latest()->get();
        return view('backend.setting.list',$data);
    }

    public function update(Request $request){
//        return $request;
        $prev_url = url()->previous();
        if(Str::contains($prev_url, 'contact')){
            $request->validate([
                'email'     => 'required',
                'phone'     => 'required',
                'whatsapp'  => 'required',
            ]);
        }
        elseif (Str::contains($prev_url, 'site')){
            $request->validate([
                'site_name'         => 'required',
                'business_name'     => 'required',
                'site_url'          => 'required',
                'short_bio'         => 'required',
                'meta_description'  => 'required',
            ]);
        }

        elseif (Str::contains($prev_url, 'user')){
            $request->validate([
                'user_verification' => 'required',
            ]);
        }


        $settings = Setting::all();
        foreach ($settings as $setting) {
            if($setting->key == 'site_logo' || $setting->key == 'site_footer_logo' || $setting->key == 'site_dark_logo' || $setting->key == 'site_favicon'){
                if ($request->hasFile($setting->key)) {
                    $request->validate([
                        $setting->key => 'mimes:jpeg,png,jpg',
                    ]);
                    $image = $request->file($setting->key);
                    $imagePath = updateImagePath($image, $setting->value, 'website-image' );

                }
                else{
                    $imagePath = $setting->value;
                }
                $setting->value = $imagePath;
            }
            elseif ($setting->key != 'developed_by' || $setting->key != 'developed_by_url'){

                if ($request->input($setting->key) !== null){
                    $setting->value = $request->input($setting->key);
                }
                elseif (Str::contains($prev_url, 'store')){
                    if($setting->key == 'address' || $setting->key == 'shop_map_location' || $setting->key == 'opening_time' || $setting->key == 'closing_time' || $setting->key == 'closed_on') {
                        $setting->value = $request->input($setting->key) ?? null;
                    }
                }

            }
            else{
                $setting->value = $request->input($setting->key);
            }

            $setting->save();
        }

        return back()->with('success', 'Settings updated successfully');

    }

    public function goToSection($slug)
    {
        $data[] = null;

        $permissions = [
            'site' => 'Settings Site',
            'activation' => 'Settings Activation',
            'contact' => 'Settings Contact',
            'logos-favicon' => 'Settings Logo & Favicon',
            'social-media' => 'Settings Social Media',
            'store' => 'Settings Store',
        ];

        // Check if the current user has the required permission
        if (array_key_exists($slug, $permissions) && !auth()->user()->can($permissions[$slug])) {
            abort(403, 'User does not have the right permissions.');
        }
        if($slug == 'site'){
            $view = 'index';
        }
        elseif ($slug == 'contact'){
            $view = 'contact';
        }
        elseif ($slug == 'logos-favicon'){
            $view = 'logo_favicon';
        }
        elseif ($slug == 'social-media'){
            $view = 'social_media';
        }
        elseif ($slug == 'activation'){
            $view = 'activation';
        }
        elseif ($slug == 'store'){
            $view = 'store';
        }
        else{
            return redirect()->route('admin.setting.edit', 'site');
        }

        return view('backend.setting.'.$view, $data);
    }
}

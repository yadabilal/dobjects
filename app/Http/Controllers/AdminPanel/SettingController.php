<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Model\Setting;


class SettingController extends Controller
{
    public $view = 'admin.setting';
    public function __construct()
    {
      $this->middleware('auth');
    }
    public function index()
    {
      $models = Setting::orderBy('id', 'desc')->where('param', '!=', 'logo')->get();
        $logo = Setting::where('param', 'logo')->first();
        $breadcrumb = Setting::where('param', 'breadcrumb')->first();

        return view($this->view.'.index', compact('models', 'logo', 'breadcrumb'));
    }

    public function save() {
      if(request()->post()) {
        $all = request()->all();
        $new_key = @$all['new_key'] ? : null;
        $new_value = @$all['new_value'] ? : null;

        if($new_key && $new_value) {
          Setting::create(['param' => $new_key, 'value' => $new_value]);
        }


        foreach ($all  as $key => $value) {
          $item = Setting::by_key_item($key);
          if($item) {
            $item->update(['value' => $value]);
          }
        }

          if(request()->file('logo')) {
              $file = request()->file('logo');
              $path = Setting::STORE_PATH;
              $pathUploaded = $file->store($path,['disk' => 'public']);

              $setting = Setting::where('param', 'logo')->first();

              if($setting) {
                  $setting->value = $pathUploaded;
                  $setting->save();
              }else {
                  Setting::create(['param' => "logo", 'value' => $pathUploaded]);
              }
          }

          if(request()->file('breadcrumb')) {
              $file = request()->file('breadcrumb');
              $path = Setting::STORE_PATH;
              $pathUploaded = $file->store($path,['disk' => 'public']);

              $setting = Setting::where('param', 'breadcrumb')->first();

              if($setting) {
                  $setting->value = $pathUploaded;
                  $setting->save();
              }else {
                  Setting::create(['param' => "breadcrumb", 'value' => $pathUploaded]);
              }
          }
      }

      return redirect()->back();
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function changeLanguage($lang)
    {
        if (in_array($lang, ['ar', 'en'])) {
            session(['system_language' => $lang]);
            session(['direction' => $lang === 'ar' ? 'rtl' : 'ltr']);
        } else {
            toastr()->error(__('Language not found'));
        }

        return redirect()->back();
    }

}

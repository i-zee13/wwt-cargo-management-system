<?php

namespace App\Http\Controllers;

use Artisan;
use File;
use Illuminate\Http\Request;

class TranslationsController extends Controller
{
    public function index(){
        return view('translations.index');
    }
    public function store(Request $request){
        $request->validate([
            'key' => 'required|string',
            'english' => 'required|string',
            'spanish' => 'required|string',
        ]); 
        $key = $request->input('key');
        $english = $request->input('english');
        $spanish = $request->input('spanish'); 
        $enFilePath = resource_path('lang/en/fields.php');
        $esFilePath = resource_path('lang/es/fields.php'); 
        $enTranslations = include $enFilePath;
        $esTranslations = include $esFilePath; 
        $enTranslations[$key] = $english;
        $esTranslations[$key] = $spanish; 
        File::put($enFilePath, "<?php\n\nreturn " . var_export($enTranslations, true) . ";");
        File::put($esFilePath, "<?php\n\nreturn " . var_export($esTranslations, true) . ";");
        Artisan::call('lang:js');
        return response()->json([
            'status' => 'success',
            'msg' => 'Translations updated Successfully', 
        ]);
    }
    public function getTranslations(){
        $enTranslations = include resource_path('lang/en/fields.php');
        $esTranslations = include resource_path('lang/es/fields.php');

       
        $translations = [];
        foreach ($enTranslations as $key => $value) {
            $translations[] = [
                'key' => $key,
                'english' => $value,
                'spanish' => $esTranslations[$key] ?? 'N/A',  
            ];
        }
        return response()->json([
            'status' => 'success',
            'msg' => 'Translations Fetched Successfully',
            'translations' => $translations
        ]); 
    }
  
}

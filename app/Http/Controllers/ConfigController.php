<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
class ConfigController extends Controller
{
    protected function setEnvValue(string $key, string $value): void
{
    $envPath = base_path('.env');
    $content = file_get_contents($envPath);

    // check if key exists
    if (preg_match("/^{$key}=.*/m", $content)) {
        // replace existing
        $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
    } else {
        // add new
        $content .= "\n{$key}={$value}";
    }

    file_put_contents($envPath, $content);
}

public function setMode(Request $request)
{
    $mode = $request->input('mode', 'test'); // default test if none passed

    // update in .env
    $this->setEnvValue('TRIPJACK_API_MODE', $mode);

    // clear config cache
    \Artisan::call('config:clear');

    return redirect()
        ->route('config')
        ->with('success', "Switched to {$mode} mode!");
}

public function config()
{
    $currentMode = config('services.tripjack_token.mode');

   // ✅ comes from config/tripjack.php
    return view('admin_dashboard.config.configuration', compact('currentMode'));
}

}

<?php

namespace Brunoabpinto\CsrfRefresh;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CsrfRefreshServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Publish JS assets
        $sourcePath = __DIR__.'/../resources/js/csrf-refresh.js';
        $destinationPath = public_path('vendor/csrf-refresh/csrf-refresh.js');

        // Auto-publish JS file on boot if it doesn't exist
        if (! File::exists($destinationPath)) {
            File::ensureDirectoryExists(public_path('vendor/csrf-refresh'));
            File::copy($sourcePath, $destinationPath);
        }

        // Manual publish JS file for updates
        $this->publishes([
            $sourcePath => $destinationPath,
        ], 'csrf-refresh-assets');

        // Register route
        Route::middleware('web')->group(function () {
            Route::get('/csrf-token/refresh', function () {
                return response()->json(['token' => csrf_token()]);
            })->name('csrf.refresh');
        });

        // Register Blade directive
        Blade::directive('csrfRefresh', function () {
            $interval = (config('session.lifetime') * 60 - 50) * 1000;
            $assetPath = asset('vendor/csrf-refresh/csrf-refresh.js');

            return "<?php echo '<script>window.csrfRefreshInterval={$interval};</script><script src=\"{$assetPath}\"></script>'; ?>";
        });
    }
}

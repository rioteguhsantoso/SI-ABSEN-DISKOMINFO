<?php
namespace App\Providers;
use App\Models\Izin;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }
    public function boot(): void
    {
        View::composer('Admin.layouts.app', function ($view) {
            $jumlahIzinMenunggu = Izin::where('status', 'menunggu')->count();
            $view->with('jumlahIzinMenunggu', $jumlahIzinMenunggu);
        });
    }
}
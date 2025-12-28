<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot() : void
    {
        View::composer('*', function ($view) {
        if (Auth::check()) {
            $user = Auth::user();

            $unreadChatCount = Message::where('receiver_id', $user->id)
                                      ->where('is_read', false)
                                      ->count();

            $view->with('unreadChatCount', $unreadChatCount);
        }
    });
    }
}

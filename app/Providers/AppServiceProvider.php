<?php

namespace App\Providers;

use App\Contracts\AiContentService;
use App\Services\HttpAiContentService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AiContentService::class, HttpAiContentService::class);
    }

    public function boot(): void {}
}

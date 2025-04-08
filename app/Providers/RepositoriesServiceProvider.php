<?php

namespace App\Providers;

use App\Repositories\Contracts\Products\ProductRepositoryInterface;
use App\Repositories\Products\ProductRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    protected array $classes = [
        ProductRepositoryInterface::class => ProductRepository::class,
    ];
    public function register(): void
    {
        foreach ($this->classes as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

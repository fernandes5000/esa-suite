<?php

namespace App\Providers;

use App\Models\EsaRequest;
use App\Models\Pet;
use App\Policies\EsaRequestPolicy;
use App\Policies\PetPolicy;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        EsaRequest::class => EsaRequestPolicy::class,
        Pet::class => PetPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
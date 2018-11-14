<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Incentives\Core\Entity;

class CustomRulesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
      Validator::extend('points', function ($attribute, $value, $parameters, $validator) {
          $entity = Entity::find($parameters[0]);
          return $value <= (int)$entity->totalpoints();

      });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

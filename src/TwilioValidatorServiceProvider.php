<?php

namespace Yamartino\TwilioValidator;

use Illuminate\Support\ServiceProvider;
// use \Kickbox\Client as Kickbox;
// use \Validator as Validator;

class TwilioValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // load translation files
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'twilio');

        $this->app->booted(function($app) {
            // get validator and translator
            $validator = $app['validator'];
            $translator = $app['translator'];

            // setup custom twilio validator
            $validator->extend('twilio', function($attribute, $value, $parameters, $validator){
                // get twilio key from users env file
                // $client = new Kickbox(env('KICKBOX_API_KEY', 'key'));
                // return $client->kickbox()->verify($value)->body['result'] !== 'undeliverable';
            }, $translator->get('twilio::validation.twilio'));

        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

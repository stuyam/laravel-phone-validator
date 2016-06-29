<?php

namespace Yamartino\TwilioValidator;

use Illuminate\Support\ServiceProvider;
use \Lookups_Services_Twilio as Twilio;

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

                // throw exception if the twilio credentials are missing from the env
                if( env('TWILIO_SID') == null || env('TWILIO_TOKEN') == null ) {
                    // throw the custom exception defined below
                    throw new TwilioCredentialsNotFoundException;
                }

                $client = new Twilio(env('TWILIO_SID'), env('TWILIO_TOKEN'));
                try {
                    // attempt to get the carrier on a phone number
                    // if an exception is thrown, no phone number was found
                    $client->phone_numbers->get($value)->carrier;
                    return true;
                } catch (\Services_Twilio_RestException $e) {
                    return false;
                }
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

// this exception is thrown in the twilio credentials are not found in the env file
class TwilioCredentialsNotFoundException extends \Exception {}


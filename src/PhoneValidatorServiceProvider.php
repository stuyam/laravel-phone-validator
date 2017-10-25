<?php

namespace StuYam\PhoneValidator;

use Illuminate\Support\ServiceProvider;
use Twilio\Rest\Client as Twilio;

class PhoneValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // allow publishing off the config
        $this->publishes([
            __DIR__.'/config/twilio.php' => config_path('twilio.php'),
        ], 'twilio');

        // load translation files
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'phone');

        // setup custom twilio validator
        $this->app->validator->extend('phone', function($attribute, $value, $parameters, $validator){
            // fetch the api key from the config - which allows the config to be cached
            $twilioSID = config('twilio.sid');
            $twilioToken = config('twilio.token');

            // throw exception if the twilio credentials are missing from the env
            if( $twilioSID == null || $twilioToken == null ) {
                throw new TwilioCredentialsNotFoundException('Please provide a TWILIO_SID and TWILIO_TOKEN in your .env file.');
            }

            $client = new Twilio($twilioSID, $twilioToken);
            try {
                // attempt to look up a phone number
                // if an exception is thrown, no phone number was found
                $client->lookups->phoneNumbers($value)->fetch();
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }, $this->app->translator->get('phone::validation.phone'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/twilio.php', 'twilio'
        );
    }
}

#Lavarel Phone Validator
A phone validator for Laravel using the FREE [Twilio phone lookup service](https://www.twilio.com/lookup)

This custom validator validates that a phone number actual exists. Not just if it has a specific format or not, but if the phone number is a real registered phone number. It is smart enough to handle formated numbers like ```(123)-555-1234``` and unfromated numbers like ```1235551234``` so users can enter in a phone number however they are most comfortable.

Also see: [Laravel Kickbox Validator](https://github.com/stuyam/laravel-kickbox-validator) for email address validation.

###Step 1
Install via composer:

```
composer require stuyam/laravel-phone-validator
```

###Step 2
Add to your ```config/app.php``` service provider list:

```php
StuYam\PhoneValidator\PhoneValidatorServiceProvider::class
```

###Step 3
Add Twilio credentials to your .env file:

```
TWILIO_SID=xxxxxxxx
TWILIO_TOKEN=xxxxxxxx
```
(If you don't have a Twilio account you can go to [Twilio.com](https://www.twilio.com/) and make a free account)


###Usage
Add the string 'phone' to a form request rules or validator like so:

```php
<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PhoneFormRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        	// this means the phone input will be validated that
        	// it is required and that it is an ACTUAL phone number
            'phone' => 'required|phone'
        ];
    }
}

```

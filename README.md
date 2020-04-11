# Zenziva SMS Library
Zenziva SMS Online Gateway Library based on Zenziva [Documentation](https://www.zenziva.id/dokumentasi/)


## Requirements

- PHP with Curl Extension Support
- Zenziva Account

## How to use
### Install with Composer
Recommended install via [composer](http://getcomposer.org)
```bash
composer require faytranevozter/zenziva-lib
```

### Usage
All example below is placed under autoload composer
```php
require 'vendor/autoload.php';
use Faytranevozter\Zenziva\Sms;
// your code here ...
```

#### Simple way
```php
$sms = new Sms('faytranevozter', '123456');
// or
$sms = new Sms();
$sms->username('faytranevozter'); // chainable
$sms->password('123456'); // chainable
// send sms
$sms->send('089765432123', 'Helaw!'); // send(number, text, otp)
```
Default type is `reguler` if 3rd parameter not set.

#### Masking
```php
$sms = new Sms('faytranevozter', '123456', 'masking');
$sms->send('089765432123', 'Helaw! this is masking sms');
// or
$sms = new Sms('faytranevozter', '123456');
$sms->type('masking'); // chainable
// send sms
$sms->send('089765432123', 'Helaw! this is masking sms'); // send(number, text, otp)
```
Default type is `reguler` if 3rd parameter not set.

#### SMS Center
```php
$sms = new Sms('faytranevozter', '123456', 'sms_center');
$sms->subdomain('mysubdomain'); // chainable, [required for sms_center]
$sms->send('089765432123', 'Helaw! this is from sms_center');
// or
$sms = new Sms('faytranevozter', '123456');
$sms->type('sms_center'); // chainable
$sms->subdomain('mysubdomain'); // chainable, [required for sms_center]
// send sms
$sms->send('089765432123', 'Helaw! this is from sms_center'); // send(number, text, otp)
```
Default type is `reguler` if 3rd parameter not set.

#### OTP SMS
Zenziva now apply special treatment for SMS OTP. See [#Zenziva Docs](https://www.zenziva.id/dokumentasi/#1487744370576-71f03366-9c88)
> Untuk mengirim SMS OTP, wajib menambahkan parameter type=otp
```php
$sms = new Sms('faytranevozter', '123456', 'masking');
$sms->send('089765432123', 'Helaw! this is masking sms', TRUE);
// or
$sms = new Sms('faytranevozter', '123456');
$sms->otp(TRUE); // chainable
// send sms
$sms->send('089765432123', 'Helaw! this is masking sms'); // send(number, text, otp)
```

#### Chaining
```php
$sms = new Sms();
$sms->username('faytranevozter')
    ->password('123456')
    ->type('masking')
    ->to('089765432123')
    ->message('Helaw!')
    ->send();
```

#### Handling Response
##### Checking SMS Status
```php
$sms = new Sms('faytranevozter', '123456');
$is_send = $sms->send('089765432123', 'Helaw! this is my sms');
if ($is_send) {
    echo "Sms sent!";
} else {
    echo "Uh-oh! Failed to send sms";
}
```
##### Get Response
```php
$sms = new Sms('faytranevozter', '123456');
$sms->send('089765432123', 'Helaw! this is my sms');
$sms->send('082341561273', 'Helaw! this is my second sms');

// get all response
print_r($sms->responses()); // return array of response

// get last response
print_r($sms->last_response()); // return last response

```
##### Get Error
```php
$sms = new Sms('faytranevozter', '123456');
$sms->send('089765432123', 'Helaw! this is my sms');
$sms->send('082341561273', 'Helaw! this is my second sms');

// get all error
print_r($sms->errors()); // return array of error

// get last error
print_r($sms->last_error()); // return last error

```

#### Using Codeigniter Framework
Creating library `Zenziva.php`
```php
use Faytranevozter\Zenziva\Sms;

class Zenziva extends Sms {
    function __construct($params=array()) {
        parent::__construct(...$params);
    }
}
```
Load Zenziva library from controller
```php
...

$this->load->library('Zenziva', ['faytranevozter', '123456', 'reguler']);
$this->zenziva->send('089765432123', 'Helaw! this is my sms');

...
```

## Credits and License
### Author
Fahrur Rifai [fahrur.dev](https://www.fahrur.dev)  
Twitter [@faytranevozter](https://twitter.com/faytranevozter)

### License
MIT License

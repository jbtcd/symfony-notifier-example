[![Stand With Ukraine](https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/banner2-direct.svg)](https://vshymanskyy.github.io/StandWithUkraine/)

# Symfony Notifier SMS Example 📱💬

This project provides an example implementation of the [symfony notifier bundle][3] with [twilio][1] as the SMS provider.

## Preparations

```bash
composer install
```

Create a [twilio][1] account and replace arguments in [local .env][2] file

## Run the command

Just call the send-sms command and replace phone-number with the phone number which should receive the message 
```bash
php bin/console app:send-sms phone-number
```

You also can provide the message which should send to the given phone number
```bash
php bin/console app:send-sms phone-number 'This is the message.'
```

[1]: https://console.twilio.com/
[2]: .env
[3]: https://symfony.com/doc/current/notifier.html

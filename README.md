# Post SendGrid

[![LICENSE](https://img.shields.io/github/license/pittica/site-webhooks.svg)](LICENSE)
[![packagist](https://img.shields.io/badge/packagist-pittica%2Fpost--sendgrid-brightgreen.svg)](https://packagist.org/packages/pittica/site-webhooks)
![PHP from Packagist](https://img.shields.io/packagist/php-v/pittica/site-webhooks)

This project has been created to handle webhooks.

This is an internal project.

## Installation

You can install _site-webhooks_ using [Composer](https://getcomposer.org).

``` bash
composer create-project --prefer-dist pittica/site-webhooks
```

## Configuration

Create and edit a **config/app.php** file.

### Example

``` php
<?php

return [
    'token' => '',
    'keys' => [
        'GitHub' => [
			'username' => '',
			'token' => ''
		]
    ]
];
```

## Copyright

© 2021 [Pittica S.r.l.s.](https://pittica.com)
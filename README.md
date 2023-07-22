# CodeIgniter Passage

Basic integration for [Passage](https://passage.1password.com/) - passwordless authentication powered by passkeys.

### Installation

In the example below we will assume, that files from this project will be located in `app/ThirdParty/passage` directory.

Download this project and then enable it by editing the `app/Config/Autoload.php` file and adding the `Michalsn\CodeIgniterPassage` namespace to the `$psr4` array, like in the below example:

```php
<?php

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

class Autoload extends AutoloadConfig
{
    // ...
    public $psr4 = [
        APP_NAMESPACE => APPPATH, // For custom app namespace
        'Config'      => APPPATH . 'Config',
        'Michalsn\CodeIgniterPassage' => APPPATH . 'ThirdParty/passage/src',
    ];

    // ...
```
Also add the required helper to the same file under `$files` array:

```php
    // ...
    public $files = [
        APPPATH . 'ThirdParty/passage/src/Common.php',
    ];

    // ...
```

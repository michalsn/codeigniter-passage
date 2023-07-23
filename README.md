# CodeIgniter Passage

Basic integration for [Passage](https://passage.1password.com/) - passwordless authentication powered by passkeys.

[![PHPUnit](https://github.com/michalsn/codeigniter-passage/actions/workflows/phpunit.yml/badge.svg)](https://github.com/michalsn/codeigniter-passage/actions/workflows/phpunit.yml)
[![PHPStan](https://github.com/michalsn/codeigniter-passage/actions/workflows/phpstan.yml/badge.svg)](https://github.com/michalsn/codeigniter-passage/actions/workflows/phpstan.yml)
[![Deptrac](https://github.com/michalsn/codeigniter-passage/actions/workflows/deptrac.yml/badge.svg)](https://github.com/michalsn/codeigniter-passage/actions/workflows/deptrac.yml)

![PHP](https://img.shields.io/badge/PHP-%5E8.0-blue)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-%5E4.3-blue)

### Installation

#### Composer

    composer require michalsn/codeigniter-passage

#### Manually

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

### Configuration

- Follow the [quickstart](https://docs.passage.id/getting-started/quickstart)
- Create an app in the Passage Console
    - `php spark passage:publish` - to copy config file to the `App` namespace
    - Fill the config variables or use .env file
- Add a Passage Element to your frontend
- You can use `passageStateless` filter as your middleware implementation

### Helper functions

- `passageAppId()` will return your AppId
- `passageId()` will return your user id (if you're using `passageStateless` filter), you can also set this yourself via: `passageId($userId)`

### Example

```php
<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;
use Michalsn\CodeIgniterPassage\Exceptions\PassageException;

class Home extends BaseController
{
    public function index()
    {
        try {
            $passage = service('passage');
            $userId = $passage->authenticateRequest($this->request);
            $data = ['user' => $passage->user->get($passageId)];
        } catch (PassageException $e) {
            throw PageNotFoundException::forPageNotFound($e->getMessage());
        }

        return view('home/index', $data);
    }
}
```

# berbeflo/Config
This library offers a simple interface for the access of config arrays. While there are a lot of fantastic libraries out there that provide similar functionality, most of them are far more complex as I need them.

## Installation
```
clone git@github.com:berbeflo/Config.git
composer install [--no-dev]
```

## The Config class
This class receives an array from a given file.
The access via the method `get` happens via a dot-separated array path. E.g: `get('database.host')` will return `'localhost'`. This method's second parameter is the value to return if the given config path isn't set.
### Example
```
# file /config/path/database.php
return [
    'database' => [
        'host' => 'localhost',
        'user' => 'root',
        'password' => 'root',
        'database' => 'dontusedefaultpasswords'
    ]
];
```
```
use berbeflo\Config\Config;
$databaseConfig = new Config('/config/path/database.php');
$databaseConfig->get('database.host'); // -> 'localhost'
$databaseConfig->get('database.port'); // --> null
$databaseConfig->get('database.port', 3306); // -> 3306
$databaseConfig->getAll(); // ['database' => ['host' => 'localhost', ...]]

```
## The ConfigRepository
This is a static interface for the access to multiple configuration files. With the method `addSearchPath` you can add a directory, which contains config files.

The method `add` awaits a filename (without extension). The repository then searches for the given filename inside of the given search paths. The files should have the extension `.php`.

Access to the config data is similar to accessing the `Config` objects directly. But the first part of the path has to be the filename, where the config has to be.

### Example
```
# file /config/path/1/database.php
return [
    'database' => [
        'host' => 'localhost',
        'user' => 'root',
        'password' => 'root',
        'database' => 'dontusedefaultpasswords'
    ]
];
```

```
# file /config/path/2/log.php
return [
    'level' => 'debug'
];
```

```
use berbeflo\Config\ConfigRepository;

ConfigRepository::addSearchPath('/config/path/1/');
ConfigRepository::addSearchPath('/config/path/2/');
ConfigRepository::add('database');
ConfigRepository::add('log');

ConfigRepository::get('log'); // --> ['level' => 'debug']
ConfigRepository::get('log.level'); // --> 'debug'
ConfigRepository::get('log.file'); // --> null
ConfigRepository::get('log.file', 'log.txt'); // --> 'log.txt'
```

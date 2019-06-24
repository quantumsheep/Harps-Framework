# Harps Framework
This framework is an (old) attempt to create a light and practical PHP framework in order to enhance the lack of beauty and fun in PHP.

## Installation
To install Harps you will first need [Composer](https://getcomposer.org/download/).

Then can install the framework by using the pre-made skeleton:
```bash
composer create-project "harps/harps-skeleton" app
```

Or by using the components in the framework by adding it in your Composer's dependencies:
```bash
composer install "harps/harps"
```

## How to use Harps?
If you don't use the skeleton, you will need to add those lines in your `index.php` file:
```php
require_once(__DIR__ . "/vendor/autoload.php");
Boot::Harps();
```

After that you have two directory, `App` and `Config`. In `App` there are all the `Controllers`, `Models`, `Managers`, `Views` and `Ressources`. In `Config` there is the parameters and the routes configurations.

And that's it, you can now use Harps! Look at the documentation for more informations.

# Laravel Str Facade TR Extend

<p align="center">
<a href="https://packagist.org/packages/X-Laravel/model-settings-bag" rel="nofollow"><img src="https://img.shields.io/packagist/v/X-Laravel/model-settings-bag" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/X-Laravel/model-settings-bag" rel="nofollow"><img src="https://img.shields.io/packagist/dt/X-Laravel/model-settings-bag" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/X-Laravel/model-settings-bag" rel="nofollow"><img src="https://poser.pugx.org/X-Laravel/model-settings-bag/dependents.svg" alt="Dependents"></a>
<a href="https://packagist.org/packages/X-Laravel/model-settings-bag" rel="nofollow"><img src="https://img.shields.io/packagist/l/X-Laravel/model-settings-bag" alt="License"></a>
</p>

<p align="center">
<a href="https://scrutinizer-ci.com/g/X-Laravel/model-settings-bag/build-status/master" rel="nofollow"><img src="https://scrutinizer-ci.com/g/X-Laravel/model-settings-bag/badges/quality-score.png?b=master" title="Scrutinizer Code Quality"></a>
<a href="https://styleci.io/repos/322733350" rel="nofollow"><img src="https://styleci.io/repos/322733350/shield?branch=master" alt="StyleCI"></a>
</p>

## Introduction

Add simple but flexible multiple settings to your Laravel models.

## Requirements

PHP >=7.0. Other than that, this library has no requirements.

## Installation
```bash
$ composer require x-laravel/model-settings-bag:"~1.0"
```

## Integration
### Single
#### 1. Add a JSON settings field to your model's migration.
_create_users_table.php_
```php
Schema::create('users', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->json('settings')->nullable();
    $table->rememberToken();
    $table->timestamps();
});
```

#### 2. Use the trait `XLaravel\ModelSettingsBag\HasSettingsBag` within your model.
_User.php_
```php
use XLaravel\ModelSettingsBag\HasSettingsBag;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable 
{
    use HasSettingsBag;
     
    // truncated for brevity..
}
```

### Multiple
#### 1. Add a JSON settings field to your model's migration.
_create_user_theme_settings_table.php_
```php
Schema::create('user_theme_settings', function (Blueprint $table) {
    $table->id();
    $table->unsignedInteger('user_id');
    $table->json('settings')->nullable();
    $table->rememberToken();
    $table->timestamps();
});
```

#### 2. Use the trait `XLaravel\ModelSettingsBag\HasSettingsBag` within your other setting model.
_UserThemeSetting.php_
```php
use XLaravel\ModelSettingsBag\HasSettingsBag;
use Illuminate\Database\Eloquent\Model;

class UserThemeSetting extends Model 
{
    use HasSettingsBag;
}
```

#### 3. Use the trait `XLaravel\ModelSettingsBag\HasSettingsBag` within your model.
_User.php_
```php
class User extends Model 
{
    use HasSettingsBag;

    public function themeSettings()
    {
        return $this->hasOne(UserThemeSetting::class);
    }
}
```

## Usage
### 1.) Get all of the model's settings.
```php
$user = App\User::first();

$user->settings()->all();    // Returns an array of the user's settings.
$user->settings('theme')->get();    // Returns an array of a user's theme settings.
```

### 2.) Get a specific setting.
```php
$user = App\User::first();

$user->settings()->get('some.setting');
$user->settings()->get('some.setting', $defaultValue); // With a default value.

$user->settings('theme')->get('layout.boxed');
$user->settings('theme')->get('layout.boxed', $defaultValue); // With a default value.
```

### 3.) Add or update a setting.
```php
$user = App\User::first();

$user->settings()->update('some.setting', 'new value');
$user->settings('theme')->update('layout.boxed', 'new value');
```

### 4.) Determine if the model has a specific setting.
```php
$user = App\User::first();

$user->settings()->has('some.setting');
$user->settings('theme')->has('layout.boxed');
```

### 5.) Remove a setting from a model.
```php
$user = App\User::first();

$user->settings()->delete('some.setting');
$user->settings('theme')->delete('layout.boxed');
```

### 6.) Set the default settings for a new model.

If you define `$defaultSettings` as an array property on your model, we will use its value as the default settings for
any new models that are created *without* settings.

_User.php_
```php
use XLaravel\ModelSettingsBag\HasSettingsBag;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable 
{
    use HasSettingsBag;

    /**
     * The model's default settings.
     * 
     * @var array
     */
    protected $defaultSettings = [
    	'homepage' => '/profile'
    ];

    // truncated for brevity..
}
```

### 7.) Specify the settings that are allowed.

If you define `$allowedSettings` as an array property then only settings which match a value within
the `$allowedSettings` array will be saved on the model.

_User.php_
```php
use XLaravel\ModelSettingsBag\HasSettingsBag;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable 
{
    use HasSettingsBag;

    /**
     * The model's allowed settings.
     * 
     * @var array
     */
    protected $allowedSettings = ['homepage'];

    // truncated for brevity..
}
```

### 8.) Using another method name other than settings()

If you prefer to use another name other than `settings` , you can do so by defining a `$mapSettingsTo` property. This simply maps calls to the method (such as `config()`) to the `settings()` method.

_User.php_
```php
use XLaravel\ModelSettingsBag\HasSettingsBag;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable 
{
    use HasSettingsBag;

    /**
     * The settings field name.
     * 
     * @var string
     */
    protected $mapSettingsTo = 'config';

    // truncated for brevity..
}
```

## License

This package is open source software licensed under the [MIT license](https://opensource.org/licenses/MIT).

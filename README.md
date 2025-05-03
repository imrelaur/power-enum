# Laravel Power Enum

Laravel Power Enum is a package that provides a powerful and flexible way to work with PHP enums in Laravel applications. It extends the functionality of PHP enums by adding useful methods and features, making it easier to manage and manipulate enum values.

Works with [Laravel](https://laravel.com/) and [Filament PHP](https://filamentphp.com/). 

[![Latest Stable Version](https://poser.pugx.org/imrelaur/power-enum/v/stable)](https://packagist.org/packages/imrelaur/power-enum)
[![License](https://poser.pugx.org/imrelaur/power-enum/license)](https://packagist.org/packages/imrelaur/power-enum)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.2-4F5B93.svg)](https://www.php.net/)
[![Laravel Version](https://img.shields.io/badge/Laravel-%3E%3D%2010.0-FF2D20.svg)](https://laravel.com/)

Table of Contents
=================

* [Install](#install)
* [Usage](#usage)
    * [Power Enum in Laravel](#power-enum-in-laravel)
    * [Power Enum in Filament](#power-enum-in-filament)
* [Methods](#methods)
    * [fromName](#fromname)
    * [tryFromName](#tryfromname)
    * [fromRequest](#fromrequest)
    * [rule](#rule)
    * [count](#count)
    * [collect](#collect)
    * [names](#names)
    * [values](#values)
    * [options](#options)
    * [only](#only)
    * [except](#except)
    * [is](#is)
    * [isNot](#isnot)
    * [isAny](#isany)
    * [isNotAny](#isnotany)

## Install

```
composer require imrelaur/power-enum
```

## Usage

### Power Enum in Laravel

```php
<?php

namespace App\Enums;

use PowerEnum\PowerEnum;

enum Status: string
{
    use PowerEnum;

    case Draft = 'draft';
    case Hidden = 'hidden';
    case Published = 'published';
}
```

### Power Enum in Filament

```php
<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use PowerEnum\PowerEnum;

enum Status: string implements HasLabel
{
    use PowerEnum;

    case Draft = 'draft';
    case Hidden = 'hidden';
    case Published = 'published';

    public function getLabel(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Hidden => 'Hidden',
            self::Published => 'Published',
        };
    }
}

// Using power enum in a select field.
Select::make('status')
    ->options(Status::options(except: Status::Hidden)),
    // OR
    ->options(Status::options(only: [Status::Published, Status::Draft])),
```

## Methods

### fromName

Returns the enum case from the enum name. When the name is missing or is invalid, then it throws a `ValueError` exception.

```php
Status::fromName('Published'); // Status::Published

Status::fromName('invalid'); // throws \ValueError
```

### tryFromName

Returns the enum case from the enum name. When the name is missing or is invalid, then it returns `null`.

```php
Status::tryFromName('Published'); // Status::Published

Status::tryFromName('invalid'); // null
```

### fromRequest

Returns the enum case from the request, when is a valid value. Optionally, you can provide a default value and use that instead of `null`.

```php
Status::fromRequest('status'); // self|null

Status::fromRequest('status', default: Status::Draft); // self
```

### rule

Returns the validation rule for the enum. Returns an instance of `Illuminate\Validation\Rules\Enum`.

```php
Status::rule(); // \Illuminate\Validation\Rules\Enum

Status::rule()->only([
    Status::Published,
    Status::Draft,
]);

Status::rule()->except(Status::Hidden);
```

### count

Counts the number of enum cases.

```php
Status::count(); // 3
```

### collect

The collect method returns a new `Illuminate\Support\Collection` instance with the enum cases.

```php
Status::collect(); // \Illuminate\Support\Collection
```

### names

```php
Status::names(); // ['Published', 'Hidden', 'Draft']

Status::names(only: Status::Published); // ['Published']
Status::names(only: [Status::Published, Status::Draft]); // ['Published', 'Draft']

Status::names(except: Status::Hidden); // ['Published', 'Draft']
Status::names(except: [Status::Hidden, Status::Draft]); // ['Published']
```

### values

```php
Status::values(); // ['published', 'hidden', 'draft']

Status::values(only: Status::Published); // ['published']
Status::values(only: [Status::Published, Status::Draft]); // ['published', 'draft']

Status::values(except: Status::Hidden); // ['published', 'draft']
Status::values(except: [Status::Hidden, Status::Draft]); // ['published']
```

#### Example: Using in a Laravel Query Builder

You can use the `values` method to filter the query builder results based on the enum values.

```php
class Post extends Model
{
    public function scopeVisible(Builder $query): void
    {
        $query->whereIn('status', Status::values(except: Status::Hidden));
        // OR
        $query->whereIn('status', Status::values(only: [Status::Published, Status::Draft]));
    }
}
```

### options

Returns an array of options for the enum, with the keys being the values of the enum and the values being the names of
the enum.

When `getLabel` method is implemented, it will be used to get the label for the enum value. Otherwise, the name of the
enum will be converted to a headline format, using `Str::headline` function
from [Laravel String helper class](https://laravel.com/docs/strings#method-str-headline).

```php
Status::options(); // ['published' => 'Published', 'hidden' => 'Hidden', 'draft' => 'Draft']

Status::options(only: Status::Published); // ['published' => 'Published']
Status::options(only: [Status::Published, Status::Draft]); // ['published' => 'Published', 'draft' => 'Draft']

Status::options(except: [Status::Hidden, Status::Draft]); // ['published' => 'Published']
Status::options(except: Status::Hidden); // ['published' => 'Published', 'draft' => 'Draft']
```

#### Example: Using in a Filament form as a select field options

You can use the `options` method to get options for the select field. Optionally you can filter them using `only` or `except` parameters.

```php
class PostResource extends Resource
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('type')
                    ->required()
                    ->options(Status::options(except: Status::Hidden))
                    // OR
                    ->options(Status::options(only: [Status::Published, Status::Draft]))
            ]);
    }
}
```

### only

Returns the enum cases only the ones provided. You can provide a single enum case, an array of enum cases, or multiple enum cases.

```php
Status::only(Status::Hidden); // [Status::Hidden]

Status::only(Status::Hidden, Status::Draft); // [Status::Hidden, Status::Draft]

Status::only([Status::Hidden, Status::Draft]); // [Status::Hidden, Status::Draft]
```

### except

Returns the enum cases except the ones provided. You can provide a single enum case, an array of enum cases, or multiple enum cases.

```php
Status::except(Status::Hidden); // [Status::Published, Status::Draft]

Status::except(Status::Hidden, Status::Draft); // [Status::Published]

Status::except([Status::Hidden, Status::Draft]); // [Status::Published]
```

### is

```php
Status::Published->is(Status::Draft); // false
Status::Published->is(Status::Published); // true
```

### isNot

```php
Status::Published->isNot(Status::Draft); // true
Status::Published->isNot(Status::Published); // false
```

### isAny

```php
Status::Published->isAny(Status::Draft); // false
Status::Published->isAny(Status::Published); // true

Status::Published->isAny(Status::Published, Status::Draft); // true
Status::Published->isAny(Status::Draft, Status::Hidden); // false

Status::Published->isAny([Status::Draft, Status::Hidden]); // false
Status::Published->isAny([Status::Published, Status::Hidden]); // true
```

### isNotAny

```php
Status::Published->isNotAny(Status::Draft); // true
Status::Published->isNotAny(Status::Published); // false

Status::Published->isNotAny(Status::Published, Status::Draft); // false
Status::Published->isNotAny(Status::Draft, Status::Hidden); // true

Status::Published->isNotAny([Status::Draft, Status::Hidden]); // true
Status::Published->isNotAny([Status::Published, Status::Hidden]); // false
```

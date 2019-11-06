# Nova Embed 

Embed field for Laravel Nova. With this package you can embed from Youtube, Vimeo, Facebook, DailyMotion, SoundCloud, Instagram and a lot of more. Uses [Oscarotero Embed](https://github.com/oscarotero/Embed) to get the data.

![preview](https://user-images.githubusercontent.com/74367/53722300-807ff800-3e65-11e9-8675-bd28ba33a4e1.png)

![preview2](https://user-images.githubusercontent.com/74367/53722303-8249bb80-3e65-11e9-830c-9d4c63a0cee8.png)

## Installation

You can install the package in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:


```bash
composer require ericlagarda/nova-embed
```

Then, register your new field in the `fields` method of the `Resource` class.

```php

use EricLagarda\NovaEmbed\Embed;

public function fields()
{
    return [
        // ...
 
        Embed::make('Embed')
                ->ajax(),
    ];
}
```


## Update

Please change your namespace: `Ericlagarda` to `EricLagarda`

## Field options

* `->ajax()`: Load the embed with ajax. By default loads with the field
* `->cache()`: You can set the time of the cache. By default a month.
* `->viewInIndex()`: Click on the image to open a modal with the embed.

Cache will set a month in minutes for Laravel 5.7 and below, and a month in seconds for Laravel 5.8 and up.

## Screenshots

![preview2](https://user-images.githubusercontent.com/74367/53722332-968db880-3e65-11e9-86d1-6cc77717b21e.png)

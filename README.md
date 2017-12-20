## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
$ php composer.phar require faboslav/yii2-material-tabs "dev-master"
```

or add

```
"faboslav/yii2-material-tabs": "dev-master"
```

to the ```require``` section of your `composer.json` file.

## Preview
![MaterialTabsPreview](https://j.gifs.com/voY5Er.gif)

## Usage

### MaterialNavBar

```php
use faboslav\materialtabs\MaterialTabs

echo MaterialTabs::widget([
    'items' => [
        [
            'label' => '<i class="material-icons">home</i>',
            'content' => 'Tab one content.'
        ],
        [
            'label' => 'Tab two',
            'content' => 'Tab two content<br><br>Text<br><br>Text',
        ],
        [
            'label' => 'Tab three',
            'content' => 'Tab three content<br>Text<br>Text',
            'active' => true
        ],
        [
            'icon' => '<i class="material-icons">people</i>',
            'label' => 'Tab four',
            'content' => 'Tab four content<br><br>Text<br>Text'
        ],
        [
            'label' => 'Tab five',
            'content' => 'Tab five content<br>Text'
        ],
    ]
]);
```
## Licence

The MIT License (MIT)
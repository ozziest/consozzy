Consozzy
========

Consozzy is a simple console library for your console applications. It has got simple router for your library and class. 

## Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
    "require": {
        "ozziest/consozzy": "2.*",
    }
}
```

```bash 
$ composer install
```

### Usage
```bash
$ php console publisher:library:command:method
```

### Custom Commands

```php 
namespace Publisher\Library;

class Mycommand {
    
    use \Ozziest\Consozzy\Screen;

    public function operation()
    {
        $this->write('This is a command on based the consozzy.');
    }

}
```

```
$ php console publisher:library:mycommand:operation
```

### Screen Trait

You can use methods of screen;

* `write($text, $color)`
* `writeln($text, $color)`
* `prompt()`

### Sample Command Ready 

```php 
class Mycommand {
    
    use \Ozziest\Consozzy\Screen;

    public function operation()
    {
        $command = readline($this->prompt());
    }

}
```

### Core Commands

* `exit`

### Colors

* `black`
* `blue`
* `green`
* `cyan`
* `red`
* `purple`
* `brown`
* `yellow`
* `white`


### License 

[MIT](http://opensource.org/licenses/MIT)



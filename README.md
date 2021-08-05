PHP DataTransformer
=

A simple library that allows transform any kind of data to native php data or whatever


## Content

* [What is a DataTransformer](#what-is-a-datatransformer)
* [Installation](#installation)
* [Usage](#usage)

### What is a DataTransformer

It is just an abstraction layer to transform data between two data types. Perfect for those API bridges data transformation between your system and incoming data.

It could handle json incoming data and then transform it to a class or whatever


### Installation

```php
composer require didix16/php-datatransformer
```

### Usage

Example of how to use it. This is a transformer that gets a string data value and transforms it to a
PHP DateTime

```php

use didix16\DataTransformer\DataTransformer;

class DateTransformer extends DataTransformer {

    const FORMATS = [
        DateTimeInterface::ATOM,
        DateTimeInterface::COOKIE,
        DateTimeInterface::ISO8601,
        DateTimeInterface::RFC822,
        DateTimeInterface::RFC850,
        DateTimeInterface::RFC1036,
        DateTimeInterface::RFC1123,
        DateTimeInterface::RFC2822,
        DateTimeInterface::RFC3339,
        DateTimeInterface::RFC3339_EXTENDED,
        DateTimeInterface::RSS,
        DateTimeInterface::W3C
    ];

    /**
     * @var string
     */
    protected $fromFormat;

    /**
     * @var DateTimeZone
     */
    protected $toTimezone;

    public function __construct($fromFormat='Y-m-d', $toTimezone= 'Europe/Madrid')
    {
        $this->fromFormat = $fromFormat;
        $this->toTimezone = new DateTimeZone($toTimezone);
    }

    protected function transform(&$value)
    {
        if(empty($value)){
            $value = null;
            return;
        }

        $date = $this->convertToDate($value);
        if($this->assertClass($date, DateTime::class)){

            $this->dateToTimezone($date);
            $value = $date;
        } else {
            throw new Exception(
                sprintf("An error was ocurred while transforming the value '%s' into date:\n
                Expected to be an instance of '%s', founded '%s'", $value, DateTime::class, gettype($date))
            );
        }
    }

    /**
     * Tries to convert the passed value to a date by checking all possible DateTimeInterface formats first.
     * If no one satisfies then try using the specified format at constructor
     */
    private function convertToDate(&$value){

        foreach(self::FORMATS as $format){

            $t = DateTime::createFromFormat($format, $value);
            if($t) return $t;
        }

        try {
            return new DateTime($value);
        } catch(Exception $e){
            return DateTime::createFromFormat($this->fromFormat, $value);
        }
    }

    private function dateToTimezone(DateTime &$value){

        $currentTimezone = $value->getTimezone()->getName();
        $toTimezone = $this->toTimezone->getName();

        if($currentTimezone !== $toTimezone){
            $value->setTimezone($this->toTimezone);
        }
    }

}

```

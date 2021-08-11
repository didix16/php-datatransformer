<?php


namespace didix16\DataTransformer;


use didix16\DataTransformer\TransformerInterface;

abstract class DataTransformer implements TransformerInterface
{
    /**
     * Make invokable transformer as a function
     */
    public function __invoke(&$value)
    {
        $this->transform($value);
    }

    /**
     * Transform the incoming value to whaterver you want
     */
    protected function transform(&$value){
        return;
    }

    /**
     * @param $value
     * @param string $className
     * @return bool
     */
    public final function assertClass(&$value, $className = 'stdClass'){

        return $this->assertObject($value) && $value instanceof $className;
    }

    /**
     * Check if value is an object
     * @param $value
     * @return bool
     */
    protected function assertObject(&$value): bool { return is_object($value); }

    /**
     * Check if a value is a PHP primitive different from object.
     * @param $value
     * @return bool
     */
    public final function assertPrimitive(&$value): bool
    {

        switch (gettype($value)){
            case 'integer':
            case 'double':
            case 'array':
            case 'resource':
            case 'resource (closed)':
            case 'NULL':
            case 'boolean':
            case 'string':
                return true;
            default:
                return false;
        }
    }

    public final function assertString(&$value): bool
    {
        return is_string($value);
    }

    public final function assertDouble(&$value): bool
    {
        return is_double($value);
    }

    public final function assertArray(&$value): bool
    {
        return is_array($value);
    }

    public final function assertResource(&$value): bool
    {
        return is_resource($value);
    }

    public final function assertResourceClosed(&$value): bool
    {
        return gettype($value) === 'resource (closed)';
    }

    public final function assertNull(&$value): bool
    {
        return is_null($value);
    }

    public final function assertBoolean(&$value): bool
    {
        return is_bool($value);
    }

}
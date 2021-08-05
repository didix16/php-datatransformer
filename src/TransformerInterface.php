<?php


namespace didix16\DataTransformer;


interface TransformerInterface
{
    /**
     * Make invokable transformer as a function
     */
    public function __invoke(&$value);
    public function  assertPrimitive(&$value);
    public function  assertClass(&$value, $class);
}
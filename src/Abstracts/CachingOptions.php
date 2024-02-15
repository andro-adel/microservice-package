<?php

namespace DD\MicroserviceCore\Abstracts;

abstract class CachingOptions
{
    protected array $options = [];

    /**
     * @return array
     */
    public function getOptions(): array
    {
        $optArray = [];
        foreach ($this->options as $index => $value) {
            if (gettype($index) !== 'integer') {
                $optArray[] = $index;
            }
            $optArray[] = $value;
        }
        return $optArray;
    }
}

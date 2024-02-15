<?php

namespace DD\MicroserviceCore\Classes;

use DD\MicroserviceCore\Abstracts\CachingOptions;

class ExpireOptions extends CachingOptions
{
    public function setIfNew(): ExpireOptions
    {
        if ($index = array_search('XX', $this->options)) {
            unset($this->options[$index]);
        }
        if ($index = array_search('GT', $this->options)) {
            unset($this->options[$index]);
        }
        if ($index = array_search('LT', $this->options)) {
            unset($this->options[$index]);
        }
        $this->options[] = 'NX';
        return $this;
    }

    public function setIfExist(): ExpireOptions
    {
        if ($index = array_search('NX', $this->options)) {
            unset($this->options[$index]);
        }
        if ($index = array_search('GT', $this->options)) {
            unset($this->options[$index]);
        }
        if ($index = array_search('LT', $this->options)) {
            unset($this->options[$index]);
        }
        $this->options[] = 'XX';
        return $this;
    }

    public function setIfGreaterThanCurrent(): ExpireOptions
    {
        if ($index = array_search('NX', $this->options)) {
            unset($this->options[$index]);
        }
        if ($index = array_search('XX', $this->options)) {
            unset($this->options[$index]);
        }
        if ($index = array_search('LT', $this->options)) {
            unset($this->options[$index]);
        }
        $this->options[] = 'GT';
        return $this;
    }

    public function setIfLessThanCurrent(): ExpireOptions
    {
        if ($index = array_search('NX', $this->options)) {
            unset($this->options[$index]);
        }
        if ($index = array_search('XX', $this->options)) {
            unset($this->options[$index]);
        }
        if ($index = array_search('GT', $this->options)) {
            unset($this->options[$index]);
        }
        $this->options[] = 'LT';
        return $this;
    }
}

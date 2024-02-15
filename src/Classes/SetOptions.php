<?php

namespace DD\MicroserviceCore\Classes;

use DD\MicroserviceCore\Abstracts\CachingOptions;

class SetOptions extends CachingOptions
{
    public function setExpiration(int $ttl): SetOptions
    {
        if (isset($this->options['PX'])) {
            unset($this->options['PX']);
        }
        if (isset($this->options['EXAT'])) {
            unset($this->options['EXAT']);
        }
        if (isset($this->options['PXAT'])) {
            unset($this->options['PXAT']);
        }
        if ($index = array_search('KEEPTTL', $this->options)) {
            unset($this->options[$index]);
        }
        $this->options['EX'] = $ttl;
        return $this;
    }
    public function setExpirationTimestamp(string $ttl): SetOptions
    {
        if (isset($this->options['EX'])) {
            unset($this->options['EX']);
        }
        if (isset($this->options['PX'])) {
            unset($this->options['PX']);
        }
        if (isset($this->options['PXAT'])) {
            unset($this->options['PXAT']);
        }
        if ($index = array_search('KEEPTTL', $this->options)) {
            unset($this->options[$index]);
        }
        $this->options['EXAT'] = $ttl;
        return $this;
    }
    public function setExpirationInMilliseconds(int $ttl): SetOptions
    {
        if (isset($this->options['EX'])) {
            unset($this->options['EX']);
        }
        if (isset($this->options['EXAT'])) {
            unset($this->options['EXAT']);
        }
        if (isset($this->options['PXAT'])) {
            unset($this->options['PXAT']);
        }
        if ($index = array_search('KEEPTTL', $this->options)) {
            unset($this->options[$index]);
        }
        $this->options['PX'] = $ttl;
        return $this;
    }
    public function setExpirationTimestampInMilliseconds(string $ttl): SetOptions
    {
        if (isset($this->options['EX'])) {
            unset($this->options['EX']);
        }
        if (isset($this->options['PX'])) {
            unset($this->options['PX']);
        }
        if (isset($this->options['EXAT'])) {
            unset($this->options['EXAT']);
        }
        if ($index = array_search('KEEPTTL', $this->options)) {
            unset($this->options[$index]);
        }
        $this->options['PXAT'] = $ttl;
        return $this;
    }
    public function setExpirationSameAsOld(): SetOptions
    {
        if (isset($this->options['EX'])) {
            unset($this->options['EX']);
        }
        if (isset($this->options['PX'])) {
            unset($this->options['PX']);
        }
        if (isset($this->options['EXAT'])) {
            unset($this->options['EXAT']);
        }
        if (isset($this->options['PXAT'])) {
            unset($this->options['PXAT']);
        }
        $this->options[] = 'KEEPTTL';
        return $this;
    }
    public function returnOldValue(): SetOptions
    {
        $this->options[] = 'GET';
        return $this;
    }

    public function setIfNew(): SetOptions
    {
        if ($index = array_search('XX', $this->options)) {
            unset($this->options[$index]);
        }
        $this->options[] = 'NX';
        $this->expireOptions[] = 'NX';
        return $this;
    }

    public function setIfExist(): SetOptions
    {
        if ($index = array_search('NX', $this->options)) {
            unset($this->options[$index]);
        }
        $this->options[] = 'XX';
        $this->expireOptions[] = 'XX';
        return $this;
    }
}

<?php

namespace DD\MicroserviceCore\Classes;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Closure;

class CachingOptions
{
    protected array $setOptions = [];
    protected array $expireOptions = [];

    protected function getOptions($optionVariable): array
    {
        $optArray = [];
        foreach ($this->$optionVariable as $index => $value) {
            if (gettype($index) !== 'integer') {
                $optArray[] = $index;
            }
            $optArray[] = $value;
        }
        return $optArray;
    }

    protected function getSetOptions(): array
    {
        return $this->getOptions('setOptions');
    }

    protected function getExpireOptions(): array
    {
        return $this->getOptions('expireOptions');
    }
    public function setExpiration(int $ttl): void
    {
        if (isset($this->setOptions['PX'])) {
            unset($this->setOptions['PX']);
        }
        if (isset($this->setOptions['EXAT'])) {
            unset($this->setOptions['EXAT']);
        }
        if (isset($this->setOptions['PXAT'])) {
            unset($this->setOptions['PXAT']);
        }
        if ($index = array_search('KEEPTTL', $this->setOptions)) {
            unset($this->setOptions[$index]);
        }
        $this->setOptions['EX'] = $ttl;
    }
    public function setExpirationTimestamp(string $ttl): void
    {
        if (isset($this->setOptions['EX'])) {
            unset($this->setOptions['EX']);
        }
        if (isset($this->setOptions['PX'])) {
            unset($this->setOptions['PX']);
        }
        if (isset($this->setOptions['PXAT'])) {
            unset($this->setOptions['PXAT']);
        }
        if ($index = array_search('KEEPTTL', $this->setOptions)) {
            unset($this->setOptions[$index]);
        }
        $this->setOptions['EXAT'] = $ttl;
    }
    public function setExpirationInMilliseconds(int $ttl): void
    {
        if (isset($this->setOptions['EX'])) {
            unset($this->setOptions['EX']);
        }
        if (isset($this->setOptions['EXAT'])) {
            unset($this->setOptions['EXAT']);
        }
        if (isset($this->setOptions['PXAT'])) {
            unset($this->setOptions['PXAT']);
        }
        if ($index = array_search('KEEPTTL', $this->setOptions)) {
            unset($this->setOptions[$index]);
        }
        $this->setOptions['PX'] = $ttl;
    }
    public function setExpirationTimestampInMilliseconds(string $ttl): void
    {
        if (isset($this->setOptions['EX'])) {
            unset($this->setOptions['EX']);
        }
        if (isset($this->setOptions['PX'])) {
            unset($this->setOptions['PX']);
        }
        if (isset($this->setOptions['EXAT'])) {
            unset($this->setOptions['EXAT']);
        }
        if ($index = array_search('KEEPTTL', $this->setOptions)) {
            unset($this->setOptions[$index]);
        }
        $this->setOptions['PXAT'] = $ttl;
    }
    public function setExpirationSameAsOld(): void
    {
        if (isset($this->setOptions['EX'])) {
            unset($this->setOptions['EX']);
        }
        if (isset($this->setOptions['PX'])) {
            unset($this->setOptions['PX']);
        }
        if (isset($this->setOptions['EXAT'])) {
            unset($this->setOptions['EXAT']);
        }
        if (isset($this->setOptions['PXAT'])) {
            unset($this->setOptions['PXAT']);
        }
        $this->setOptions[] = 'KEEPTTL';
    }
    public function returnOldValue(): void
    {
        $this->setOptions[] = 'GET';
    }

    public function setIfNew(): void
    {
        if ($index = array_search('XX', $this->setOptions)) {
            unset($this->setOptions[$index]);
        }
        if ($index = array_search('XX', $this->expireOptions)) {
            unset($this->expireOptions[$index]);
        }
        if ($index = array_search('GT', $this->expireOptions)) {
            unset($this->expireOptions[$index]);
        }
        if ($index = array_search('LT', $this->expireOptions)) {
            unset($this->expireOptions[$index]);
        }
        $this->setOptions[] = 'NX';
        $this->expireOptions[] = 'NX';
    }

    public function setIfExist(): void
    {
        if ($index = array_search('NX', $this->setOptions)) {
            unset($this->setOptions[$index]);
        }
        if ($index = array_search('NX', $this->expireOptions)) {
            unset($this->expireOptions[$index]);
        }
        if ($index = array_search('GT', $this->expireOptions)) {
            unset($this->expireOptions[$index]);
        }
        if ($index = array_search('LT', $this->expireOptions)) {
            unset($this->expireOptions[$index]);
        }
        $this->setOptions[] = 'XX';
        $this->expireOptions[] = 'XX';
    }

    public function setIfGreaterThanCurrent(): void
    {
        if ($index = array_search('NX', $this->expireOptions)) {
            unset($this->expireOptions[$index]);
        }
        if ($index = array_search('XX', $this->expireOptions)) {
            unset($this->expireOptions[$index]);
        }
        if ($index = array_search('LT', $this->expireOptions)) {
            unset($this->expireOptions[$index]);
        }
        $this->expireOptions[] = 'GT';
    }

    public function setIfLessThanCurrent(): void
    {
        if ($index = array_search('NX', $this->expireOptions)) {
            unset($this->expireOptions[$index]);
        }
        if ($index = array_search('XX', $this->expireOptions)) {
            unset($this->expireOptions[$index]);
        }
        if ($index = array_search('GT', $this->expireOptions)) {
            unset($this->expireOptions[$index]);
        }
        $this->expireOptions[] = 'LT';
    }
}

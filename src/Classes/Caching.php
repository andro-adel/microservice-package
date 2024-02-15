<?php

namespace DD\MicroserviceCore\Classes;

use Illuminate\Support\Facades\Redis;
use Illuminate\Redis\Connections\Connection;
use Closure;

class Caching
{
    private Connection $redis;

    public function __construct(string|null $connectionName = null)
    {
        $this->changeConnection($connectionName);
    }

    public function changeConnection(string|null $connectionName): void
    {
        $this->redis = Redis::connection($connectionName);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param SetOptions|null $opt
     * @return mixed
     */
    public function set(string $key, mixed $value, SetOptions|null $opt = null): mixed
    {
        return Redis::set($key, serialize($value),
            ...($opt ? $opt->getOptions() : $opt->setExpiration(60 * 60)->getOptions()));
    }

    /**
     * @param array $values
     * @return bool
     */
    public function setMany(array $values): bool
    {
        $data = [];
        foreach ($values as $key => $value) {
            $data[] = $key;
            $data[] = serialize($value);
        }
        return $this->redis->mset(...$data)->getPayload();
    }

    /**
     * @param string|array $key
     * @return mixed
     */
    public function get(string|array $key): mixed
    {
        if (is_array($key)) {
            $data = [];
            foreach ($this->redis->mget($key) as $value) {
                $data[] = $value ? unserialize($value) : null;
            }
            return $data;
        } else {
            $data = $this->redis->get($key);
            return $data ? unserialize($data) : null;
        }
    }

    /**
     * @param string $key
     * @param string $newKey
     * @return bool
     */
    public function rename(string $key, string $newKey): bool
    {
        return $this->redis->rename($key, $newKey)->getPayload();
    }

    /**
     * @param string $key
     * @return string
     */
    public function getType(string $key): string
    {
        return $this->redis->type($key)->getPayload();
    }

    public function transaction(Closure $callback): string
    {
        return $this->redis->transaction($callback);
    }

    /**
     * @param string|array $key
     * @return int
     */
    public function delete(string|array $key): int
    {
        return $this->redis->del(...(is_array($key) ? $key : [$key]));
    }

    /**
     * @param string|array $key
     * @return int
     */
    public function isKeyExist(string|array $key): int
    {
        return $this->redis->exists(...(is_array($key) ? $key : [$key]));
    }

    /**
     * @param string $key
     * @param int $ttl
     * @param ExpireOptions|null $opt
     * @return bool
     */
    public function setExpire(string $key, int $ttl, ExpireOptions|null $opt = null): bool
    {
        return $this->redis->expire($key, $ttl, ...($opt ? $opt->getOptions() : []));
    }

    /**
     * @param string $pattern
     * @return array
     */
    public function keySearching(string $pattern): array
    {
        return $this->redis->keys($pattern);
    }

    /**
     * @return array
     */
    public function getAllKeys(): array
    {
        return self::keySearching('*');
    }

    /**
     * @param string $command
     * @param array $values
     * @return array
     */
    public function command(string $command, array $values): mixed
    {
        return $this->redis->command($command, $values);
    }
}

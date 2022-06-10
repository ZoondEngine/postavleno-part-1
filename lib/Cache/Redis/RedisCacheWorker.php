<?php

require_once dirname(__DIR__)."/Contracts/ICacheWorker.php";
require_once 'RedisConnector.php';
require_once 'RedisIOController.php';

class RedisCacheWorker implements ICacheWorker {

    /**
     * @var RedisIOController|null
     */
    private ?RedisIOController $controller;

    /**
     * @param string $host
     * @param int $port
     * @return bool
     * @throws NotInitializedRedisConnectionException
     * @throws RedisConnectorNotConnectedException
     * @throws RedisException
     */
    public function connect(string $host, int $port): bool {
        $this->controller = new RedisIOController();
        $this->controller->setConnection(new RedisConnector($host, $port));

        return $this->ready();
    }

    /**
     * @return bool
     */
    public function ready(): bool {
        return $this->controller?->ready() ?? false;
    }

    /**
     * @param string $key
     * @return bool
     * @throws NotInitializedRedisConnectionException
     */
    public function has(string $key): bool {
        return $this->controller?->has($key) ?? false;
    }

    /**
     * @param string $key
     * @return mixed
     * @throws NotInitializedRedisConnectionException
     */
    public function get(string $key): mixed {
        return $this->controller?->take($key) ?? null;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param float $ttl
     * @param bool $update
     * @return mixed
     * @throws NotInitializedRedisConnectionException
     */
    public function push(string $key, mixed $value, float $ttl = 3600, bool $update = true): mixed {
        return $this->controller?->push($key, $value, $ttl, $update);
    }

    /**
     * @param string $key
     * @return mixed
     * @throws NotInitializedRedisConnectionException
     */
    public function del(string $key): mixed {
        return $this->controller?->del($key);
    }

    /**
     * @return array
     * @throws NotInitializedRedisConnectionException
     */
    public function all(): array {
        return $this->controller?->items();
    }
}
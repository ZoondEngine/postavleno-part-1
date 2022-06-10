<?php

require_once 'IRedisConnector.php';

/**
 * Interface IRedisIOController
 */
interface IRedisIOController {

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function take(string $key, mixed $default = null): mixed;

    /**
     * @param string $key
     * @param mixed $value
     * @param float $ttl
     * @param bool $update
     * @return mixed
     */
    public function push(string $key, mixed $value, float $ttl = 3600, bool $update = true): mixed;

    /**
     * @param string $key
     * @return mixed
     */
    public function del(string $key): mixed;

    /**
     * @return array
     */
    public function items(): array;

    /**
     * @return bool
     */
    public function ready(): bool;

    /**
     * @param IRedisConnector $connector
     * @return bool
     */
    public function setConnection(IRedisConnector $connector): bool;
}
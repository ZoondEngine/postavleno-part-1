<?php

interface ICacheWorker {
    /**
     * @param string $host
     * @param int $port
     * @return bool
     */
    public function connect(string $host, int $port): bool;

    /**
     * @return bool
     */
    public function ready(): bool;

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed;

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
    public function all(): array;
}
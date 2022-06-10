<?php

/**
 * Interface IRedisConnector
 */
interface IRedisConnector {
    /**
     * @return bool
     */
    public function connect(): bool;

    /**
     * @return bool
     */
    public function disconnect(): bool;

    /**
     * @return bool
     */
    public function connected(): bool;

    /**
     * @return string
     */
    public function host(): string;

    /**
     * @return int
     */
    public function port(): int;

    /**
     * @return Redis
     * @throws NotInitializedRedisConnectionException
     */
    function native(): Redis;
}
<?php

require_once 'Contracts\IRedisIOController.php';
require_once 'Exceptions\RedisConnectorNotConnectedException.php';

class RedisIOController implements IRedisIOController {
    /**
     * @var IRedisConnector|null
     */
    private ?IRedisConnector $connector;

    /**
     * @param string $key
     * @return bool
     * @throws NotInitializedRedisConnectionException
     */
    public function has(string $key): bool {
        return $this->ready() && $this->handle()->get($key);
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     * @throws NotInitializedRedisConnectionException
     */
    public function take(string $key, mixed $default = null): mixed {
        return $this->has($key)
            ? $this->handle()->get($key)
            : $default;
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
        if($this->has($key)) {
            if(!$update) {
                return $this->take($key);
            }
        }

        $handle = $this->handle();

        if($this->has($key)) {
            $handle->del($key);
        }

        $handle->set($key, $value);
        $handle->expire($key, $ttl);

        return $this->take($key);
    }

    /**
     * @return array
     * @throws NotInitializedRedisConnectionException
     */
    public function items(): array {
        if($this->ready()) {
            $handle = $this->handle();

            if($handle) {
                $keys = $handle->keys('*');
                $map = [];

                foreach ($keys as $key) {
                    $map[$key] = $handle->get($key);
                }

                return $map;
            }
        }

        return [];
    }

    /**
     * @return bool
     */
    public function ready(): bool {
        return $this->connector?->connected() ?? false;
    }

    /**
     * @return Redis|null
     * @throws NotInitializedRedisConnectionException
     */
    protected function handle(): ?Redis {
        return $this->connector?->native() ?? null;
    }

    /**
     * @param IRedisConnector $connector
     * @return bool
     * @throws NotInitializedRedisConnectionException
     * @throws RedisConnectorNotConnectedException
     * @throws RedisException
     */
    public function setConnection(IRedisConnector $connector): bool {
        if(!$connector->connected()) {
            if(!$connector->connect()) {
                throw new RedisConnectorNotConnectedException(
                    "Couldn't not connect with error {$connector->native()->getLastError()}"
                );
            }
        }

        $this->connector = $connector;
        return $this->connector->native()->ping();
    }

    /**
     * @param string $key
     * @return mixed
     * @throws NotInitializedRedisConnectionException
     */
    public function del(string $key): mixed {
        if($this->has($key)) {
            $item = $this->take($key);
            $this->handle()->del($key);

            return $item;
        }

        return null;
    }
}
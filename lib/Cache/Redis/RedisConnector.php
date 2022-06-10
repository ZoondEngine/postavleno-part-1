<?php

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

require_once 'Contracts\IRedisConnector.php';
require_once 'Exceptions\NotInitializedRedisConnectionException.php';

/**
 * Class RedisConnector
 */
class RedisConnector implements IRedisConnector {

    /**
     * @var Redis|null
     */
    private ?Redis $connection;

    /**
     * @var string|null
     */
    private ?string $host;

    /**
     * @var int|null
     */
    private ?int $port;

    /**
     * RedisConnector constructor.
     * @param string $host
     * @param int $port
     */
    public function __construct(string $host = '127.0.0.1', int $port = 6789) {
        $this->connection = new Redis();
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * @return bool
     */
    public function connect(): bool {
        return $this->connection->connect($this->host, $this->port);
    }

    /**
     * @return bool
     */
    public function disconnect(): bool {
        if($this->connected()) {
            $operationResult = $this->connection->close();
            $this->reset();

            return $operationResult;
        }

        if($this->parametrized()) {
            $this->reset();
        }

        return true;
    }

    /**
     *
     */
    protected function reset(): void {
        $this->host = null;
        $this->port = null;
        $this->connection = null;
    }

    /**
     * @return bool
     */
    public function connected(): bool {
        return $this->connection?->isConnected() ?? false;
    }

    /**
     * @return bool
     */
    protected function parametrized(): bool {
        return $this->host !== null
            && $this->port !== null
            && $this->connection !== null;
    }

    /**
     * @return array
     */
    #[ArrayShape(['host' => "null|string", 'port' => "int|null"])]
    protected function repository(): array {
        return [
            'host' => $this->host ?? '',
            'port' => $this->port ?? 0,
        ];
    }

    /**
     * @return string
     */
    #[Pure]
    public function host(): string {
        return $this->repository()['host'];
    }

    /**
     * @return int
     */
    #[Pure]
    public function port(): int {
        return $this->repository()['port'];
    }

    /**
     * @return Redis
     * @throws NotInitializedRedisConnectionException
     */
    public function native(): Redis {
        if(!$this->connection) {
            throw new NotInitializedRedisConnectionException('Connection not was initialized before access');
        }

        return $this->connection;
    }
}
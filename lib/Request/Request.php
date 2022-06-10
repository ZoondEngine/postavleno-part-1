<?php

/**
 * Class Request
 */
class Request {

    /**
     * @var string|mixed
     */
    private string $method;

    /**
     * @var string|mixed
     */
    private string $uri;

    /**
     * @var array
     */
    private array $query;

    /**
     * @var string
     */
    private string $queryString;

    /**
     * Request constructor.
     */
    public function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'];

        $this->explodeQuery();
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed {
        if(array_key_exists($name, $this->query)) {
            return $this->query[$name];
        }

        return null;
    }

    /**
     * @return string
     */
    public function getMethod(): string {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUri(): string {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getUriWithQueryString(): string {
        return "$this->uri?$this->queryString";
    }

    /**
     * @return string
     */
    public function getQueryString(): string {
        return $this->queryString;
    }

    /**
     * @return array
     */
    public function getQuery(): array {
        return $this->query;
    }

    /**
     *
     */
    private function explodeQuery(): void {
        $arr = explode("?", $_SERVER['REQUEST_URI']);

        $this->uri = $arr[0];

        if(count($arr) < 2) {
            // Skip it if not
            return;
        }

        $temporary = $arr[1];

        // ?key=value&ttl=3600
        foreach(explode('&', $temporary) as $keyValuePair) {
            $keyValue = explode('=', $keyValuePair);

            //key=value
            if(count($keyValue) === 2) {
                //               key            value
                $this->query[$keyValue[0]] = $keyValue[1];
            }
        }

        // backup query string
        $this->queryString = $temporary;
    }
}
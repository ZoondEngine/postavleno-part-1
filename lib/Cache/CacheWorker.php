<?php

require_once 'Contracts\ICacheWorker.php';
require_once 'Exceptions\InvalidWorkerUsedException.php';

class CacheWorker {
    /**
     * @var ICacheWorker|null
     */
    private static ?ICacheWorker $worker;

    /**
     * @param string $cacheWorker
     * @return ICacheWorker
     * @throws InvalidWorkerUsedException
     */
    public static function use(string $cacheWorker): ICacheWorker {
        $instance = new $cacheWorker;

        if(!($instance instanceof ICacheWorker)) {
            throw new InvalidWorkerUsedException(
                "Worker {$cacheWorker} was invalid because not implemented ICacheWorker contract"
            );
        }

        self::$worker = $instance;
        self::$worker->connect('127.0.0.1', 6379);
        return self::useSaved();
    }

    /**
     * @return ICacheWorker
     */
    public static function useSaved(): ICacheWorker {
        return self::$worker;
    }
}
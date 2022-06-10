<?php

require_once 'lib\Cache\CacheWorker.php';
require_once 'lib\Cache\Redis\RedisCacheWorker.php';

/**
 * @param array $args
 * @throws Exception
 */
function execute(array $args) {
    if(count($args) < 4) {
        die('Invalid arguments count');
    }

    if(!extension_loaded('redis')) {
        die('Redis extensions not loaded');
    }

    $provider = $args[1];
    $command = $args[2];
    $worker = get_worker($provider);

    if($command === 'add') {
        if(count($args) < 4) {
            print 'Error, add command could not container less then 5 elements';
        }
        else {
            $key = $args[3];
            $value = $args[4];

            $worker->push($key, $value);

            print 'Item was added. Full items list: ';
        }
    }

    if($command === 'delete') {
        $key = $args[3];

        $worker->del($key);

        print 'Item was deleted. Full items list:';
    }

    foreach ($worker->all() as $key => $value) {
        print "\r\n" . 'Key: ' . $key . ' / Value: ' . $value . "\r\n";
    }
}

/**
 * @param string $provider
 * @return ICacheWorker
 * @throws Exception
 */
function get_worker(string $provider): ICacheWorker {
    $func = strtolower($provider) . '_worker';
    if(function_exists($func)) {
        return $func();
    }

    throw new Exception(
        'Worker not found for received provider'
    );
}

/**
 * @return ICacheWorker
 * @throws InvalidWorkerUsedException
 */
function redis_worker(): ICacheWorker {
    return CacheWorker::use(RedisCacheWorker::class);
}

/**
 * @return ICacheWorker
 * @throws Exception
 */
function memcached_worker(): ICacheWorker {
    throw new Exception(
        'Memcached implementation test, but from case not need to implement it only for possibility reasons'
    );
}

try {
    execute($argv);
} catch (Exception $e) {
    print 'execution error: ' . $e->getMessage();
}
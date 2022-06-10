<?php

require_once dirname(__DIR__)."/lib/Request/Request.php";
require_once dirname(__DIR__)."/lib/Response/JsonResponse.php";
require_once dirname(__DIR__)."/lib/Cache/Redis/RedisCacheWorker.php";
require_once dirname(__DIR__)."/lib/Cache/CacheWorker.php";

/**
 * @param Request $request
 * @return string
 * @throws InvalidWorkerUsedException
 */
function delete(Request $request): string {
    $redis = cache();

    if($redis->has($request->key)) {
        $redis->del($request->key);
        return (new JsonResponse(message: 'Element was deleted!'));
    }

    return (new JsonResponse(code: 404, message: 'Nothing to delete'));
}

/**
 * @param Request $request
 * @return string
 * @throws InvalidWorkerUsedException
 */
function get(Request $request): string {
    return (new JsonResponse(body: cache()->all()));
}

/**
 * @return ICacheWorker
 * @throws InvalidWorkerUsedException
 */
function cache(): ICacheWorker {
    return CacheWorker::use(RedisCacheWorker::class);
}

$request = new Request();

if(function_exists($request->getMethod())) {
    echo $request->getMethod()($request);
}
else {
    echo (new JsonResponse(
        code: 500,
        message: 'Invalid request method for using redis api'
    ));
}
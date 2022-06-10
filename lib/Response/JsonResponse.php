<?php

/**
 * Class JsonResponse
 */
class JsonResponse {

    /**
     * @var int
     */
    private int $code;

    /**
     * @var string
     */
    private string $message;

    /**
     * @var array
     */
    private array $body;

    /**
     * JsonResponse constructor.
     * @param int $code
     * @param string $message
     * @param array $body
     */
    public function __construct(int $code = 200, string $message = '', array $body = []) {
        $this->code = $code;
        $this->message = $message;
        $this->body = $body;
    }

    /**
     * @return int
     */
    public function getCode(): int {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getMessage(): string {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getBody(): array {
        return $this->body;
    }

    /**
     * @return string
     */
    public function __toString(): string {
        return json_encode([
            'code' => $this->code,
            'message' => $this->message,
            'body' => $this->body
        ]);
    }

    /**
     * @param string $json
     * @return JsonResponse|null
     */
    public static function fromJson(string $json): ?JsonResponse {
        $obj = json_decode($json);

        if($obj) {
            return (new JsonResponse(
                $obj->code,
                $obj->message,
                $obj->body
            ));
        }

        return null;
    }
}
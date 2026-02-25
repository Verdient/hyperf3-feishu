<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\FeiShu;

use Verdient\Http\Result as HttpResult;
use Verdient\HttpAPI\AbstractResult;

/**
 * 结果
 *
 * @author Verdient。
 */
class Result extends AbstractResult
{
    /**
     * @inheritdoc
     * @author Verdient。
     */
    protected function resolve(HttpResult $result): void
    {
        $statusCode = $result->getStatusCode();

        $body = $result->getBodies();

        if (
            $statusCode >= 200
            && $statusCode < 300
            && isset($body['code'])
            && $body['code'] === 0
        ) {
            $this->isOK = true;

            $this->data = $body;
        } else {
            $this->isOK = false;

            $this->errorCode = $body['code'] ?? $statusCode;

            $this->errorMessage = $body['msg'] ?? $result->getResponse()->getRawContent();
        }
    }
}

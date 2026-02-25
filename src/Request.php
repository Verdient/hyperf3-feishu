<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\FeiShu;

use Verdient\Http\Transport\CUrlTransport;
use Verdient\HttpAPI\AbstractRequest;
use Verdient\HttpAPI\ResultInterface;

/**
 * 请求
 *
 * @author Verdient。
 */
class Request extends AbstractRequest
{
    /**
     * @inheritdoc
     * @author Verdient。
     */
    public function send(): ResultInterface
    {
        $this->request->setTransport(new CUrlTransport);
        return new Result($this->request->send());
    }
}

<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\FeiShu;

/**
 * 凭据
 *
 * @author Verdient。
 */
readonly class Credential
{
    /**
     * @param string $accessToken 授权秘钥
     * @param string $expiredAt 过期时间
     * @author Verdient。
     */
    public function __construct(
        public string $accessToken,
        public int $expiredAt,
    ) {}
}

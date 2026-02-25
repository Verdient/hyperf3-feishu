<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\FeiShu;

use RuntimeException;
use Verdient\HttpAPI\AbstractClient;
use Verdient\HttpAPI\RequestInterface;

/**
 * 飞书
 *
 * @extends AbstractClient<Configure>
 * @author Verdient。
 */
class FeiShu extends AbstractClient
{
    /**
     * 是否携带授权秘钥
     *
     * @author Verdient。
     */
    protected bool $withAccessToken = false;

    /**
     * 携带访问秘钥
     *
     * @param bool $value 是否携带授权秘钥
     *
     * @author Verdient。
     */
    public function withAccessToken(bool $value = true): static
    {
        $this->withAccessToken = $value;

        return $this;
    }

    /**
     * 获取凭据
     *
     * @author Verdient。
     */
    public function getCredential(): Credential
    {
        if ($credential = $this->getCredentialFromCache()) {
            return $credential;
        }

        $credential = $this->requestNewCredential();

        $this->cacheCredential($credential);

        return $credential;
    }

    /**
     * 请求新的凭据
     *
     * @author Verdient。
     */
    protected function requestNewCredential(): Credential
    {
        $request = parent::request('open-apis/auth/v3/tenant_access_token/internal');

        $request->setMethod('POST');

        $request->setBodies([
            'app_id' => $this->configure->getAppId(),
            'app_secret' => $this->configure->getAppSecret()
        ]);

        $result = $request->send();

        if (!$result->getIsOK()) {
            throw new RuntimeException($result->getErrorMessage());
        }

        $data = $result->getData();

        return new Credential(
            accessToken: $data['tenant_access_token'],
            expiredAt: time() + $data['expire'] - 30
        );
    }

    /**
     * 缓存路径
     *
     * @author Verdient。
     */
    protected function credentialCachePath(): string
    {
        return $this->configure->getCachePath() . DIRECTORY_SEPARATOR . md5(serialize($this->configure));
    }

    /**
     * 从缓存中获取凭据
     *
     * @author Verdient。
     */
    protected function getCredentialFromCache(): ?Credential
    {
        $cachePath = $this->credentialCachePath();

        if (!file_exists($cachePath)) {
            return null;
        }

        if (!is_readable($cachePath)) {
            return null;
        }

        $credential = unserialize(file_get_contents($cachePath));

        if (!($credential instanceof Credential)) {
            return null;
        }

        if ($credential->expiredAt <= time()) {
            return null;
        }

        return $credential;
    }

    /**
     * 缓存凭据
     *
     * @author Verdient。
     */
    protected function cacheCredential(Credential $credential): bool
    {
        $cachePath = $this->credentialCachePath();

        $dir = dirname($cachePath);

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        return file_put_contents($cachePath, serialize($credential)) !== false;
    }

    /**
     * @inheritdoc
     * @author Verdient。
     */
    public function request(?string $path = null): RequestInterface
    {
        $request = parent::request($path);

        if ($this->withAccessToken) {
            $credential = $this->getCredential();

            $request->setHeaders([
                'Authorization' => 'Bearer ' . $credential->accessToken
            ]);
        }

        return $request;
    }
}

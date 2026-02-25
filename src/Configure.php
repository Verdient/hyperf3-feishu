<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\FeiShu;

use Hyperf\Stringable\Str;
use Override;
use Verdient\HttpAPI\Configure as HttpAPIConfigure;

use function Hyperf\Config\config;

/**
 * @author Verdient。
 */
class Configure extends HttpAPIConfigure
{
    /**
     * @author Verdient。
     */
    protected string $host = 'open.feishu.cn';

    /**
     * 应用编号
     *
     * @author Verdient。
     */
    protected string $appId;

    /**
     * 应用秘钥
     *
     * @author Verdient。
     */
    protected string $appSecret;

    /**
     * 缓存路径
     *
     * @author Verdient。
     */
    protected string $cachePath;

    /**
     * @author Verdient。
     */
    #[Override]
    protected function configure(): array
    {
        $result = [];

        foreach (config('feishu', []) as $name => $value) {
            $result[Str::camel($name)] = $value;
        }

        return $result;
    }

    /**
     * 获取应用编号
     *
     * @author Verdient。
     */
    public function getAppId(): string
    {
        return $this->appId;
    }

    /**
     * 获取应用秘钥
     *
     * @author Verdient。
     */
    public function getAppSecret(): string
    {
        return $this->appSecret;
    }

    /**
     * 获取缓存路径
     *
     * @author Verdient。
     */
    public function getCachePath(): string
    {
        return $this->cachePath;
    }
}

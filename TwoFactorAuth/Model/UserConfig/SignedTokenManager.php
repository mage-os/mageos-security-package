<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\TwoFactorAuth\Model\UserConfig;

use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Encryption\Helper\Security;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\TwoFactorAuth\Api\UserConfigTokenManagerInterface;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\ObjectManager;

/**
 * @inheritDoc
 */
class SignedTokenManager implements UserConfigTokenManagerInterface
{
    /**
     * @var string
     */
    public const CACHE_ID = 'tfa_token';

    /**
     * @var EncryptorInterface
     */
    private $encryptor;

    /**
     * @var Json
     */
    private $json;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @param EncryptorInterface $encryptor
     * @param Json $json
     * @param DateTime $dateTime
     * @param CacheInterface|null $cache
     */
    public function __construct(
        EncryptorInterface $encryptor,
        Json $json,
        DateTime $dateTime,
        CacheInterface $cache = null
    ) {
        $this->encryptor = $encryptor;
        $this->json = $json;
        $this->dateTime = $dateTime;
        $this->cache = $cache ?? ObjectManager::getInstance()->get(CacheInterface::class);
    }

    /**
     * @inheritDoc
     */
    public function issueFor(int $userId): string
    {
        $data = ['user_id' => $userId, 'tfa_configuration' => true, 'iss' => $this->dateTime->timestamp()];
        $encodedData = $this->json->serialize($data);
        $signature = base64_encode($this->encryptor->hash($encodedData));
        $this->cache->save(base64_encode($encodedData .'.' .$signature), self::CACHE_ID . $userId);
        return base64_encode($encodedData .'.' .$signature);
    }

    /**
     * @inheritDoc
     */
    public function isValidFor(int $userId, string $token): bool
    {
        $isValid = false;
        try {
            // phpcs:ignore Magento2.Functions.DiscouragedFunction
            [$encodedData, $signatureProvided] = explode('.', base64_decode($token));
            $data = $this->json->unserialize($encodedData);
            if (array_key_exists('user_id', $data)
                && array_key_exists('tfa_configuration', $data)
                && array_key_exists('iss', $data)
                && $data['user_id'] === $userId
                && $data['tfa_configuration']
                && ($this->dateTime->timestamp() - (int)$data['iss']) < 3600
                && Security::compareStrings(base64_encode($this->encryptor->hash($encodedData)), $signatureProvided)
            ) {
                $isValid = true;
            }
        } catch (\Throwable $exception) {
            $isValid = false;
        }

        return $isValid;
    }
}

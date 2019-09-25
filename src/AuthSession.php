<?php
/**
 * Copyright (c) 2019 - present
 * Laravel Auth Log - CurrentAuthSession.phpion.php
 * author: Roberto Belotti - roby.belotti@gmail.com
 * web : robertobelotti.com, github.com/biscolab
 * Initial version created on: 16/9/2019
 * MIT license: https://github.com/biscolab/laravel-authlog/blob/master/LICENSE
 */

namespace Biscolab\LaravelAuthLog;

use Biscolab\LaravelAuthLog\Models\Session;
use Biscolab\LaravelAuthLog\Models\SessionInterface;
use Illuminate\Support\Facades\Redis;

/**
 * Class CurrentAuthSession
 * @package Biscolab\LaravelAuthLog
 */
class AuthSession
{

    /**
     * @var null|string
     */
    protected $session_id = '';

    /**
     * CurrentAuthSession constructor.
     *
     * @param null|string $session_id
     */
    public function __construct(?string $session_id = null)
    {

        $this->session_id = $session_id ?? getCurrentSessionId();;
    }

    /**
     * @return string
     */
    public function getSessionData(): string
    {

        $data = '';

        switch (config('session.driver', '')) {
            case 'redis':
                $data = $this->getRedisContent();
                break;
            case 'file':
                $data = $this->getSessionFileContent();
                break;
            case 'database':
                $data = $this->getDataFromDbSession();
        }

        return $data ?? '';
    }

    /**
     * @return bool
     */
    public function kill(): bool
    {

        $response = false;

        if (!config('authlog.safe_mode') && getCurrentSessionId() === $this->session_id) {
            return $response;
        }

        switch (config('session.driver', '')) {
            case 'redis':
                $response = $this->deleteSessionFromRedis();
                break;
            case 'file':
                $response = $this->deleteSessionFile();
                break;
            case 'database':
                $response = $this->deleteSessionFromDb();
        }

        return $response;
    }

    /**
     * @return \Illuminate\Redis\Connections\Connection
     */
    protected function getRedisConnection()
    {

        return Redis::connection(config('session.connection'));
    }

    /**
     * @return string
     */
    protected function getCacheKey(): string
    {

        return config('cache.prefix') . ':' . $this->session_id;
    }

    /**
     * @return string
     */
    protected function getFilePath(): string
    {

        return storage_path('framework/sessions/' . $this->session_id);
    }

    /**
     * @return string
     */
    protected function getFilePathIfExists(): string
    {

        $file_path = $this->getFilePath();

        return (file_exists($file_path)) ? $file_path : '';
    }

    /**
     * @return SessionInterface|null
     */
    protected function getDbSession(): ?SessionInterface
    {

        $session_model_class = config('authlog.session_model');

        return $session_model_class::find($this->session_id);
    }

    /**
     * @return bool
     */
    protected function deleteSessionFromDb(): bool
    {

        /** @var Session $session */
        $session = $this->getDbSession();
        if ($session) {
            return $session->delete();
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    protected function deleteSessionFile(): bool
    {

        $file_path = $this->getFilePathIfExists();

        if (!$file_path) {
            return false;
        }

        return unlink($file_path);
    }

    /**
     * @return string
     */
    protected function getSessionFileContent(): string
    {

        $file_path = $this->getFilePathIfExists();

        if (!$file_path) {
            return '';
        }

        return file_get_contents($file_path);
    }

    /**
     * @return bool
     */
    protected function deleteSessionFromRedis(): bool
    {

        return $this->getRedisConnection()->del([$this->getCacheKey()]);
    }

    /**
     * @return string
     */
    protected function getRedisContent(): string
    {

        return $this->getRedisConnection()->get($this->getCacheKey()) ?? '';
    }

    /**
     * @return string
     */
    protected function getDataFromDbSession(): string
    {

        $session = $this->getDbSession();

        return (null === $session) ? '' : $session->decoded_payload;
    }
}
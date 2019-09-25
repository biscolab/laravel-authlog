<?php
/**
 * Copyright (c) 2019 - present
 * Laravel Auth Log - AuthLog.php
 * author: Roberto Belotti - roby.belotti@gmail.com
 * web : robertobelotti.com, github.com/biscolab
 * Initial version created on: 13/9/2019
 * MIT license: https://github.com/biscolab/laravel-authlog/blob/master/LICENSE
 */

namespace Biscolab\LaravelAuthLog\Models;

use Biscolab\LaravelAuthLog\AuthSession;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;

/**
 * Class UserLog
 * @property string                  session_id
 * @property User                    user
 * @property string                  user_name
 * @property bool                    user_logout
 * @property int                     user_id
 * @property mixed                   created_at
 * @property string                  user_agent
 * @property string                  browser
 * @property string                  browser_name
 * @property string                  session_data
 * @property int                     id
 * @property AuthLogInterface        logout
 * @property AuthLogInterface        forced_logout
 * @property bool                    is_still_logged_in
 * @property AuthSession             auth_session
 * @property \Carbon\CarbonInterface logged_out_at
 * @package App
 */
class AuthLog extends Model implements AuthLogInterface
{

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var AuthSession|null
     */
    protected $auth_session_instance = null;

    /**
     * AuthLog constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {

        parent::__construct($attributes);

        $this->setTable(config('authlog.table_name'));
    }

    /**
     * @return Collection
     */
    public static function getNotManuallyLoggedOut(): Collection
    {

        return self::whereNull('logged_out_at')->orderBy('id', 'desc')->get();
    }

    /**
     * @return Collection
     */
    public static function getStillLoggedIn()
    {

        return self::getNotManuallyLoggedOut()->filter(function ($item) {

            return !empty($item->session_data);
        });
    }

    /**
     * @return AuthSession|null
     */
    public function getAuthSessionAttribute(): ?AuthSession
    {

        if ($this->session_id && null === $this->auth_session_instance) {
            $this->auth_session_instance = new AuthSession($this->session_id);
        }

        return $this->auth_session_instance;
    }

    /**
     * user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {

        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * user_name
     *
     * @return string
     */
    public function getUserNameAttribute(): string
    {

        return ($this->user) ? $this->user->name : '';
    }

    /**
     * @param array $data
     *
     * @return AuthLogInterface
     */
    public function createLogin($data = []): AuthLogInterface
    {

        return $this->writeRow($data);
    }

    /**
     * @param array $data
     *
     * @return AuthLogInterface
     */
    public function createLogout($data = []): AuthLogInterface
    {

        $this->logged_out_at = Carbon::now();

        if ($data) {
            foreach ($data as $k => $v) {
                $this->$k = $v;
            }
        }
        $this->save();

        return $this;
    }

    /**
     * @param int|null  $blame_on_user_id
     * @param bool|null $killed_from_console
     *
     * @return AuthLogInterface
     */
    public function createForcedLogout(?int $blame_on_user_id = null, ?bool $killed_from_console = false): AuthLogInterface
    {

        if (empty($blame_on_user_id)) {
            $blame_on_user_id = auth()->id();
        }

        return $this->createLogout([
            'blame_on_user_id' => $blame_on_user_id,
            'killed_from_console'  => $killed_from_console
        ]);
    }

    /**
     * @param string $new_session_id
     *
     * @return bool
     */
    public function changeSessionId($new_session_id = null): bool
    {

        if (!$new_session_id) {
            $new_session_id = getCurrentSessionId();
        }

        return $this->update([
            'session_id' => $new_session_id,
        ]);
    }

    /**
     * browser
     *
     * @return string
     */
    public function getBrowserNameAttribute(): string
    {

        if (preg_match('/^(?!.*(?:Chrome|Edge)).*Safari/', $this->user_agent)) {
            return 'safari';
        }
        if (preg_match('/^(?!.*(?:Chrome|Edge)).*Firefox/', $this->user_agent)) {
            return 'firefox';
        }
        if (preg_match('/^(?!.*Edge).*Chrome/', $this->user_agent)) {
            return 'chrome';
        }
        if (preg_match('/Edge/', $this->user_agent)) {
            return 'edge';
        }
    }

    /**
     * session_data
     *
     * @return null|string
     */
    public function getSessionDataAttribute(): ?string
    {

        return ($this->auth_session) ? $this->auth_session->getSessionData() : null;
    }

    /**
     * @return bool
     */
    public function killSession(): bool
    {

        $user_id = null;

        if (!app()->runningInConsole()) {
            /** @var User $user */
            $user = auth()->user();
            $user_id = $user->id;

        }

        if ($this->is_still_logged_in) {
            if ($this->auth_session->kill()) {
                $this->createForcedLogout($user_id, true);

                return true;
            }
        }

        return false;
    }

    /**
     * @param $data
     *
     * @return AuthLogInterface
     */
    public function writeRow($data): AuthLogInterface
    {

        return self::create($data);
    }

    /**
     * is_still_logged_in
     *
     * @return bool
     */
    public function getIsStillLoggedInAttribute(): bool
    {

        return !$this->logged_out_at && $this->session_data;
    }

}

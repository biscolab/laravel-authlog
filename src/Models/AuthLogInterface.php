<?php
/**
 * Copyright (c) 2019 - present
 * Laravel Auth Log - AuthLogInterface.php
 * author: Roberto Belotti - roby.belotti@gmail.com
 * web : robertobelotti.com, github.com/biscolab
 * Initial version created on: 18/9/2019
 * MIT license: https://github.com/biscolab/laravel-authlog/blob/master/LICENSE
 */

namespace Biscolab\LaravelAuthLog\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;

/**
 * Class UserLog
 * @property string session_id
 * @property User   user
 * @property bool   user_logout
 * @property int    user_id
 * @property mixed  created_at
 * @property string user_agent
 * @property string browser
 * @property string browser_name
 * @property int    id
 * @package App
 */
interface AuthLogInterface
{

    /**
     * user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo;

    /**
     * user_name
     *
     * @return string
     */
    public function getUserNameAttribute(): string;

    /**
     * @param array $data
     *
     * @return AuthLogInterface
     */
    public function createLogin($data = []): self;

    /**
     * @param array $data
     *
     * @return AuthLogInterface
     */
    public function createLogout($data = []): self;

    /**
     * @param int|null $blame_on_user_id
     *
     * @return AuthLogInterface
     */
    public function createForcedLogout(?int $blame_on_user_id = null): self;
//
//    /**
//     * user_simple_logout
//     *
//     * @return UserSession
//     */
//    public function getUserSimpleLogoutAttribute(): ?UserSession
//    {
//
//        return UserSession::where('session_id', $this->session_id)->whereIn('event_type', [
//            UserActionTypeEnum::LOGOUT,
//        ])->first();
//    }
//
//    /**
//     * user_forced_logout
//     *
//     * @return UserSession
//     */
//    public function getUserForcedLogoutAttribute(): ?UserSession
//    {
//
//        return self::where('session_id', $this->session_id)->whereIn('event_type', [
//            UserActionType::FORCED_LOGOUT,
//        ])->first();
//    }

    /**
     * @param string $new_session_id
     *
     * @return bool
     */
    public function changeSessionId($new_session_id = null): bool;

    /**
     * browser
     *
     * @return string
     */
    public function getBrowserNameAttribute(): string;
//
//    /**
//     * browser_icon
//     *
//     * @return string
//     */
//    public function getBrowserIconAttribute(): string
//    {
//
//        return $this->browser_name;
//    }
//
//    /**
//     * os
//     *
//     * @return string
//     */
//    public function getOsAttribute(): string
//    {
//
//        $os = '';
//
//        $os_array = [
//            '/windows/i'            => 'Windows',
//            '/windows nt 10/i'      => 'Windows 10',
//            '/windows nt 6.2/i'     => 'Windows 8',
//            '/windows nt 6.1/i'     => 'Windows 7',
//            '/windows nt 6.0/i'     => 'Windows Vista',
//            '/windows nt 5.2/i'     => 'Windows Server 2003/XP x64',
//            '/windows nt 5.1/i'     => 'Windows XP',
//            '/windows xp/i'         => 'Windows XP',
//            '/windows nt 5.0/i'     => 'Windows 2000',
//            '/windows me/i'         => 'Windows ME',
//            '/win98/i'              => 'Windows 98',
//            '/win95/i'              => 'Windows 95',
//            '/win16/i'              => 'Windows 3.11',
//            '/macintosh|mac os x/i' => 'Mac OS X',
//            '/mac_powerpc/i'        => 'Mac OS 9',
//            '/linux/i'              => 'Linux',
//            '/ubuntu/i'             => 'Ubuntu',
//            '/iphone/i'             => 'iPhone',
//            '/ipod/i'               => 'iPod',
//            '/ipad/i'               => 'iPad',
//            '/android/i'            => 'Android',
//            '/blackberry/i'         => 'BlackBerry',
//            '/webos/i'              => 'Mobile'
//        ];
//
//        foreach ($os_array as $regex => $value) {
//            if (preg_match($regex, $this->user_agent)) {
//                $os = $value;
//            }
//        }
//
//        return $os;
//    }
//
//    /**
//     * os_icon
//     *
//     * @return string
//     */
//    public function getOsIconAttribute(): string
//    {
//
//        switch (strtolower($this->os)) {
//            case 'macintosh':
//            case 'mac os x':
//            case 'mac os 9':
//            case 'iphone':
//            case 'ipod':
//            case 'ipad':
//                return 'apple';
//                break;
//
//            case 'windows':
//            case 'windows 10':
//            case 'windows 8':
//            case 'windows 7':
//            case 'windows vista':
//            case 'windows server 2003/xp x64':
//            case 'windows xp':
//            case 'windows 2000':
//            case 'windows me':
//            case 'windows 98':
//            case 'windows 95':
//            case 'windows 3.11':
//                return 'windows';
//                break;
//            case 'linux':
//            case 'ubuntu':
//                return 'linux';
//                break;
//            case 'android':
//                return 'android';
//                break;
//        }
//
//        return '';
//    }

    /**
     * session_data
     *
     * @return null|string
     */
    public function getSessionDataAttribute(): ?string;

    /**
     * @return bool
     */
    public function killSession(): bool;

    /**
     * @param $data
     *
     * @return AuthLogInterface
     */
    public function writeRow($data): self;
}

<?php
/**
 * Copyright (c) 2019 - present
 * Laravel Auth Log - GetLoggedUsersCommand.php
 * author: Roberto Belotti - roby.belotti@gmail.com
 * web : robertobelotti.com, github.com/biscolab
 * Initial version created on: 19/9/2019
 * MIT license: https://github.com/biscolab/laravel-authlog/blob/master/LICENSE
 */

namespace Biscolab\LaravelAuthLog\Commands;

use Biscolab\LaravelAuthLog\Models\AuthLog;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class GetLoggedUsersCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'authlog:logged';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get logged in users list';

    /**
     * @var null|Collection
     */
    protected $logged_in = null;

    /**
     * DatabaseClean constructor.
     */
    public function __construct()
    {

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->getAuthLogTable();

        while (($selected_id = trim(strtolower($this->ask('Type Log ID to kill session. Type "exit" to quit')))) != 'exit') {

            if (is_numeric($selected_id)) {

                /** @var AuthLog $auth_log */
                $auth_log = Arr::get($this->logged_in, $selected_id);

                if ($auth_log && $auth_log->killSession()) {
                    $this->info('Session "' . $auth_log->session_id . '" deleted. "' . $auth_log->user_name . '" user logged out');
                } else {
                    $this->error('Something went wrong');
                }

                $this->getAuthLogTable();
            } else {
                $this->error('Please type a numeric log ID');
            }
        }
    }

    /**
     * @return void
     */
    protected function getAuthLogTable(): void
    {

        $this->logged_in = AuthLog::getStillLoggedIn()->keyBy('id');

        if ($this->logged_in->count()) {
            $logged_in_array = $this->logged_in->map(function ($log) {

                return [
                    $log->id,
                    $log->session_id,
                    $log->user_name . ' (#' . $log->user_id . ')',
                    $log->created_at,
                ];
            });
            $headers = ['Log ID', 'Session ID', 'User', 'Logged @'];

            $this->table($headers, $logged_in_array);
        } else {
            $this->error('No logged user, please type "exit" to quit');
        }
    }
}

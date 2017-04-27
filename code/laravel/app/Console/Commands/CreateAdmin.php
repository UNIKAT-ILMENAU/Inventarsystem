<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new admin';

    /**
     * Create a new command instance.
     *
     * @return void
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
        $mail = $this->ask('Mail');
        $name = $this->ask('Name');
        $password = $this->ask('Password');

        $default = 1;
        $confirm = $this->choice('Confirm?', ['n', 'y'], $default);

        if($confirm === 'y') {
            $user = new User();
            $user->name = $name;
            $user->email = $mail;
            $user->password = Hash::make($password);
            $user->active = 1;

            $user->save();
        }
    }
}

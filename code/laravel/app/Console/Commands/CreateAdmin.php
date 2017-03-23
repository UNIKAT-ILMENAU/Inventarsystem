<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
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
    protected $description = 'Create an admin user';

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
        $firstName = $this->ask('First Name');
        $lastName = $this->ask('Last Name');
        $password = $this->secret('Password');

        $default = 1;
        $confirm = $this->choice('Confirm?', ['n', 'y'], $default);

        if($confirm === 'y') {
            DB::transaction(function () use ($password, $firstName, $lastName, $mail) {
                $id = DB::table('member')->insertGetId([
                    'isActivated' => 1,
                    'isAdmin' => 1,
                    'password' => Hash::make($password)
                ]);

                DB::table('user')->insert([
                    'member_id' => $id,
                    'FirstName' =>$firstName,
                    'LastName' =>$lastName,
                    'Email' =>$mail
                ]);
            });
        }
    }
}

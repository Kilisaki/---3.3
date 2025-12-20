<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class SetAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:set-admin {email : Email of the user} {--remove : Remove admin rights instead of setting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set or remove admin rights for a user by email';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');
        $remove = $this->option('remove');

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("User with email '{$email}' not found.");
            return 1;
        }

        if ($remove) {
            if (! $user->is_admin) {
                $this->info("User '{$email}' is not an admin.");
                return 0;
            }

            $user->is_admin = false;
            $user->save();

            $this->info("Admin rights removed from '{$email}'.");
            return 0;
        }

        if ($user->is_admin) {
            $this->info("User '{$email}' is already an admin.");
            return 0;
        }

        $user->is_admin = true;
        $user->save();

        $this->info("User '{$email}' is now an admin.");

        return 0;
    }
}

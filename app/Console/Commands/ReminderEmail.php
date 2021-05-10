<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\EventsDailyNotification;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReminderEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remainder:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily emails to users';

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
     * @return int
     */
    public function handle()
    {

        $users = User::whereHas('events')->withCount([
          'events',
          'events as events_count' => function (Builder $query) {
              $query->whereDate('event_date', Carbon::today());
          }
        ])->get();
        foreach($users as $user){
          if($user->events_count > 0)
          {
            $user->notify(new EventsDailyNotification($user));
          }
        }

    }
}

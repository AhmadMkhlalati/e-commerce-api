<?php

namespace App\Console\Commands;

use App\Models\Settings\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Console\Command;

class AddNewSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:add {--isDeveloper}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a new Settings to the settings table in the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        repeat:
        $title = $this->ask('What is the title of the new settings');
        $type = $this->choice(
            'What is the type of the new settings',
            Setting::$types,'text',
        );
        $isDeveloper = $this->option('isDeveloper');
        $isDeveloper = is_null($isDeveloper) ? false : (bool)$isDeveloper;

        recheck:
        if ( ! $this->confirm("You are about to create a new settings with the following data: title => $title , and type => $type . Are you sure you want to continue ?")) {
            if(!$this->confirm('Would you like to repeat the process ? ')){
                $this->info('The creation process was stopped successfully ');
                return 0;
            }
                goto repeat;
        }

        $settings = Setting::query()->create([
            'title' => $title,
            'type' => $type,
            'is_developer' => $isDeveloper,
            'value' => null
        ]);

        if(!$settings->wasRecentlyCreated){
            $this->error('An error occurred while creating the data please check the inputs and try again later ');
            goto recheck;
        }

        $this->info("The settings $title was created successfully!");

        return 1;


    }
}

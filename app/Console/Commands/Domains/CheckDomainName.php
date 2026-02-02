<?php

namespace App\Console\Commands\Domains;

use App\Models\Domain;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckDomainName extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domains:check-domain-name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Format all the saved domain names in our database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $domains = Domain::all();

        foreach ($domains as $domain) {
            
            $url = preg_replace("(^https?://)", "", $domain->name);

            $dm = Domain::find($domain->id);
            $dm->name = $url;
            $dm->save();
        }

        return Command::SUCCESS;
    }
}

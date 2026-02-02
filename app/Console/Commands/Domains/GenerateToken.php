<?php

namespace App\Console\Commands\Domains;

use App\Models\Domain;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domains:token {id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate token for existing user domains';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (! $this->argument('id')) {
            $this->regenerateAllDomainWithoutTokens();
            return Command::SUCCESS;
        }
        
        $domain = Domain::find($this->argument('id'));
        $this->info("Domain: " . $domain);

        if ($domain && $this->confirm("Are you sure you want to regenerate tokens for this domain?", false)) {
            $this->generateToken($domain);
            return Command::SUCCESS;
        }

        $this->error("Domain with id {$this->argument('id')} does not exists.");
        return Command::FAILURE;        
    }

    public function regenerateAllDomainWithoutTokens()
    {
        $domains = Domain::whereNull('token')->get();
        $this->info("Domains ids: " . $domains->pluck('id'));

        if ($this->confirm("Are you sure you want to regenerate tokens for these domains?", false)) {
            foreach ($domains as $key => $domain) {
                $this->generateToken($domain);
            }
        }
    }

    public function generateToken(Domain $domain)
    {
        $domain->update(['token' =>
            Str::random(10) . $domain->created_at->format('mdY') . Str::random(10) . $domain->created_at->format('His') . Str::random(10)
        ]);
    }
}

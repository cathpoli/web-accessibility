<?php

namespace App\Jobs;

use App\Models\Domain;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;

class TakeDomainBrowsershotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $domain;

    private $url;

    private $directory = 'assets/images/domain-previews';

    private $file;

    private $publicPath;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $domainId)
    {
        $this->domain = Domain::find($domainId);

        $this->url = "https://{$this->domain->name}";

        $this->file = "{$this->directory}/{$this->domain->id}-{$this->domain->name}.jpg";

        $this->publicPath = public_path($this->file);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $request = Http::get($this->url);
        } catch (\Illuminate\Http\Client\ConnectionException $th) {
            Log::error("TakeDomainBrowsershotJob: {$th->getMessage()}");
            return;
        }

        $this->deleteExistingBrowsershot();

        $this->saveDomainBrowsershot();
    }

    public function deleteExistingBrowsershot()
    {
        if (File::exists($this->publicPath)) File::delete($this->publicPath);
    }

    public function saveDomainBrowsershot()
    {
        $timeout = config('browsershot.timeout');

        /** Reference: https://spatie.be/docs/browsershot/v2/introduction */
        Browsershot::url($this->url)
            ->setOption('landscape', true)
            ->windowSize(1920, 1080)
            ->waitUntilNetworkIdle()
            ->newHeadless()
            ->timeout($timeout)
            ->save($this->publicPath);

        $this->domain->update(['preview_img' => $this->file]);
    }
}

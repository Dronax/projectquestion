<?php

namespace App\Console\Commands;

use App\Channel;
use App\Theard;
use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator as SitemapGenerate;

class SitemapGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate site sitemap';

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
        $sitemap = SitemapGenerate::create(config('app.url'));

        // $questions = Theard::all();
        // $channels = Channel::all();

        // $questions->each(function ($question) use ($sitemap) {
        //     $sitemap->add(config('app.url') . '/questions/' . $question->slug);
        // });

        // $channels->each(function ($channel) use ($sitemap) {
        //     $sitemap->add(config('app.url') . '/tagged/' . $channel->slug);
        // });

        $sitemap->writeToFile(public_path('sitemap.xml'));
    }
}

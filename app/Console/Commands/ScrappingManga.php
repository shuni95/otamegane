<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScrappingManga extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrapper:manga';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrapping a manga webpage';

    /**
     * Collection of scrappers
     * @var Collection
     */
    protected $scrappers;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->namespace = 'App\Services\\';

        $this->scrappers = collect([
            'Scrappers\\MangaStreamScrapper',
            'Scrappers\\MangaPandaScrapper',
            'Scrappers\\MangaFoxScrapper',
            'ApiScrappers\\TuMangaOnlineScrapper',
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->scrappers->each(function ($scrapper) {
            resolve($this->namespace. $scrapper)->scrapping();
        });
    }
}

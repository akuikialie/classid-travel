<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Fluent;
use function Termwind\{terminal, render};
use Termwind\HtmlRenderer;

class ScraperIndoRegion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrap:indo-region {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scraping Indonesian Region from https://kodepos.nomor.net/';

    private FakerGenerator|FakerFactory $faker;
    private HtmlRenderer $html;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // $this->faker = FakerFactory::create();
        $this->faker = fake();
        $this->html = new HtmlRenderer();

        terminal()->clear();
        $this->newLine(2);
        render(<<<'HTML'
            <div class="bg-blue-600">
                <div class="justify-between"><span>&nbsp;</span><span>&nbsp;</span></div>
                <div class="justify-between">
                    &nbsp;
                    <span class="font-bold uppercase">Scraping Indonesian Region</span>
                    &nbsp;
                </div>
                <div class="justify-between"><span>&nbsp;</span><span>&nbsp;</span></div>
            </div>
        HTML);
        $this->newLine(2);

        $isEmptyGender = $this->option('force') ?: $this->components->confirm("Really start scraping ?", true);
        if ($isEmptyGender) {
            $this->startScraping();
        }

        $this->newLine(2);
        render('<div class="justify-center text-green-600">~ Thanks using this Scraper ~</div>');
        $this->newLine();

        return static::SUCCESS;
    }

    private function startScraping(): void
    {
        $this->components->info("Starting ...");

        DB::unprepared("truncate table only geo_temporary restart identity cascade;");
        DB::commit();

        $provinces = collect([
            ['code' => '11', 'name' => 'Aceh (NAD)'],
            ['code' => '12', 'name' => 'Sumatera Utara'],
            ['code' => '13', 'name' => 'Sumatera Barat'],
            ['code' => '14', 'name' => 'Riau'],
            ['code' => '15', 'name' => 'Jambi'],
            ['code' => '16', 'name' => 'Sumatera Selatan'],
            ['code' => '17', 'name' => 'Bengkulu'],
            ['code' => '18', 'name' => 'Lampung'],
            ['code' => '19', 'name' => 'Kepulauan Bangka Belitung'],
            ['code' => '21', 'name' => 'Kepulauan Riau'],
            ['code' => '31', 'name' => 'DKI Jakarta'],
            ['code' => '32', 'name' => 'Jawa Barat'],
            ['code' => '33', 'name' => 'Jawa Tengah'],
            ['code' => '34', 'name' => 'DI Yogyakarta'],
            ['code' => '35', 'name' => 'Jawa Timur'],
            ['code' => '36', 'name' => 'Banten'],
            ['code' => '51', 'name' => 'Bali'],
            ['code' => '52', 'name' => 'Nusa Tenggara Barat (NTB)'],
            ['code' => '53', 'name' => 'Nusa Tenggara Timur (NTT)'],
            ['code' => '61', 'name' => 'Kalimantan Barat'],
            ['code' => '62', 'name' => 'Kalimantan Tengah'],
            ['code' => '63', 'name' => 'Kalimantan Selatan'],
            ['code' => '64', 'name' => 'Kalimantan Timur'],
            ['code' => '65', 'name' => 'Kalimantan Utara'],
            ['code' => '71', 'name' => 'Sulawesi Utara'],
            ['code' => '72', 'name' => 'Sulawesi Tengah'],
            ['code' => '73', 'name' => 'Sulawesi Selatan'],
            ['code' => '74', 'name' => 'Sulawesi Tenggara'],
            ['code' => '75', 'name' => 'Gorontalo'],
            ['code' => '76', 'name' => 'Sulawesi Barat'],
            ['code' => '81', 'name' => 'Maluku'],
            ['code' => '82', 'name' => 'Maluku Utara'],
            ['code' => '91', 'name' => 'Papua'],
            ['code' => '92', 'name' => 'Papua Barat'],
            ['code' => '92.', 'name' => 'Papua Barat Daya'],
            ['code' => '93', 'name' => 'Papua Selatan'],
            ['code' => '94', 'name' => 'Papua Tengah'],
            ['code' => '95', 'name' => 'Papua Pegunungan'],
        ]);

        $provinces->each(function ($_prov) {
            $prov = new Fluent($_prov);
            $this->components->task("Scraping provinsi [{$prov->code}] {$prov->name}", function () use ($prov) {
                $data = [
                    'id' => $prov->code,
                    'parent_id' => '00',
                    'type' => 'province',
                    'sub_type' => 'province',
                    'name' => $prov->name,
                ];
                $data['raw'] = json_encode($data);
                DB::table('geo_temporary')
                    ->updateOrInsert(
                        attributes: Arr::only($data, ['id', 'parent_id', 'type', 'name']),
                        values: Arr::except($data, ['id', 'parent_id', 'type', 'name'])
                    );
                // DB::table('geo_temporary')->insert($data);

                dispatch(new \App\Jobs\Scraper\ScraperIndoRegion(
                    search: $prov->name,
                    parentId: $data['id'],
                    mode: 'city'
                ));
            });
        });

        $this->components->info("successfully scrapped");
    }
}

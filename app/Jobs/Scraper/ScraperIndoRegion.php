<?php

namespace App\Jobs\Scraper;

use App\Core\Dom\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Stringable;
use Symfony\Component\DomCrawler\Crawler;
use Throwable;

class ScraperIndoRegion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly string $search,
        private readonly string $parentId = '00',
        private readonly string $mode = 'province',
        private readonly string|null $daerah = null,
        private readonly bool $sync = false
    ) {
        //
    }

    /** @inheritdoc */
    public function tags(): array
    {
        return ['scrapper', 'scrapper-indo-' . $this->mode];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        match ($this->mode) {
            'city' => $this->buildCity(),
            'district' => $this->buildDistrict(),
            'sub_district' => $this->buildSubDistrict(),
            default => $this->buildProvince(),
        };
    }

    /**
     * @param  string|null  $daerah
     *
     * @return string
     */
    private function getSearchUrl(string|null $daerah = null): string
    {
        // $baseUrl = 'https://www.nomor.net/_kodepos.php';
        $baseUrl = 'http://www.nomor.net/_kodepos.php';
        $daerah ??= $this->daerah;

        if ($daerah && str($daerah)->lower()->startsWith(['kab.', 'kota'])) {
            $daerah = str($daerah)->lower()->startsWith('kab.')
                ? 'Kabupaten'
                : 'Kota';
        }

        $qString = match ($this->mode) {
            'city' => [
                '_i' => 'kota-kodepos',
                'daerah' => 'Provinsi',
                'jobs' => $this->search,
                'sby' => '010000',
                'asc' => '00001110',
                'perhal' => 10000,
                'urut' => 10,
            ],
            'district' => [
                '_i' => 'kecamatan-kodepos',
                'daerah' => $daerah ?? 'Kota',
                'jobs' => $this->search,
                'sby' => '010000',
                'asc' => '0010001',
                'perhal' => 10000,
                'urut' => 9,
            ],
            'sub_district' => [
                '_i' => 'desa-kodepos',
                'daerah' => $daerah ?? 'Kecamatan',
                'jobs' => $this->search,
                'sby' => '010000',
                'asc' => '0001011',
                'perhal' => 10000,
                'urut' => 8,
            ],
            default => [
                '_i' => 'kode-wilayah',
                'asc' => '000',
                'urut' => 2,
            ],
        };

        return $baseUrl . '?' . http_build_query($qString);
    }

    private function crawler(string $method = 'GET'): Crawler
    {
        // dd($method, $this->getSearchUrl());
        $client = new Client();
        return $client->request($method, $this->getSearchUrl());
        // return GoutteFacade::request(strtoupper($method), $this->getSearchUrl());
    }

    private function buildProvince(): void
    {
        $this->crawler()
            ->filter('#Provinsi2 > table > tr')->each(function (Crawler $node, $i) {
                $td = $node->filter('td');

                $code = $this->sanitizeCode($td->getNode(2)->textContent);
                $name = str($td->getNode(1)->textContent)->trim()->toString();

                if ($code->isMatch('/^\d[.0-9]+\d$/')) {
                    if (str($name)->slug()->is('papua-barat-daya')) {
                        $code = $code->append('.');
                    }

                    $data = [
                        'id' => $code->toString(),
                        'parent_id' => $this->parentId,
                        'type' => 'province',
                        'sub_type' => 'province',
                        'name' => $name,
                    ];
                    $this->save($data);

                    if ($this->sync) {
                        dispatch_sync(new static(
                            search: $data['name'],
                            parentId: $data['id'],
                            mode: 'city',
                            daerah: 'Provinsi',
                            sync: $this->sync
                        ));
                    } else {
                        dispatch(new static(
                            search: $data['name'],
                            parentId: $data['id'],
                            mode: 'city',
                            daerah: 'Provinsi',
                            sync: $this->sync
                        ));
                    }
                }
            });
    }

    private function buildCity(): void
    {
        $this->crawler()
            ->filter('body table[bgcolor="#ffccff"] > tr.cstr')
            ->each(function (Crawler $node, $i) use (& $parentId) {
                $td = $node->filter('td');

                $code = $this->sanitizeCode($td->getNode(8)->textContent);
                $name = str($td->getNode(2)->textContent)->trim()->toString();

                if ($code->isMatch('/^\d[.0-9]+\d$/')) {
                    $subType = $code->isMatch('/^[.0-9]+\.[0-6]\d+$/')
                        ? 'kabupaten'
                        : 'kota';

                    $data = [
                        'id' => $code->toString(),
                        'parent_id' => $this->parentId,
                        'type' => 'city',
                        'sub_type' => $subType,
                        'name' => $name,
                        'additional' => json_encode([
                            'postal_code' => $td->getNode(4)->textContent,
                        ]),
                    ];
                    $this->save($data);

                    $daerah = str($subType)->is('kabupaten') ? 'Kab.-' : 'Kota-';

                    if ($this->sync) {
                        dispatch_sync(new static(
                            search: $data['name'],
                            parentId: $data['id'],
                            mode: 'district',
                            daerah: $daerah . $name,
                            sync: $this->sync
                        ));
                    } else {
                        dispatch(new static(
                            search: $data['name'],
                            parentId: $data['id'],
                            mode: 'district',
                            daerah: $daerah . $name,
                            sync: $this->sync
                        ));
                    }
                }
            });
    }

    private function buildDistrict(): void
    {
        $this->crawler()
            ->filter('body table[bgcolor="#ffccff"] > tr')
            ->each(function (Crawler $node, $i) use (& $parentId) {
                $td = $node->filter('td');

                $code = $this->sanitizeCode($td->getNode(5)->textContent);
                $name = str($td->getNode(1)->textContent)->trim()->toString();

                if ($code->isMatch('/^\d[.0-9]+\d$/')) {
                    $data = [
                        'id' => $code->toString(),
                        'parent_id' => $this->parentId,
                        'type' => 'district',
                        'sub_type' => 'Kecamatan',
                        'name' => $name,
                        'additional' => json_encode([
                            'postal_code' => $td->getNode(2)->textContent,
                        ]),
                    ];
                    $this->save($data);

                    $daerah = 'Kecamatan-' . $this->daerah;

                    if ($this->sync) {
                        dispatch_sync(new static(
                            search: $data['name'],
                            parentId: $data['id'],
                            mode: 'sub_district',
                            daerah: $daerah,
                            sync: $this->sync
                        ));
                    } else {
                        dispatch(new static(
                            search: $data['name'],
                            parentId: $data['id'],
                            mode: 'sub_district',
                            daerah: $daerah,
                            sync: $this->sync
                        ));
                    }
                }
            });
    }

    private function buildSubDistrict(): void
    {
        $this->crawler()
            ->filter('body table[bgcolor="#ffccff"] > tr[bgcolor="#ccffff"]')
            ->each(function (Crawler $node, $i) use (& $parentId) {
                $td = $node->filter('td');

                $code = $this->sanitizeCode($td->getNode(3)->textContent);
                $name = str($td->getNode(2)->textContent)->trim()->toString();

                if ($code->isMatch('/^\d[.0-9]+\d$/')) {
                    $subType = $code->isMatch('/^[.0-9]+\.1\d+$/')
                        ? 'kelurahan'
                        : 'desa';

                    $this->save([
                        'id' => $code->toString(),
                        'parent_id' => $this->parentId,
                        'type' => 'sub_district',
                        'sub_type' => $subType,
                        'name' => $name,
                        'additional' => json_encode([
                            'postal_code' => $td->getNode(1)->textContent,
                        ]),
                    ]);

                }
            });
    }

    private function save(array $data): bool
    {
        $data['raw'] = json_encode($data);

        try {
            // return (bool)DB::table('geo_temporary')->upsert($data, ['geo_temporary_main_unq'], ['sub_type', 'additional']);
            return DB::table('geo_temporary')
                ->updateOrInsert(
                    attributes: Arr::only($data, ['id', 'parent_id', 'type', 'name']),
                    values: Arr::except($data, ['id', 'parent_id', 'type', 'name'])
                );
            // return DB::table('geo_temporary')->insert($data);
        } catch (Throwable $e) {
            app('log')->error('indo-region::' . $data['type'] . '-' . $data['id'], [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        return false;
    }

    private function getParentId(Stringable|string $id): string
    {
        if (! $id instanceof Stringable) {
            $id = str($id);
        }

        return $id
            ->replaceMatches('/^([.0-9]+)\.\d+$/', '$1')
            ->toString();
    }

    private function sanitizeCode(Stringable|string $code): Stringable
    {
        if (! $code instanceof Stringable) {
            $code = str($code);
        }

        return $code
            ->replaceMatches('/[^.0-9]/', '')
            ->replaceMatches('/^\.+|\.+$/', '')
            ->replaceMatches('/\.+/', '.');
    }
}

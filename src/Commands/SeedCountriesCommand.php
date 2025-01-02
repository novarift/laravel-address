<?php

declare(strict_types=1);

namespace Novarift\Address\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\View\Components\TwoColumnDetail;

class SeedCountriesCommand extends Command
{
    public $signature = 'address:seed-countries';

    public $description = 'Seed countries to the countries table.';

    public function handle(): int
    {
        $this->comment('Seeding countries...');

        $countries = json_decode(file_get_contents(__DIR__.'/../../data/countries.json'), true);

        $total = count($countries);

        collect($countries)
            ->each(function (array $country, int $index) use ($total) {
                config('address.models.country')::updateOrCreate(['code' => $country['code']], $country);

                $current = $index + 1;

                with(new TwoColumnDetail($this->output))->render($country['name'], "$current/$total");
            });

        $this->comment('Done!');

        return self::SUCCESS;
    }
}

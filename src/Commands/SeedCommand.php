<?php

declare(strict_types=1);

namespace Novarift\Address\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\View\Components\TwoColumnDetail;
use Illuminate\Support\Collection;
use Novarift\Address\Models\Country;
use Novarift\Address\Models\State;

class SeedCommand extends Command
{
    public string $PATH = __DIR__.'/../../data';

    public $signature = 'address:seed';

    public $description = 'Seed countries and its relation for references.';

    public function handle(): int
    {
        $this->countries();

        return self::SUCCESS;
    }

    protected function countries(): void
    {
        $this->comment('Seeding countries...');

        $countries = json_decode(file_get_contents("$this->PATH/countries.json"), true);

        $total = count($countries);

        collect($countries)
            ->each(function (array $country, int $index) use ($total) {
                $country = config('address.models.country')::updateOrCreate(['code' => $country['code']], $country);

                $current = $index + 1;

                with(new TwoColumnDetail($this->output))->render($country['name'], "$current/$total");

                $this->states($country)?->each(fn (State $state) => $this->districts($state, $country));
            });

        $this->comment('Done!');
    }

    protected function states(Country $country): ?Collection
    {
        $file = "$this->PATH/countries/{$country->code}/states.json";

        if (! file_exists($file)) {
            return null;
        }

        if (($states = collect(json_decode(file_get_contents($file), true)))->isEmpty()) {
            return null;
        }

        $this->comment('Seeding states...');

        $progress = $this->output->createProgressBar($states->count());
        $progress->start();

        $states->map(function (array $state) use ($progress, $country) {
            $state = config('address.models.state')::updateOrCreate(['country_id' => $country->id, 'code' => $state['code']], $state);

            $progress->advance();

            return $state;
        });

        $progress->finish();

        return $states;
    }

    protected function districts(State $state, Country $country): ?Collection
    {
        $file = "$this->PATH/countries/{$country->code}/states/{$state->code}/districts.json";

        if (! file_exists($file)) {
            return null;
        }

        if (($districts = collect(json_decode(file_get_contents($file), true)))->isEmpty()) {
            return null;
        }

        $this->comment('Seeding districts...');

        $progress = $this->output->createProgressBar($districts->count());
        $progress->start();

        $districts->map(function (array $district) use ($progress, $state) {
            $district = config('address.models.district')::updateOrCreate(['state_id' => $state->code, 'code' => $district['code']], $district);

            $progress->advance();

            return $district;
        });

        $progress->finish();

        return $districts;
    }
}

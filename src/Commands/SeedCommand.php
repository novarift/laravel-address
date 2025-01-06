<?php

declare(strict_types=1);

namespace Novarift\Address\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\View\Components\TwoColumnDetail;
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

                $this->comment('Seeding states...');

                $this->states($country);

                with(new TwoColumnDetail($this->output))->render($country['name'], "$current/$total");
            });

        $this->comment('Done!');
    }

    protected function states(Country $country): void
    {
        $file = "$this->PATH/countries/{$country->code}/states.json";

        if (! file_exists($file)) {
            return;
        }

        if (($states = collect(json_decode(file_get_contents($file), true)))->isEmpty()) {
            return;
        }

        $progress = $this->output->createProgressBar($states->count());
        $progress->start();

        $states->each(function (array $state) use ($progress, $country) {
            $state = config('address.models.state')::updateOrCreate(['country_id' => $country->id, 'code' => $state['code']], $state);

            $this->comment('Seeding states...');

            $this->districts($state, $country);

            $progress->advance();
        });

        $progress->finish();
    }

    protected function districts(State $state, Country $country): void
    {
        $file = "$this->PATH/countries/{$country->code}/states/{$state->code}/districts.json";

        if (! file_exists($file)) {
            return;
        }

        if (($districts = collect(json_decode(file_get_contents($file), true)))->isEmpty()) {
            return;
        }

        $progress = $this->output->createProgressBar($districts->count());
        $progress->start();

        $districts->each(function (array $district) use ($progress, $state) {
            config('address.models.district')::updateOrCreate(['state_id' => $state->code, 'code' => $district['code']], $district);

            $progress->advance();
        });

        $progress->finish();
    }
}

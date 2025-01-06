<?php

declare(strict_types=1);

namespace Novarift\Address\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\View\Components\TwoColumnDetail;
use Illuminate\Support\Collection;
use Novarift\Address\Models\Country;
use Novarift\Address\Models\District;
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

                $this->states($country);
                $this->postOffices($country);
                $this->districts($country);
                $this->mukims($country);
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

        $this->comment('Seeding states...');

        $progress = $this->output->createProgressBar($states->count());
        $progress->start();

        $states->each(function (array $state) use ($progress, $country) {
            config('address.models.state')::updateOrCreate(['country_id' => $country->id, 'code' => $state['code']], $state);
            $progress->advance();
        });

        $progress->finish();
        $this->newLine();
    }

    protected function postOffices(Country $country): void
    {
        $collection = $country->states
            ->mapWithKeys(fn (State $state) => [$state->id => "$this->PATH/countries/{$country->code}/states/{$state->code}/post-offices.json"])
            ->filter(fn (string $file) => file_exists($file))
            ->map(fn (string $file) => collect(json_decode(file_get_contents($file), true)))
            ->filter(fn (Collection $collection) => $collection->isNotEmpty());

        if ($collection->isEmpty()) {
            return;
        }

        $this->comment('Seeding post offices...');

        $progress = $this->output->createProgressBar($collection->flatten(1)->count());
        $progress->start();

        $collection->each(function (Collection $postOffices, int $state) use ($progress) {
            $state = config('address.models.state')::find($state);

            return $postOffices->each(function (array $office) use ($progress, $state) {
                config('address.models.post-office')::updateOrCreate(['state_id' => $state->id, 'name' => $office['name']], $office);
                $progress->advance();
            });
        });

        $progress->finish();
        $this->newLine();
    }

    protected function districts(Country $country): void
    {
        $collection = $country->states
            ->mapWithKeys(fn (State $state) => [$state->id => "$this->PATH/countries/{$country->code}/states/{$state->code}/districts.json"])
            ->filter(fn (string $file) => file_exists($file))
            ->map(fn (string $file) => collect(json_decode(file_get_contents($file), true)))
            ->filter(fn (Collection $collection) => $collection->isNotEmpty());

        if ($collection->isEmpty()) {
            return;
        }

        $this->comment('Seeding districts...');

        $progress = $this->output->createProgressBar($collection->flatten(1)->count());
        $progress->start();

        $collection->each(function (Collection $districts, int $state) use ($progress) {
            $state = config('address.models.state')::find($state);

            return $districts->each(function (array $district) use ($progress, $state) {
                config('address.models.district')::updateOrCreate(['state_id' => $state->id, 'code' => $district['code']], $district);
                $progress->advance();
            });
        });

        $progress->finish();
        $this->newLine();
    }

    protected function mukims(Country $country): void
    {
        $collection = $country->districts
            ->mapWithKeys(fn (District $district) => [$district->id => "$this->PATH/countries/{$country->code}/states/{$district->state->code}/districts/{$district->code}/mukims.json"])
            ->filter(fn (string $file) => file_exists($file))
            ->map(fn (string $file) => collect(json_decode(file_get_contents($file), true)))
            ->filter(fn (Collection $collection) => $collection->isNotEmpty());

        if ($collection->isEmpty()) {
            return;
        }

        $this->comment('Seeding mukims...');

        $progress = $this->output->createProgressBar($collection->flatten(1)->count());
        $progress->start();

        $collection->each(function (Collection $mukims, int $district) use ($progress) {
            $district = config('address.models.district')::find($district);

            return $mukims->each(function (array $mukim) use ($progress, $district) {
                config('address.models.mukim')::updateOrCreate(['district_id' => $district->id, 'code' => $mukim['code']], $mukim);
                $progress->advance();
            });
        });

        $progress->finish();
        $this->newLine();
    }
}

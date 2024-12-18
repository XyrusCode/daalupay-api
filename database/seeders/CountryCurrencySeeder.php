<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountryCurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {


            $countries = $this->getLocalCountriesData();
            $exchangeRates = $this->getLocalExchangeRates();

            if (empty($countries) || !is_array($countries) || empty($exchangeRates) || !is_array($exchangeRates)) {
                throw new \Exception("Failed to retrieve valid data for countries or exchange rates. Countries: " .
                    (is_array($countries) ? count($countries) : 'not array') .
                    ", Rates: " . (is_array($exchangeRates) ? count($exchangeRates) : 'not array'));
            }

            DB::beginTransaction();

            try {
                foreach ($countries as $country) {
                    if (isset($country['name']['common'], $country['currencies'])) {
                        $countryName = $country['name']['common'];
                        $countryCode = $country['cca3'];

                        $countryId = DB::table('countries')->insertGetId([
                            'name' => $countryName,
                            'code' => $countryCode,
                        ]);

                        foreach ($country['currencies'] as $currencyCode => $currencyDetails) {
                            $currencyName = $currencyDetails['name'] ?? null;

                            if ($currencyName) {
                                $exchangeRate = $exchangeRates[$currencyCode] ?? 1.00000000;
                                DB::table('currencies')->insert([
                                    'name' => $currencyName,
                                    'code' => $currencyCode,
                                    'exchange_rate' => $exchangeRate,
                                    'status' => 'enabled',
                                    'country_id' => $countryId,
                                ]);
                            }
                        }
                    }
                }

                DB::commit();
                $this->command->info('Countries and currencies seeded successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            $this->command->error("Seeding failed: " . $e->getMessage());
        }
    }

    private function getLocalCountriesData(): array
    {
        $path = database_path('seeders/data/countries.json');
        $this->command->info("Looking for file at: " . $path);

        if (!file_exists($path)) {
            // Try alternative path
            throw new \Exception("Countries data file not found at: $path");
        }

        $data = json_decode(file_get_contents($path), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("JSON decode error: " . json_last_error_msg());
        }

        // Ensure we have an array
        if (!is_array($data)) {
            $data = [$data];
        }

        $this->command->info("Found " . count($data) . " countries in JSON file");
        return $data;
    }

    private function getLocalExchangeRates(): array
    {
        $countries = $this->getLocalCountriesData();
        $rates = ['USD' => 1.0]; // Base currency

        foreach ($countries as $country) {
            if (isset($country['currencies'])) {
                foreach ($country['currencies'] as $code => $details) {
                    $rates[$code] = 1.0; // Set 1:1 exchange rate
                }
            }
        }

        // Debug output
        $this->command->info("Exchange rates count: " . count($rates));
        return $rates;
    }
}

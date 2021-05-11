<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;

class ContractsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Contract::class, 1)->create()->each(function ($contract) {
            $dates = [];
            for ($i = 1; $i <= 200; $i++) {
                switch ($contract->cycle) {
                    case 'daily':
                        $maturity = $contract->maturity->addDay();
                        break;
                    case 'weekly':
                        $maturity = $contract->maturity->addWeek();
                        break;
                    case 'fortnightly':
                        $maturity = $contract->maturity->addWeek(2);
                        break;
                    case 'monthly':
                        $maturity = $contract->maturity->addMonth();
                        break;
                }
                if(!$maturity->isSunday()){
                    $dates[] = $maturity->format('Y-m-d');
                }
                
            }

            $number = 1;
            foreach (collect($dates)->take($contract->split) as $date) {

            $contract->provisions()->create([
                'number' => $number++,
                'amount' => $contract->amount_provision,
                'maturity' => $date
            ]);
            }
            
        });
    }
}

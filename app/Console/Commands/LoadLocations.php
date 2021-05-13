<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Models\Product;
use App\Models\Tag;
use App\Models\Vendor;
use App\Models\Warehouse;
use Illuminate\Console\Command;
use Exception;
use Illuminate\Support\Facades\Log;

class LoadLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'locations:load';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $filename = 'locations.csv';

        // File upload location
        $location = 'app';

        // Upload file
//            $file->move($location, $filename);

        // Import CSV to Database
        $filepath = storage_path($location . "/" . $filename);

        // Reading file
        $file = fopen($filepath, "r");
        $heading = fgetcsv($file);
        $columns = [];
        foreach ($heading as $key => $title) {
            $title = str_replace('\'', '', $title);
            $title = preg_replace('/[\W_]+/', ' ', trim($title));
            $title = preg_replace('/\s{2,}/', ' ', trim($title));
            $columns[strtolower(str_replace(' ', '_', $title))] = $key;
        }

        $count = 1;

        $insert = [];
//        try {
            while (!feof($file)) {

                $data = fgetcsv($file);

                if ($data == false) {
                    continue;
                }

                $count++;

                $warehouse_id = 0;

                foreach ($columns as $column => $index) {
                    if ($column != '') {
                        $result = utf8_encode(trim($data[$index]));
                        $insert[$column] = $result != "" ? $result : null;
                    }
                }

                $product = Product::FirstOrCreate(['core_number' => $insert['product_code']]);

                if( $insert['warehouse'] != "") {
                    $warehouse_id = (Warehouse::FirstOrCreate(['warehouse' => $insert['warehouse']]))->id;
                }

                if( $insert['location'] != "") {
                    $location_id = (Location::FirstOrCreate(['location' => $insert['location']], ['warehouse_id' => $warehouse_id]))->id;
                    $quantity = str_replace(',', '', $insert['quantity']);
                    $product->locations()->syncWithoutDetaching([$location_id => [ 'quantity' => $quantity]]);
                }

                Log::info($insert);

            }
//        } catch (Exception $e) {
//            $this->warn($e->getMessage() . " error occured at row " . $count);
//            Log::info($e->getMessage() . " error occured at row " . $count);
//        }
    }
}

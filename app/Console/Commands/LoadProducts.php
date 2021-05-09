<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Tag;
use App\Models\Vendor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LoadProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:load';

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
        $filename = 'file.csv';

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

        $tagsLength = 0;
        $vendorProduct = [];
        $vendors = [];

        $bar = $this->output->createProgressBar(2781);

//        try {
            while (!feof($file)) {
                $tags = [];

                $data = fgetcsv($file);

                if ($data == false) {
                    continue;
                }

                $count++;

                foreach ($columns as $column => $index) {

                    if(strpos($column, 'vendor') !== false && $column != 'vendor' && $column != 'backup_vendor') {
                        $result = utf8_encode(trim($data[$index]));
                        $vendorProduct[$column] = $result != "" ? $result : null;
                    } else if($column == 'vendor') {
                        $vendor = utf8_encode(trim($data[$index]));
                        if( $vendor != "") {
                            $vendors[0] = (Vendor::FirstOrCreate(['vendor' => $vendor]))->id;
                        }
                    }else if ($column == 'backup_vendor') {
                        $vendor = utf8_encode(trim($data[$index]));
                        if( $vendor != "") {
                            $vendors[1] = (Vendor::FirstOrCreate(['vendor' => $vendor]))->id;
                        }
                    } else if(strpos($column, 'tag') !== false && $column != 'tags_info') {
                        $tag = utf8_encode(trim($data[$index]));
                        if( $tag != "") {
                            $tagsLength++;
                            array_push($tags, Tag::FirstOrCreate(['tag' => $tag])->id);
                        }
                    } else if ($column != '') {
                        $result = utf8_encode(trim($data[$index]));
                        $insert[$column] = $result != "" ? $result : null;
                    }
                }

                Log::info($insert);

                $product = Product::UpdateOrCreate(['core_number' => $insert['core_number']], $insert);

                if ($product->wasRecentlyCreated) {
                    $this->info($insert['core_number'] . ' has been created');
                } else {
                    $this->info($insert['core_number'] . ' has been updated');
                }

                if($tagsLength > 0) {
                    $product->tags()->syncWithoutDetaching($tags);
                }

                $vendorProductBackUp = $vendorProduct;
                if($vendors[0] != null){
                    unset($vendorProduct['backup_vendor_sku']);
                    $product->vendors()->syncWithoutDetaching([
                        $vendors[0] => $vendorProduct
                    ]);
                }

                if($vendors[1] != null){
                    $vendorProductBackUp['vendor_sku'] = $vendorProductBackUp['backup_vendor_sku'];
                    $vendorProductBackUp['backup'] = 1;
                    unset($vendorProductBackUp['backup_vendor_sku']);
                    $product->vendors()->syncWithoutDetaching([
                        $vendors[1] => $vendorProductBackUp
                    ]);
                }

            }
        $bar->finish();
        $this->warn('inventory upload ends');
//        } catch (Exception $e) {
//            $this->warn($e->getMessage() . " error occured at row " . $count);
//            Log::info($e->getMessage() . " error occured at row " . $count);
//        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        QuotationItem::truncate();
        Quotation::truncate();
        Product::truncate();
        Service::truncate();
        Activity::truncate();
        Customer::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $products = Product::factory()->count(3)->create();
        $this->command->info('Created ' . $products->count() . ' products.');

        $services = Service::factory()->count(14)->create();
        $this->command->info('Created ' . $services->count() . ' services.');

        $activities = Activity::factory()->count(7)->create();
        $this->command->info('Created ' . $activities->count() . ' activities.');

        $customers = Customer::factory()->count(12)->create();
        $this->command->info('Created ' . $customers->count() . ' customers.');

        $productList = Product::all();

        foreach (range(1, 10) as $i) {
            $customer = $customers->random();
            $numItems = rand(2, 5);

            $quotation = Quotation::factory()->create([
                'customer_id' => $customer->id,
                'status' => $this->randomStatus($i),
            ]);

            $items = collect();
            $selectedProducts = $productList->random(min($numItems, $productList->count()));

            foreach ($selectedProducts as $product) {
                $volume = rand(1, 5);
                $price = $product->harga_default ?: rand(100000, 500000000);

                $items->push(new QuotationItem([
                    'quotation_id' => $quotation->id,
                    'product_id' => $product->id,
                    'nama_item' => $product->name,
                    'deskripsi' => 'Unit baru, spesifikasi sesuai standar. ' . ($product->description ? substr($product->description, 0, 100) . '...' : ''),
                    'volume' => $volume . ' ' . ($product->satuan ?? 'Unit'),
                    'harga_satuan' => $price,
                    'subtotal' => $volume * $price,
                ]));
            }

            $quotation->items()->saveMany($items->all());

            $total = $items->sum('subtotal');
            $quotation->update(['total' => $total]);
        }

        $quotationCount = Quotation::count();
        $this->command->info('Created ' . $quotationCount . ' quotations with items.');
    }

    private function randomStatus(int $index): string
    {
        return match (true) {
            $index <= 3 => 'draft',
            $index <= 6 => 'dikirim',
            $index <= 11 => 'deal',
            default => 'batal',
        };
    }
}

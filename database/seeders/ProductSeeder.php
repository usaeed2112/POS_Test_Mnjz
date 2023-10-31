<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array = [
            [
                'name' => 'Fjallraven - Foldsack No. 1 Backpack, Fits 15 Laptops',
                "price" => 109.95,
                "category" => "men's clothing",
                "image" => "https://fakestoreapi.com/img/81fPKd-2AYL._AC_SL1500_.jpg",
            ],
            [
                'name' => 'Mens Casual Premium Slim Fit T-Shirts ',
                "price" => 22.3,
                "category" => "men's clothing",
                "image" => "https://fakestoreapi.com/img/71-3HjGNDUL._AC_SY879._SX._UX._SY._UY_.jpg",
            ],
            [
                'name' => 'Solid Gold Petite Micropave ',
                "price" => 185,
                "category" => "jewelery",
                "image" => "https://fakestoreapi.com/img/61sbMiUnoGL._AC_UL640_QL65_ML3_.jpg",
            ],
            [
                'name' => 'White Gold Plated Princess"',
                "price" => 10,
                "category" => "jewelery",
                "image" => "https://fakestoreapi.com/img/71YAIFU48IL._AC_UL640_QL65_ML3_.jpg",
            ],
        ];

        foreach($array as $productData)
        {
            $product = new Product;
            $product->name = $productData['name'];
            $product->price = $productData['price'];
            $product->category = $productData['category'];
            $product->image = $productData['image'];
            $product->save();
        }
    }
}

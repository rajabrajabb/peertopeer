<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeedTypesAndCategory extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types=[
             [
                'name' => 'type1',
            ],
            [
                'name' => 'type2',
            ],
            [
                'name' => 'type3',
            ],
            [
                'name' => 'type4',
            ],
            [
                'name' => 'type5',
            ],
        ];

        $categories=[
             [
                'name' => 'category1',
            ],
            [
                'name' => 'category2',
            ],
            [
                'name' => 'category3',
            ],
            [
                'name' => 'category4',
            ],
            [
                'name' => 'category5',
            ],
        ];

        foreach($categories as $category){
            Category::create($category);
        }
        foreach($types as $type){
            Type::create($type);
        }
    }
}

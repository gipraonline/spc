<?php

namespace Database\Seeders;

use App\Models\CategoryMaster;
use Illuminate\Database\Seeder;

class CategoryMasterSeeder extends Seeder
{
    public function run(): void
    {
        // Parent category
        $organicFertilizers = CategoryMaster::updateOrCreate(
            [
                'c_category_code' => 'ORG-FERT',
            ],
            [
                'c_category_name' => 'Organic Fertilizers',
                'n_parent_category_id' => null,
                'c_status' => 'Y',
            ]
        );

        // Parent category
        $plantsProducts = CategoryMaster::updateOrCreate(
            [
                'c_category_code' => 'PLANTS',
            ],
            [
                'c_category_name' => 'Plants Products',
                'n_parent_category_id' => null,
                'c_status' => 'Y',
            ]
        );

        // Plant subcategories
        CategoryMaster::updateOrCreate(
            ['c_category_code' => 'PLANTS-FRUITS'],
            [
                'c_category_name' => 'Fruits',
                'n_parent_category_id' => $plantsProducts->n_category_id,
                'c_status' => 'Y',
            ]
        );

        CategoryMaster::updateOrCreate(
            ['c_category_code' => 'PLANTS-PLANTATION'],
            [
                'c_category_name' => 'Plantation',
                'n_parent_category_id' => $plantsProducts->n_category_id,
                'c_status' => 'Y',
            ]
        );

        CategoryMaster::updateOrCreate(
            ['c_category_code' => 'PLANTS-FLOWERING'],
            [
                'c_category_name' => 'Flowering',
                'n_parent_category_id' => $plantsProducts->n_category_id,
                'c_status' => 'Y',
            ]
        );

        CategoryMaster::updateOrCreate(
            ['c_category_code' => 'PLANTS-INDOOR'],
            [
                'c_category_name' => 'Indoor',
                'n_parent_category_id' => $plantsProducts->n_category_id,
                'c_status' => 'Y',
            ]
        );
    }
}
<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::query()->orderBy('id', 'ASC')->first();

        Blog::create([
            'name' => 'shahabtahrir',
            'title' => 'شهاب تحریر',
            'slug' => 'نامی فراتر از زمان',
            'description' => 'اگر می‌خواهید از شر برگه‌ها و پرونده‌ها و مجلات و فرم‌های متعددی که روزانه روی میز کارتان پخش می‌شوند',
            'created_by' => $user->id,
        ]);
    }
}

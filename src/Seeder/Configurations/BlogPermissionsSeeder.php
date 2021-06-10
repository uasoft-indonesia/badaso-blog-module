<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Uasoft\Badaso\Models\Permission;

class BlogPermissionsSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $keys = [
            'upload_file',
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key' => $key,
                'table_name' => null,
            ]);
        }

        Permission::generateFor('posts');

        Permission::generateFor('categories');

        Permission::generateFor('tags');

        Permission::generateFor('comments');
    }
}

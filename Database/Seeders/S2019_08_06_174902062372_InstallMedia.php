<?php

use Illuminate\Database\Eloquent\Model;
use Pingu\Core\Seeding\DisableForeignKeysTrait;
use Pingu\Core\Seeding\MigratableSeeder;
use Pingu\Media\Config\MediaSettings;
use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Entities\MediaType;
use Pingu\Media\Transformers\Resize;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;
use Pingu\Permissions\Entities\Permission;

class S2019_08_06_174902062372_InstallMedia extends MigratableSeeder
{
    use DisableForeignKeysTrait;

    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        Model::unguard();

        \Settings::repository('media')->create();

        MediaType::create([
            'machineName' => 'image',
            'name' => 'Image',
            'folder' => 'images',
            'deletable' => false,
            'extensions' => 'jpeg,jpg,png,gif'
        ]);

        ImageStyle::create([
            'name' => 'Icon',
            'machineName' => 'icon',
            'deletable' => false,
            'editable' => true,
            'description' => 'Icon 200x200'
        ]);

        $perm = Permission::create(['name' => 'view medias', 'section' => 'Media']);
        Permission::create(['name' => 'add medias', 'section' => 'Media']);
        Permission::create(['name' => 'edit medias', 'section' => 'Media']);
        Permission::create(['name' => 'delete medias', 'section' => 'Media']);
        $perm2 = Permission::create(['name' => 'view media types', 'section' => 'Media']);
        Permission::create(['name' => 'edit media types', 'section' => 'Media']);
        Permission::create(['name' => 'add media types', 'section' => 'Media']);
        Permission::create(['name' => 'delete media types', 'section' => 'Media']);
        Permission::create(['name' => 'add images styles', 'section' => 'Media']);
        Permission::create(['name' => 'edit images styles', 'section' => 'Media']);
        Permission::create(['name' => 'delete images styles', 'section' => 'Media']);
        $perm4 = Permission::create(['name' => 'view images styles', 'section' => 'Media']);

        $media = MenuItem::create([
            'name' => 'Media',
            'weight' => 5,
            'active' => 1,
            'deletable' => false,
            'url' => 'media.admin.media',
            'permission_id' => $perm->id
        ], 'admin-menu');

        MenuItem::create([
            'name' => 'Media types',
            'weight' => 0,
            'active' => 1,
            'deletable' => false,
            'url' => 'media.admin.mediaTypes',
            'permission_id' => $perm2->id
        ], 'admin-menu', $media);

        MenuItem::create([
            'name' => 'Images Styles',
            'weight' => 0,
            'active' => 1,
            'deletable' => false,
            'url' => 'media.admin.imagesStyles',
            'permission_id' => $perm4->id
        ], 'admin-menu', $media);
    }

    /**
     * Reverts the database seeder.
     */
    public function down(): void
    {
        // Remove your data
    }
}

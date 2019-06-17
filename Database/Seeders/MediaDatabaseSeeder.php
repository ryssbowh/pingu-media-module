<?php

namespace Pingu\Media\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Pingu\Forms\Support\Fields\NumberInput;
use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Entities\MediaType;
use Pingu\Media\Transformers\Resize;
use Pingu\Media\Types\Image;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;
use Pingu\Permissions\Entities\Permission;
use Pingu\Settings\Forms\Types\Integer;

class MediaDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $type = MediaType::where(['machineName' => 'image'])->first();

        if(!$type){

            \Settings::registerMany([
                'media.maxFileSize' => [
                    'Title' => 'Upload max file size',
                    'Section' => 'media',
                    'field' => NumberInput::class, 
                    'type' => Integer::class,
                    'unit' => 'Kb',
                    'validation' => 'required|integer|max:'.upload_max_filesize(),
                    'attributes' => ['required' => true, 'max' => upload_max_filesize()],
                    'weight' => 0
                ]
            ]);

            MediaType::create([
                'machineName' => 'image',
                'name' => 'Image',
                'folder' => 'images',
                'deletable' => false,
                'extensions' => ['jpeg', 'jpg', 'png', 'gif']
            ]);

            ImageStyle::create([
                'name' => 'Icon',
                'machineName' => 'icon',
                'deletable' => false,
                'editable' => false,
                'transformations' => [[
                    'class' => Resize::class,
                    'options' => [
                        'width' => 200,
                        'height' => 200
                    ]
                ]]
            ]);

            $perm3 = Permission::create(['name' => 'view media settings', 'section' => 'Media']);
            $perm3 = Permission::create(['name' => 'edit media settings', 'section' => 'Media']);
            $perm = Permission::create(['name' => 'view medias', 'section' => 'Media']);
            Permission::create(['name' => 'add medias', 'section' => 'Media']);
            Permission::create(['name' => 'edit medias', 'section' => 'Media']);
            Permission::create(['name' => 'delete medias', 'section' => 'Media']);
            $perm2 = Permission::create(['name' => 'view media types', 'section' => 'Media']);
            Permission::create(['name' => 'edit media types', 'section' => 'Media']);
            Permission::create(['name' => 'add media types', 'section' => 'Media']);
            Permission::create(['name' => 'delete media types', 'section' => 'Media']);

            $main = Menu::where(['machineName' => 'admin-menu'])->first();

            $media = MenuItem::create([
                'name' => 'Media',
                'weight' => 5,
                'active' => 1,
                'deletable' => false,
                'url' => 'media.admin.media',
                'permission_id' => $perm->id
            ], $main);

            MenuItem::create([
                'name' => 'Media types',
                'weight' => 0,
                'active' => 1,
                'deletable' => false,
                'url' => 'media.admin.mediaTypes',
                'permission_id' => $perm2->id
            ], $main, $media);

            $settings = MenuItem::findByName('admin-menu.settings');
            MenuItem::create([
                'name' => 'Media',
                'weight' => 3,
                'active' => 1,
                'deletable' => false,
                'url' => 'settings.admin.media',
                'permission_id' => $perm3->id
            ], $main, $settings);
        }
    }
}

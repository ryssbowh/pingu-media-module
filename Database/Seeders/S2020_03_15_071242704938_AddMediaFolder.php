<?php

use Pingu\Core\Seeding\DisableForeignKeysTrait;
use Pingu\Core\Seeding\MigratableSeeder;
use Pingu\Taxonomy\Entities\Taxonomy;

class S2020_03_15_071242704938_AddMediaFolder extends MigratableSeeder
{
    use DisableForeignKeysTrait;

    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $taxonomy = Taxonomy::create([
            'name' => 'Media Folders',
            'machineName' => 'media_folders',
            'description' => 'Folder structure for medias',
            'deletable' => false
        ]);
    }

    /**
     * Reverts the database seeder.
     */
    public function down(): void
    {
        // Remove your data
    }
}

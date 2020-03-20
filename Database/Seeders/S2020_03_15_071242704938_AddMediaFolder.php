<?php

use Pingu\Core\Seeding\DisableForeignKeysTrait;
use Pingu\Core\Seeding\MigratableSeeder;
use Pingu\Field\Entities\BundleField;
use Pingu\Media\Bundles\MediaBundle;
use Pingu\Taxonomy\Entities\FieldTaxonomy;
use Pingu\Taxonomy\Entities\Taxonomy;
use Pingu\Taxonomy\Entities\TaxonomyItem;

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
            'description' => 'Folder structure for medias'
        ]);

        $folderField = FieldTaxonomy::create(
            [
            'multiple' => false,
            'required' => false,
            'taxonomy_id' => $taxonomy->id
            ]
        );

        BundleField::create(
            [
            'name' => 'Folder',
            'machineName' => 'folder',
            'helper' => 'Folder for this media',
            'cardinality' => 1,
            'deletable' => false,
            'editable' => false
            ], (new MediaBundle), $folderField
        );
    }

    /**
     * Reverts the database seeder.
     */
    public function down(): void
    {
        // Remove your data
    }
}

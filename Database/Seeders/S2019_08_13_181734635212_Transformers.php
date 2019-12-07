<?php

use Pingu\Core\Seeding\DisableForeignKeysTrait;
use Pingu\Core\Seeding\MigratableSeeder;
use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Entities\MediaTransformer;
use Pingu\Media\Transformers\Resize;

class S2019_08_13_181734635212_Transformers extends MigratableSeeder
{
    use DisableForeignKeysTrait;

    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $tr = new MediaTransformer(
            [
            'class' => Resize::class,
            'options' => [
                'width' => 200,
                'height' => 200
            ]
            ]
        );
        $tr->image_style()->associate(ImageStyle::findByMachineName('icon'))->save();
    }

    /**
     * Reverts the database seeder.
     */
    public function down(): void
    {
        // Remove your data
    }
}

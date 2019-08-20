<?php

return [
    'name' => 'Media',
    'defaultDisk' => 'public',
    'maxFileSize' => upload_max_filesize(),
    'folder' => 'medias',
    /**
     * Image styles creation strategy
     *
     * eager : Will create all the styles for a media when it's created
     *         best if styles are not changed too often or if styles transformation are heavy.
     * lazy  : Will not create styles at media creation, but only when the style is requested.
     *         best if styles are changed often or if styles are light transformations
     */
    'stylesCreationStrategy' => 'lazy'
];
# Media

### Medias

Medias are files stored on a disk. The disk can be anything defined in config (local, private, s3 etc). Each media has type (image, document etc)

Losts of helpers available to get the url, disk, path, size, rename the media, download etc... in `Traits\MediaTrait`

### Media types

A media must have a type, the type defines what kind of actions can be performed on a media, in which folder it lives and which extensions it can have. the type images is created at installation and is not deletable.
You can add new media types with new extensions as long as the extensions are not defined in another type.

### Image styles

The medias of type images can have media styles attributed to it. They are basically tranformation on images that are done when the media is created.
Only one transformer for now : Scale, which scale a image to a width/height. The icon one is default.

### Forms

A Media field is available, a disk can be selected. By default the disk will be local. The field will upload the file to the disk and create a media with the right type. If the extension is not defined in any type, the validation will fail.

### Validation

3 new rules :
- file_extension : checks if an uploaded file has an extension that is defined in a media type
- unique_media_name : checks if a media name is unique for a media type
- unique_extensions : checks if a list of extension doesn't define an extension that is already defined by another type.

### Facade

The facade `Media` is a helper for a few things :
- guess a media type given an extension (getMediaTypeForExtension)
- uploads a file and creates a media for it (uploadFile).
- list all available extensions (getAvailableFileExtensions)
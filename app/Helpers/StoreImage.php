<?php


namespace App\Helpers;


use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class StoreImage
{
    private $title;
    private $path;
    private $image;
    private $old_image;
    private $width;
    private $height;

    public function __construct($path='', $image=null, $width=1600, $height=1066, $title='default', $old_image=null)
    {
        $this->path = $path;
        $this->image = $image;
        $this->old_image = $old_image;
        $this->title = $title;
        $this->width = $width;
        $this->height = $height;
    }

    public function createUniqueImageName()
    {
        $currentDate = Carbon::now()->toDateString();
        $uniqId = uniqid();
        $extension = $this->image->getClientOriginalExtension();
        $slug = str_slug($this->title);
        return "{$slug}-{$currentDate}-{$uniqId}.{$extension}";
    }

    public function deleteExistingImage($path, $old_image)
    {
        $old_image_path = "{$path}/{$old_image}";
        if (Storage::disk('public')->exists($old_image_path)) {
            Storage::disk('public')->delete($old_image_path);
        }
    }

    public function storeImage()
    {
        // Check category Dir exists otherwise create it
        if (!Storage::disk('public')->exists($this->path)) {
            Storage::disk('public')->makeDirectory($this->path);
        }

        //delete existing image
        if ($this->old_image)
            $this->deleteExistingImage($this->path, $this->old_image);

        // Resize original image
        $resized_image = Image::make($this->image)
            ->resize($this->width, $this->height)
            ->stream();

        // create an unique image name and store the image
        $image_name = $this->createUniqueImageName();
        Storage::disk('public')->put("{$this->path}/{$image_name}", $resized_image);

        return $image_name;
    }
}

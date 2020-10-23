<?php

namespace App\Manager;

use App\Entity\Image;
use App\Entity\Trick;

class Uploader
{
    /**
     * @param $image
     * @param $path
     * @param Trick $trick
     * @return Trick|void
     */
    public function upload($image, $path, Trick $trick)
    {
        if (null === $trick->getImage()) {
            return;
        }

        $imageName = $image->getClientOriginalName();
        $image->move(
            $path,
            $imageName
        );

        $trick->setImage($image);
        $trick->setImageName($image->getClientOriginalName());
        return $trick;
    }

    /**
     * @param $image
     * @param $path
     * @param Trick $trick
     * @return Trick|void
     */
    public function uploadMultiple($image, $path, Trick $trick)
    {
        if (null === $image->getImage()) {
            return;
        }

        $imageName = $image->getImage()->getClientOriginalName();
        $image->getImage()->move(
            $path,
            $imageName
        );

        $image->setImage($image->getImage());
        $image->setName($image->getImage()->getClientOriginalName());
        $image->setTrick($trick);

        return $trick;
    }
}
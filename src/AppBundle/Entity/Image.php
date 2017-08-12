<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class Image.
 *
 * @ORM\Entity
 * @ORM\Table(name="images")
 * @Vich\Uploadable()
 */
class Image
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     *
     * @var
     */
    private $imageType;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    private $description;

    /**
     * It only stores the name of the image associated with the product.
     *
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $image;

    /**
     * This unmapped property stores the binary contents of the image file
     * associated with the product.
     *
     * @Vich\UploadableField(mapping="images", fileNameProperty="image")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $imageType
     * @return Image
     */
    public function setImageType(string $imageType)
    {
        $this->imageType = $imageType;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageType(): string
    {
        return $this->imageType ?? '';
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description ?? '';
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param File $imageFile
     * @return $this
     */
    public function setImageFile(File $imageFile)
    {
        $this->imageFile = $imageFile;

        return $this;
    }

    /**
     * @return File
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getUrl()
    {
        return '/uploads/images/' . $this->getImage();
    }

    public function __toString()
    {
        return sprintf('%s - Filename: %s - Description: %s', $this->getImageType(), $this->getImage(), $this->getDescription());
    }
}

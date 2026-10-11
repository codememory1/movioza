<?php

declare(strict_types = 1);

namespace Movioza\Assets\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\Assets\Domain\Entity\Image;
use Movioza\Assets\Domain\Repository\ImageRepositoryInterface;

class DoctrineImageRepository extends ServiceEntityRepository implements ImageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }

    /**
     * @throws Exception
     */
    public function update(Image $image): bool
    {
        $affectedRows = $this->getEntityManager()->getConnection()->update('images', [
            'file_id' => $image->getFileId()->getValue(),
            'width' => $image->getDimensions()->getWidth(),
            'height' => $image->getDimensions()->getHeight(),
            'blur_hash' => $image->getBlurHash()->getValue(),
        ], [
            'id' => $image->getId()->getValue(),
        ]);

        return $affectedRows > 0;
    }

    public function save(Image $image): void
    {
        $this->getEntityManager()->persist($image);
    }
}

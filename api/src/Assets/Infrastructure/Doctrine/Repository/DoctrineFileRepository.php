<?php

declare(strict_types = 1);

namespace Movioza\Assets\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\Assets\Domain\Entity\File;
use Movioza\Assets\Domain\Repository\FileRepositoryInterface;

class DoctrineFileRepository extends ServiceEntityRepository implements FileRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, File::class);
    }

    /**
     * @throws Exception
     */
    public function update(File $file): bool
    {
        $affectedRows = $this->getEntityManager()->getConnection()->update('files', [
            'storage_type' => $file->getStorageType()->value,
            'key' => $file->getKey()->getValue(),
            'extension' => $file->getExtension()->getValue(),
            'size' => $file->getSize()->getValue(),
        ], [
            'id' => $file->getId()->getValue(),
        ]);

        return $affectedRows > 0;
    }

    public function save(File $file): void
    {
        $this->getEntityManager()->persist($file);
    }
}

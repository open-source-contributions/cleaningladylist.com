<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ProjectCheckbox;
use App\Exception\ShouldNotHappenException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

final class ProjectCheckboxRepository
{
    private EntityManagerInterface $entityManager;

    /**
     * @var ObjectRepository<ProjectCheckbox>
     */
    private ObjectRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $this->entityManager->getRepository(ProjectCheckbox::class);
        $this->entityManager = $entityManager;
    }

    public function get(int $projectCheckboxId): ProjectCheckbox
    {
        $projectOrNull = $this->repository->find($projectCheckboxId);
        if ($projectOrNull === null) {
            throw new ShouldNotHappenException();
        }

        return $projectOrNull;
    }

    public function persist(ProjectCheckbox $projectCheckbox): void
    {
        $this->entityManager->persist($projectCheckbox);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function save(?ProjectCheckbox $projectCheckbox): void
    {
        $this->entityManager->persist((object) $projectCheckbox);
        $this->entityManager->flush();
    }
}

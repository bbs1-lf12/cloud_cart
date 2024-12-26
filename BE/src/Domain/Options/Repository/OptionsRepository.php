<?php

declare(strict_types=1);

namespace App\Domain\Options\Repository;

use App\Domain\Options\Entity\Options;
use Doctrine\ORM\EntityRepository;

class OptionsRepository extends EntityRepository
{
    public function getOptions(): Options
    {
        $options = $this->createQueryBuilder('o')
            ->orderBy(
                'o.id',
                'DESC',
            )
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $options ?? $this->defaultOptions();
    }

    private function defaultOptions(): Options
    {
        $options = new Options();
        $options->setAppName('My App');
        return $options;
    }
}

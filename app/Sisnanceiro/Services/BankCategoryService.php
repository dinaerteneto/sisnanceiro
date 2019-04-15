<?php

namespace Sisnanceiro\Services;

use Sisnanceiro\Repositories\BankCategoryRepository;

class BankCategoryService extends Service
{
    protected $repository;

    public function __construct(BankCategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * return all categories based on $mainParentCategoryId
     * @param int $mainParentCategoryId
     * @return array
     */
    public function all($mainParentCategoryId) {
        return $this->repository->getCategories($mainParentCategoryId);
    }

}
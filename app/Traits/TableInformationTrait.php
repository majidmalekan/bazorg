<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;

trait TableInformationTrait
{

    public function getTableName(): string
    {
        return $this->model->getTable();
    }

    /**
     * @return int
     */
    public function getLastPage(): int
    {
        return $this->model
            ->query()
            ->paginate()
            ->lastPage();
    }
}

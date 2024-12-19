<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;

trait TableInformationTrait
{
    /**
     * @return array
     */
    protected function getColumnsOfTable(): array
    {
        $table = $this->getTableName();
        return Schema::getColumnListing($table);
    }

    protected function getTableName(): string
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

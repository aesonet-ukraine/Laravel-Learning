<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class Repository implements Contract\RepositoryContract
{
    /**
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * @param  array<string, string>  $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @param  Builder<Model>  $query
     */
    protected function applySorting(Builder $query): void
    {
        if (! empty($this->data['sort'])) {
            foreach ($this->data['sort'] as $sort) {
                $query->orderBy($sort['key'], $sort['direction']);
            }
        }

    }

    /**
     * @param  Builder<Model>  $query
     */
    protected function applyFilters(Builder $query): void {}

    /**
     * @param  Builder<Model>  $query
     */
    protected function trashed(Builder $query): void
    {
        if (! empty($this->data['trashed'])) {
            $query->withTrashed();
        }

    }

    /**
     * @param array{
     *     key: string,
     *     operator?: string,
     *     value?: mixed
     * } $filters
     * @return array{0:string, 1:string, 2:mixed|null}
     */
    protected function destructFilters(array $filters): array
    {
        return [
            $filters['key'],
            $filters['operator'] ?? '=',
            $filters['value'] ?? null,
        ];
    }
}

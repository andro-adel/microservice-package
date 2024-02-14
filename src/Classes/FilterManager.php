<?php

namespace DD\MicroserviceCore\Classes;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

class FilterManager extends FilterBuilder
{
    /**
     * @param array $filtersData
     * @return void
     */
    public function setFiltersData(array $filtersData): void
    {
        $this->filtersData = $filtersData;
    }

    /**
     * @param Relation|Builder|Collection|array $data
     * @return void
     */
    public function applyFilters(Relation|Builder|Collection|array &$data): void
    {
        $isArray = is_array($data);
        $isCollection = !($data instanceof Relation || $data instanceof Builder);
        $data = $isArray ? collect($data) : $data;

        $this->applyingAllFilters($data, $isCollection);
        if ($isCollection || $isArray) {
            $data = array_values($data->toArray());
            if ($isCollection) {
                $data = collect($data);
            }
        }
    }

    /**
     * @param string $column
     * @param string $operator
     * @param string|null $valueKey
     * @return void
     */
    public function addWhereFilter(string $column, string $operator = '=', string|null $valueKey = null): void
    {
        $filterKey = $valueKey ?? $column;
        if ($this->canNotAddFilter($filterKey)) {
            return;
        }
        $value = $this->filtersData[$filterKey];

        if (strtolower($operator) === 'like') {
            $value = '%' . $value . '%';
        }
        $this->buildFilter('where', [$column, $operator, $value]);
    }

    /**
     * @param array $columns
     * @param mixed $operator
     * @return void
     */
    public function addMultipleWhereFilter(array $columns, string $operator = '='): void
    {
        foreach ($columns as $index => $value) {
            if (gettype($index) === 'integer') {
                $column = $value;
                $valueKey = null;
            } else {
                $column = $index;
                $valueKey = $value;
            }
            $filterKey = $valueKey ?? $column;
            if ($this->canNotAddFilter($filterKey)) {
                continue;
            }
            $this->addWhereFilter($column, $operator, $valueKey);
        }
    }

    /**
     * @param string $column
     * @param string|null $valuesKey
     * @return void
     */
    public function addWhereInFilter(string $column, string|null $valuesKey = null): void
    {
        $filterKey = $valuesKey ?? $column;
        if ($this->canNotAddFilter($filterKey)) {
            return;
        }
        $this->buildFilter('whereIn', [$column, $this->filtersData[$filterKey]]);
    }

    /**
     * @param array $columns
     * @return void
     */
    public function addMultipleWhereInFilter(array $columns): void
    {
        foreach ($columns as $index => $value) {
            if (gettype($index) === 'integer') {
                $column = $value;
                $valuesKey = null;
            } else {
                $column = $index;
                $valuesKey = $value;
            }
            $filterKey = $valuesKey ?? $column;
            if ($this->canNotAddFilter($filterKey)) {
                continue;
            }
            $this->addWhereInFilter($column, $valuesKey);
        }
    }

    /**
     * @param string $column
     * @param string|null $valuesKeys
     * @return void
     */
    public function addWhereNotInFilter(string $column, string|null $valuesKeys = null): void
    {
        $filterKey = $valuesKeys ?? $column;
        if ($this->canNotAddFilter($filterKey)) {
            return;
        }
        $this->buildFilter('whereNotIn', [$column, $this->filtersData[$filterKey]]);
    }

    /**
     * @param array $columns
     * @return void
     */
    public function addMultipleWhereNotInFilter(array $columns): void
    {
        foreach ($columns as $index => $value) {
            if (gettype($index) === 'integer') {
                $column = $value;
                $valuesKey = null;
            } else {
                $column = $index;
                $valuesKey = $value;
            }
            $filterKey = $valuesKey ?? $column;
            if ($this->canNotAddFilter($filterKey)) {
                continue;
            }
            $this->addWhereNotInFilter($column, $valuesKey);
        }
    }

    /**
     * @param string $relationName
     * @param Closure $callback
     * @param string|null $valueKey
     * @return void
     */
    public function addWhereHasFilter(string $relationName, Closure $callback, string|null $valueKey = null): void
    {
        $filterKey = $valueKey ?? $relationName;
        if ($this->canNotAddFilter($filterKey)) {
            return;
        }
        $this->buildFilter('whereHas', [$relationName, $callback]);
    }

    /**
     * @param array $relations
     * @return void
     */
    public function addMultipleWhereHasFilter(array $relations): void
    {
        foreach ($relations as $relation => $relationData) {
            $filterKey = $relationData[1] ?? $relation;
            if ($this->canNotAddFilter($filterKey)) {
                continue;
            }
            $this->addWhereHasFilter($relation, $relationData[0], $relationData[1] ?? null);
        }
    }

    /**
     * @param string $relationName
     * @param string|array $types
     * @param Closure|null $callback
     * @param string|null $valueKey
     * @return void
     */
    public function addWhereHasMorphFilter(
        string $relationName,
        string|array $types = '*',
        Closure|null $callback = null,
        string|null $valueKey = null
    ): void {
        $filterKey = $valueKey ?? $relationName;
        if ($this->canNotAddFilter($filterKey)) {
            return;
        }
        $this->buildFilter('whereHasMorph', [$relationName, $types, $callback]);
    }

    /**
     * @param string $relationName
     * @param string $operator
     * @param string|null $valueKey
     * @return void
     */
    public function addHasFilter(string $relationName, string $operator = '>=', string|null $valueKey = null): void
    {
        $filterKey = $valueKey ?? $relationName;
        if ($this->canNotAddFilter($filterKey)) {
            return;
        }
        $this->buildFilter('has', [$relationName, $operator, $this->filtersData[$filterKey]]);
    }

    /**
     * @param array $relations
     * @param string $operator
     * @return void
     */
    public function addMultipleHasFilter(array $relations, string $operator = '>='): void
    {
        foreach ($relations as $index => $value) {
            if (gettype($index) === 'integer') {
                $relation = $value;
                $valueKey = null;
            } else {
                $relation = $index;
                $valueKey = $value;
            }
            $filterKey = $valueKey ?? $relation;
            if ($this->canNotAddFilter($filterKey)) {
                continue;
            }
            $this->addHasFilter($relation, $operator, $valueKey);
        }
    }

    /**
     * @param string $column
     * @param array|string|null $valuesKeys
     * @param Closure|null $callback
     * @return void
     */
    public function addWhereBetweenFilter(
        string $column,
        array|string|null $valuesKeys = null,
        Closure|null $callback = null
    ): void {
        if (!$valuesKeys && !$callback) {
            return;
        }
        $this->buildFilter('whereBetween', $this->buildWhereBetweenFilter($column, $valuesKeys, $callback));
    }

    /**
     * @param array $data
     * @return void
     */
    public function addMultipleWhereBetweenFilter(array $data): void
    {
        $this->buildMultipleFilter('whereBetween', $this->buildMultipleWhereBetweenFilter($data));
    }

    /**
     * @param string $column
     * @param array|string|null $valuesKeys
     * @param Closure|null $callback
     * @return void
     */
    public function addWhereNotBetweenFilter(
        string $column,
        array|string|null $valuesKeys = null,
        Closure|null $callback = null
    ): void {
        if (!$valuesKeys && !$callback) {
            return;
        }
        $this->buildFilter('whereNotBetween', $this->buildWhereBetweenFilter($column, $valuesKeys, $callback));
    }

    /**
     * @param array $data
     * @return void
     */
    public function addMultipleWhereNotBetweenFilter(array $data): void
    {
        $this->buildMultipleFilter('whereNotBetween', $this->buildMultipleWhereBetweenFilter($data));
    }

    /**
     * @param Closure $callback
     * @param string $filterKey
     * @return void
     */
    public function addWhereFunctionFilter(Closure $callback, string $filterKey): void
    {
        if ($this->canNotAddFilter($filterKey)) {
            return;
        }
        $this->buildFilter('whereFunction', $callback);
    }
}

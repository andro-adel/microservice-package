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
     * @return Relation|Builder|Collection|array
     */
    public function applyFilters(Relation|Builder|Collection|array $data): Relation|Builder|Collection|array
    {
        $isArray = is_array($data);
        $isCollection = !($data instanceof Relation || $data instanceof Builder);
        $collection = $isArray ? collect($data) : $data;

        $this->applyingAllFilters($collection, $isCollection);
        if ($isCollection || $isArray) {
            $collection = array_values($collection->toArray());
            if ($isCollection) {
                $collection = collect($collection);
            }
        }
        return $collection;
    }

    /**
     * @param string $column
     * @param string $operator
     * @param string|null $valueKey
     * @return void
     */
    public function addWhereFilter(string $column, string $operator = '=', string|null $valueKey = null): void
    {
        $this->buildFilter('where', [$column, $operator,
            ...($this->filtersData[$valueKey ?? $column]? [$this->filtersData[$valueKey ?? $column]] : [])]);
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
        $this->buildFilter('whereIn', [$column, $this->filtersData[$valuesKey ?? $column]]);
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
        $this->buildFilter('whereNotIn', [$column, $this->filtersData[$valuesKeys ?? $column]]);
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
            $this->addWhereNotInFilter($column, $valuesKey);
        }
    }

    /**
     * @param string $relationName
     * @param Closure $callback
     * @return void
     */
    public function addWhereHasFilter(string $relationName, Closure $callback): void
    {
        $this->buildFilter('whereHas', [$relationName, $callback]);
    }

    public function addMultipleWhereHasFilter(array $relations): void
    {
        foreach ($relations as $relation => $callback) {
            $this->addWhereHasFilter($relation, $callback);
        }
    }

    /**
     * @param string $relationName
     * @param string|array $types
     * @param Closure|null $callback
     * @return void
     */
    public function addWhereHasMorphFilter(string $relationName, string|array $types = '*',
                                           Closure|null $callback = null): void
    {
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
        $this->buildFilter('has', [$relationName, $operator, $this->filtersData[$valueKey ?? $relationName]]);
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
            $this->addHasFilter($relation, $operator, $valueKey);
        }
    }

    /**
     * @param string $column
     * @param array|string|null $valuesKeys
     * @param Closure|null $callback
     * @return void
     */
    public function addWhereBetweenFilter(string $column, array|string|null $valuesKeys = null,
                                          Closure|null $callback = null): void
    {
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
    public function addWhereNotBetweenFilter(string $column, array|string|null $valuesKeys = null,
                                             Closure|null $callback = null): void
    {
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
     * @return void
     */
    public function addWhereFunctionFilter(Closure $callback): void
    {
        $this->buildFilter('whereFunction', $callback);
    }

    /**
     * @param Closure $callback
     * @return void
     */
    public function addCollectionFunctionFilter(Closure $callback): void
    {
        $this->buildFilter('whereFunction', $callback);
    }
}

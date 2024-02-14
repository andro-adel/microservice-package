<?php

namespace DD\MicroserviceCore\Classes;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

class FilterBuilder
{
    protected array $filtersObject = [];

    /**
     * @param array|null $filtersData
     */
    public function __construct(protected array|null $filtersData = null)
    {
        if (!$this->filtersData) {
            $filtersRequest = request()->has('filter') ? request()->get('filter') : [];
            if (gettype($filtersRequest) === 'string') {
                $this->filtersData = json_decode($filtersRequest, true);
            } else {
                $this->filtersData = $filtersRequest;
            }
        }
    }

    /**
     * @param Relation|Builder|Collection $collection
     * @param bool $isCollection
     * @return void
     */
    protected function applyingAllFilters(Relation|Builder|Collection &$collection, bool $isCollection): void
    {
        foreach ($this->filtersObject as $filterType => $filters) {
            switch ($filterType) {
                case 'where':
                    $this->applyingWhere($filters, $collection, $isCollection);
                    break;
                case 'whereFunction':
                    $this->applyingWhereFunction($filters, $collection, $isCollection);
                    break;
                default:
                    $this->applyingDefaultCase($filterType, $filters, $collection, $isCollection);
                    break;
            }
        }
    }

    /**
     * @param array $filters
     * @param Relation|Builder|Collection $collection
     * @param bool $isCollection
     * @return void
     */
    protected function applyingWhere(array $filters, Relation|Builder|Collection &$collection, bool $isCollection): void
    {
        if ($isCollection) {
            foreach ($filters as $filter) {
                $collection = $collection->where(...$filter);
            }
        } else {
            $collection = $collection->where($filters);
        }
    }

    /**
     * @param array $filters
     * @param Relation|Builder|Collection $collection
     * @param bool $isCollection
     * @return void
     */
    protected function applyingWhereFunction(array $filters, Relation|Builder|Collection &$collection,
                                             bool $isCollection): void
    {
        foreach ($filters as $filter)
        {
            if ($isCollection) {
                $collection = $collection->filter($filter);
            } else {
                $collection = $collection->where($filter);
            }
        }
    }

    /**
     * @param string $filterType
     * @param array $filters
     * @param Relation|Builder|Collection $collection
     * @param bool $isCollection
     * @return void
     */
    protected function applyingDefaultCase(string $filterType, array $filters, Relation|Builder|Collection &$collection,
                                         bool $isCollection): void
    {
        if ($isCollection && in_array($filterType, ['whereHas', 'has', 'whereHasMorph'])) {
            return;
        }
        foreach ($filters as $filter) {
            $collection = $collection->$filterType(...$filter);
        }
    }

    /**
     * @param string $key
     * @return bool
     */
    protected function canNotAddFilter(string $key): bool
    {
        return !isset($this->filtersData[$key]);
    }

    /**
     * @param string $type
     * @param array|Closure $filterArray
     * @return void
     */
    protected function buildFilter(string $type, array|Closure $filterArray): void
    {
        $this->filtersObject[$type][] = $filterArray;
    }

    /**
     * @param string $type
     * @param array $filtersArray
     * @return void
     */
    protected function buildMultipleFilter(string $type, array $filtersArray): void
    {
        $this->filtersObject[$type] = [...$this->filtersObject[$type], ...$filtersArray];
    }

    /**
     * @param string $column
     * @param array|string|null $valuesKeys
     * @param Closure|null $callback
     * @return array|null
     */
    protected function buildWhereBetweenFilter(string $column, array|string|null $valuesKeys = null,
                                             Closure|null $callback = null): array|null
    {
        if ($valuesKeys) {
            $values = [
                $this->filtersData[$valuesKeys[0]],
                $this->filtersData[$valuesKeys[1]]
            ];
        } elseif ($callback) {
            $values = $callback($this->filtersData);
        } else {
            return null;
        }
        return [$column, $values];
    }

    /**
     * @param array $data
     * @return array
     */
    protected function buildMultipleWhereBetweenFilter(array $data): array
    {
        $filtersArray = [];
        foreach ($data as $column => $value) {
            if (gettype($value) === 'string' || is_array($value)) {
                $valuesKey = $value;
                $callback = null;
            } else {
                $valuesKey = null;
                $callback = $value;
            }
            $filtersArray[] = $this->buildWhereBetweenFilter($column, $valuesKey, $callback);
        }
        return $filtersArray;
    }
}

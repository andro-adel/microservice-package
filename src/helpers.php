<?php

use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Summary of paginateCollection
 *
 * @param  mixed  $data
 * @param  mixed  $pageSize
 * @param  mixed  $pageNumber
 * @return Illuminate\Pagination\LengthAwarePaginator
 */
function paginateCollection($data, $pageSize = null, $pageNumber = null)
{
    $paginate = (bool) $pageSize || $pageNumber;

    if ($paginate) {
        $pageSize = abs($pageSize ?? 5);
        $pageNumber = abs($pageNumber ?? request()->get('page') ?? 1);
    }

    if ($data instanceof Relation || $data instanceof Builder) {
        if ($paginate) {
            $data = $data->paginate($pageSize, ['*'], 'page', $pageNumber);
        } else {
            $data = $data->get();
        }
    } else {
        $collection = is_array($data) ? collect($data) : $data;
        if ($paginate) {
            $currentPageItems = $collection->slice(($pageNumber - 1) * $pageSize, $pageSize)->values();
            $data = new LengthAwarePaginator($currentPageItems, count($collection), $pageSize, $pageNumber, [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
            ]);
        } else {
            $data = $collection;
        }
    }
    return $data;
}

/**
 * Summary of filterCollection
 * @param mixed $data
 * @param array $filters
 * @return mixed
 */
function filterCollection($data, array $filtersObjects)
{
    $isCollection = true;
    if ($data instanceof Relation || $data instanceof Builder) {
        $isCollection = false;
    }
    $collection = is_array($data) ? collect($data) : $data;

    foreach ($filtersObjects as $filterType => $filters) {
        switch ($filterType) {
            case 'whereIn':
            case 'whereNotIn':
            case 'whereBetween':
            case 'whereNotBetween':
            case 'whereHas':
                if ($isCollection && in_array($filterType, ['whereHas'])) {
                    break;
                }
                foreach ($filters as $filter) {
                    $collection = $collection->$filterType($filter[0], $filter[1]);
                }
                break;
            case 'whereHasMorph':
                if ($isCollection) {
                    break;
                }
                foreach ($filters as $filter) {
                    $collection = $collection->$filterType($filter[0], $filter[1] ?? '*', $filter[2] ?? null);
                }
                break;
            case 'has':
                if ($isCollection) {
                    break;
                }
                foreach ($filters as $filter) {
                    $collection = $collection->$filterType($filter[0], $filter[1] ?? '>=', $filter[2] ?? 1);
                }
                break;
            case 'where':
                if ($isCollection) {
                    foreach ($filters as $filter) {
                        $collection = $collection->$filterType(...$filter);
                    }
                    break;
                }
                $collection = $collection->$filterType($filters);
                break;
            default:
                break;
        }
    }
    return $isCollection ? array_values($collection->toArray()) : $collection;
}

/**
 * Summary of searchCollection
 * @param mixed $query
 * @param mixed $model
 * @param array $fields
 * @return mixed
 */
function searchCollection($query, $data, array $fields)
{
    $isCollection = true;
    if ($data instanceof Relation || $data instanceof Builder) {
        $isCollection = false;
    }
    $collection = is_array($data) ? collect($data) : $data;
    $searchTerms = explode(' ', $query);

    if (!$isCollection) {
        foreach ($searchTerms as $term) {
            $collection = $collection->orWhere(function ($q) use ($fields, $term) {
                foreach ($fields as $field) {
                    $q->orWhere($field, 'LIKE', '%' . $term . '%');
                }
            });
        }
        return $collection;
    }

    $collection = $collection->filter(function ($q) use ($fields, $searchTerms) {
        foreach ($searchTerms as $term) {
            foreach ($fields as $field) {
                if (strpos(strtolower($q[$field]), strtolower($term)) !== false) {
                    return true;
                }
            }
            return false;
        }
    });
    return array_values($collection->toArray());
}

/**
 * Summary of exportCollection
 * @param mixed $data
 * @param mixed $fileName
 * @param mixed $writerType
 * @param mixed $headings
 * @return mixed
 */
function exportCollection($data, $fileName = null, $writerType = null, $headings = true)
{
    return collect($data)->downloadExcel(Carbon::now()->timestamp . $fileName . '.xlsx', $writerType, $headings);
}


function importCollection($file, array $requiredHeadings)
{
    $data = Excel::toArray(null, $file)[0];

    $heatHeadings = $data[0];

    if ((bool) array_diff($requiredHeadings, $heatHeadings)) {
        return ["success" => false, "data" => null, "reason" => "Invalid required Headings"];
    }

    unset($data[0]);

    $rows = array_values($data);

    $allCollection = [];

    $collection = [];

    foreach ($rows as $row) {
        foreach ($row as $index => $value) {
            if (in_array($heatHeadings[$index], $requiredHeadings) && ($value === '' || $value === null)) {
                return ["success" => false, "data" => null, "reason" => "Invalid required Headings"];
            }
            $collection[$heatHeadings[$index]] = $value;
        }
        $allCollection[] = $collection;
    }

    return ["success" => true, "data" => $allCollection, "reason" => null];
}

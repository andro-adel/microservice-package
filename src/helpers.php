<?php

use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Response;
use JetBrains\PhpStorm\ArrayShape;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


if (!function_exists('paginateCollection')) {
    /**
     * Summary of paginateCollection
     *
     * @param Relation|Builder|Collection|array $data
     * @param string|int|null $pageSize
     * @param string|int|null $pageNumber
     * @return Collection|LengthAwarePaginator
     */
    function paginateCollection(Relation|Builder|Collection|array $data, string|int|null $pageSize = null,
                                string|int|null                   $pageNumber = null): Collection|LengthAwarePaginator
    {
        $paginate = (bool)$pageSize || $pageNumber;

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
}


if (!function_exists('filterCollection')) {
    /**
     * @param Relation|Builder|Collection|array $data
     * @param array $filtersObjects
     * @return array|Relation|Builder
     */
    function filterCollection(Relation|Builder|Collection|array $data, array $filtersObjects): array|Relation|Builder
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
}

if (!function_exists('searchCollection')) {
    /**
     * @param string $query
     * @param Relation|Builder|Collection|array $data
     * @param array $fields
     * @return array|Relation|Builder
     */
    function searchCollection(string $query, Relation|Builder|Collection|array $data, array $fields)
    : array|Relation|Builder
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
                    if (str_contains(strtolower($q[$field]), strtolower($term))) {
                        return true;
                    }
                }
                return false;
            }
        });
        return array_values($collection->toArray());
    }
}

if (!function_exists('exportCollection')) {
    /**
     * @param array|Collection $data
     * @param string|null $fileName
     * @param string|null $writerType
     * @param bool $headings
     * @return void
     */
    function exportCollection(array|Collection $data, string|null $fileName = null, string $writerType = null,
                              bool $headings = true): void
    {
        $collection = is_array($data) ? collect($data) : $data;
        $collection->downloadExcel(Carbon::now()->timestamp . $fileName . '.xlsx', $writerType, $headings);
    }
}

if (!function_exists('importCollection')) {
    /**
     * @param string|UploadedFile $file
     * @param array $requiredHeadings
     * @return array
     */
    #[ArrayShape(['success' => "bool", 'data' => "array", 'reason' => "string"])]
    function importCollection(string|UploadedFile $file, array $requiredHeadings = []): array
    {
        $data = Excel::toArray((object)null, $file)[0];

        $heatHeadings = $data[0];

        if ((bool)array_diff($requiredHeadings, $heatHeadings)) {
            return ["success" => false, "data" => [], "reason" => "Invalid required Headings"];
        }

        unset($data[0]);

        $rows = array_values($data);

        $allCollection = [];

        $collection = [];

        foreach ($rows as $row) {
            foreach ($row as $index => $value) {
                if (in_array($heatHeadings[$index], $requiredHeadings) && ($value === '' || $value === null)) {
                    return ["success" => false, "data" => [], "reason" => "Invalid required Headings"];
                }
                $collection[$heatHeadings[$index]] = $value;
            }
            $allCollection[] = $collection;
        }

        return ["success" => true, "data" => $allCollection, "reason" => ''];
    }
}

if (!function_exists('exportCollectionPDF')) {
    /**
     * @param $data
     * @param string $templateView
     * @param string|null $fileName
     * @return BinaryFileResponse
     */
    function exportCollectionPDF($data, string $templateView, string|null $fileName = null): BinaryFileResponse
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml(view($templateView, compact($data))->render());
        $dompdf->render();
        $dompdf->stream($fileName ?? 'filename.pdf');
        $output = $dompdf->output();
        $filePath = public_path($fileName);
        file_put_contents($filePath, $output);
        return Response::download($filePath);

        // $pdf = Dompdf::loadHtml(view($template, compact($data)))->setPaper('a4');
        // return $dompdf->download($fileName ?? 'filename.pdf');
    }
}

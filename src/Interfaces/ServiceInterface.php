<?php

namespace DD\MicroserviceCore\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

interface ServiceInterface
{
    /**
     * @param Relation|Builder|Collection|array $data
     * @param array|null $filtersData
     * @return void
     */
    public function applyFilters(Relation|Builder|Collection|array &$data, array|null $filtersData = null): void;
}

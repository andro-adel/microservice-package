<?php

namespace DD\MicroserviceCore\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

interface ServiceInterface
{
    public function applyFilters(Relation|Builder|Collection|array &$data, array|null $filtersData = null): void;
}

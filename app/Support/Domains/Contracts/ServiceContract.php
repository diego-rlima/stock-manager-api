<?php

namespace App\Support\Domains\Contracts;

use App\Support\Domains\Model;

interface ServiceContract
{
    /**
     * Get the model.
     *
     * @return \App\Support\Domains\Model
     */
    public function getModel(): Model;
}

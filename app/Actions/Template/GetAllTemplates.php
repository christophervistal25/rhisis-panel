<?php

namespace App\Actions\Template;

use App\Models\Template;
use Illuminate\Database\Eloquent\Collection;

class GetAllTemplates
{
    public function get(): Collection
    {
        return Template::get();
    }
}

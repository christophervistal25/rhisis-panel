<?php

namespace App\Actions\Template;

use App\Models\Template;
use Illuminate\Database\Eloquent\Collection;

class GetAllTemplatesByDisplayOrder
{
    public function get(): Collection
    {
        return Template::orderBy('display_order', 'ASC')->get();
    }
}

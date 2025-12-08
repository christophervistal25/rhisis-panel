<?php

namespace App\Actions\Template;

use App\Models\Template;

class GetAllUpcomingTemplates
{
    public function get()
    {
        return Template::where('release_date', '>', now())->get();
    }
}

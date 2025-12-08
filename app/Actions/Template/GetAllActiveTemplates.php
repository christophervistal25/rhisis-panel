<?php

namespace App\Actions\Template;

use App\Models\Template;
use App\Enums\TemplateStatus;

class GetAllActiveTemplates
{
    public function get()
    {
        return Template::where('is_active', TemplateStatus::Active)->get();
    }
}

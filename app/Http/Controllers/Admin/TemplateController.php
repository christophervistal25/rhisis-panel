<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Template\GetAllTemplates;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index(GetAllTemplates $action)
    {
        $templates = $action->get();
        return inertia('admin/templates/Index', [
            'templates' => $templates,
        ]);
    }
}

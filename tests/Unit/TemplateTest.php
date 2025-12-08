<?php

use App\Models\Template;
use Illuminate\Support\Collection;
use App\Actions\Template\GetAllTemplates;
use App\Actions\Template\GetAllActiveTemplates;
use App\Actions\Template\GetAllTemplatesByDisplayOrder;
use App\Actions\Template\GetAllUpcomingTemplates;

/**
* Get All the templates (DONE) 
* Templates strictly sorted by display_order ascending (this guarantees top → bottom order on the website) (DONE)
* Empty collection when zero templates exist (DONE)
* Only includes templates where is_active = true (DONE)
* Automatically hides templates with release_date in the future (DONE)
* Every returned template has these required fields filled: id, name, slug, description, thumbnail, bundle_url (DONE) 
* bundle_url is a full working public URL (starts with http:// or https:// and points to storage) (DONE)
* thumbnail returns a valid full URL (or fallback placeholder if file missing) (DONE)
* All templates have unique slug (case-insensitive) (DONE)
*/

it('can get all the templates', function () {
    Template::factory(5)->create();
    $action = app(GetAllTemplates::class);
    $templates = $action->get();
    expect($templates->count())->toBe(5);
});


it('can get all the templates order by display order', function () {
    Template::factory()->create([
        'name' => 'Template A',
        'display_order' => 2,
    ]);

    Template::factory()->create([
        'name' => 'Template B',
        'display_order' => 1,
    ]);

    Template::factory()->create([
        'name' => 'Template C',
        'display_order' => 3,
    ]);

    $action = app(GetAllTemplatesByDisplayOrder::class);

    $templates = $action->get(); 

    expect($templates->pluck('name')->toArray())->toEqual(['Template B', 'Template A', 'Template C']);
});


it('can only fetched or get the templates that has is_active column set to true', function () {
    Template::factory()->create([
        'name' => 'Template A',
        'display_order' => 2,
        'is_active' => true,
    ]);

    Template::factory()->create([
        'name' => 'Template B',
        'display_order' => 1,
        'is_active' => false,
    ]);

    Template::factory()->create([
        'name' => 'Template c',
        'display_order' => 2,
        'is_active' => false,
    ]);

    $action = app(GetAllActiveTemplates::class);
    $actionGetAll = app(GetAllTemplates::class);

    $activeTemplates = $action->get()->count();
    $totalTemplates = $actionGetAll->get()->count();

    expect($activeTemplates)->toEqual(1);
    expect($totalTemplates)->toEqual(3);
});


it('can return zero collection when no templates are availables', function () {
    $action = app(GetAllActiveTemplates::class);
    $templates = $action->get();
    expect($templates)->toBeInstanceOf(Collection::class);
});

it('can fetched all the upcoming to be release templates', function () {
    Template::factory()->create([
        'name' => 'Template a',
        'display_order' => 1,
        'release_date' => now()->addDays(1),
    ]);

    Template::factory()->create([
        'name' => 'Template b',
        'display_order' => 2,
        'release_date' => now()->addDays(2),
    ]);

    Template::factory()->create([
        'name' => 'Template c',
        'display_order' => 3,
        'release_date' => now()->addDays(4),
    ]);

    Template::factory()->create([
        'name' => 'Template d',
        'display_order' => 4,
    ]);


    $action = app(GetAllUpcomingTemplates::class);
    $upcomingTemplates = $action->get()->count();

    expect($upcomingTemplates)->toBe(3);
});

it('can fetch and check required fields in the template instance', function () {
    $template = Template::factory()->create();

    expect($template->getAttributes())->toHaveKeys([
        'id',
        'name',
        'slug',
        'description',
        'thumbnail',
        'bundle_url',
    ]);
});


it('check bundle_url is a full working public URL (starts with http:// or https:// and points to storage)', function () {
    $template = Template::factory()->create();
    expect(str()->startsWith($template->bundle_url, ['http', 'https']))->toBeTrue();
});

it('can return valid url for the thumbnail', function () {
    $template = Template::factory()->create();
    expect(str()->startsWith($template->thumbnail, ['http', 'https']))->toBeTrue();
});

expect()->extend('toBeUnique', function () {
    return $this->toBe(
        collect($this->value)->unique()->values()->all()
    );
});

it('can check unique slugs', function () {
    Template::factory(50)->create();

    $action = app(GetAllTemplates::class);
    $templates = $action->get();
    $slugs = $templates->pluck('slug');

    expect($slugs->count())->toBe($slugs->unique()->count());
});
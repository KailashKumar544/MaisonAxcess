<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;

/**
 * Class PageController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PageController extends Controller
{
    public function index()
    {
        return view('admin.page', [
            'title' => 'Page',
            'breadcrumbs' => [
                trans('backpack::crud.admin') => backpack_url('dashboard'),
                'Page' => false,
            ],
            'page' => 'resources/views/admin/page.blade.php',
            'controller' => 'app/Http/Controllers/Admin/PageController.php',
        ]);
    }
}

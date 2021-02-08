<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\BaseMenu;
use Illuminate\Http\Request;

class PackageController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Package'
            ],
        ];

        return view('admin.master.package.index', [
            'title' => 'Package',
            'list_menu' => $this->menu_admin(),
        ]);
    }
}

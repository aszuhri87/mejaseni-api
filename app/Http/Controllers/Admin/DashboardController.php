<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseMenu;
use Illuminate\Http\Request;

class DashboardController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Dashboard'
            ],
        ];

        return view('admin.dashboard.index', [
            'title' => 'Dashboard',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }
}

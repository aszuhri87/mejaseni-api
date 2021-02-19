<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseMenu;

class DashboardController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Dashboard'
            ],
        ];

        return view('student.dashboard.index', [
            'title' => 'Dashboard',
            'navigation' => $navigation,
            'list_menu' => $this->menu_student(),
        ]);
    }
}

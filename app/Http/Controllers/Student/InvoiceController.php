<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\BaseMenu;
use Illuminate\Http\Request;

class InvoiceController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Invoice'
            ],
        ];

        return view('student.invoice.index', [
            'title' => 'Invoice',
            'navigation' => $navigation,
            'list_menu' => $this->menu_student(),
        ]);
    }
}

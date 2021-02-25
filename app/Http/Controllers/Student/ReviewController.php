<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\BaseMenu;
use Illuminate\Http\Request;

class ReviewController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Review'
            ],
        ];

        return view('student.review.index', [
            'title' => 'Review',
            'navigation' => $navigation,
            'list_menu' => $this->menu_student(),
        ]);
    }
}

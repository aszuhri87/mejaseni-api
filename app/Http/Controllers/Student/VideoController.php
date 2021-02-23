<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Auth;
use Storage;

class VideoController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Materi'
            ],
            [
                'title' => 'Data Video Kelas'
            ],
        ];

        return view('student.video.index', [
            'title' => 'Video',
            'navigation' => $navigation,
            'list_menu' => $this->menu_student(),
        ]);
    }

    public function get_video()
    {
        
    }
}

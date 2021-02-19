<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Auth;
use Storage;

class MyClassController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'My Class'
            ],
        ];

        $path = Storage::disk('s3')->url('/');
        $data = DB::table('students')
            ->select([
                'students.*',
                DB::raw("CONCAT('{$path}',students.image) as image_url"),
            ])
            ->whereNull('students.deleted_at')
            ->where('id',Auth::guard('student')->user()->id)
            ->first();

        return view('student.my-class.index', [
            'title' => 'My Class',
            'navigation' => $navigation,
            'list_menu' => $this->menu_student(),
            'data' => $data,
        ]);
    }
}

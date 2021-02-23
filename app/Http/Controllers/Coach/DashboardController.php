<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\BaseMenu;
use App\Models\Coach;
use App\Models\CoachClassroom;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Coach'
            ],
            [
                'title' => 'Dashboard'
            ],
        ];

        return view('coach.dashboard.index', [
            'title' => 'Dashboard',
            'navigation' => $navigation,
            'list_menu' => $this->menu_coach(),
        ]);
    }

    public function summary_course_chart()
    {
        try {
            date_default_timezone_set("Asia/Jakarta");

            $data = DB::table('classrooms')
                ->select('classrooms.*')
                ->whereRaw("
                    (SELECT EXTRACT(WEEK FROM current_date)) = (SELECT EXTRACT(WEEK FROM classrooms.created_at))
                ")
                ->whereRaw("
                    (SELECT EXTRACT(MONTH FROM current_date)) = (SELECT EXTRACT(MONTH FROM classrooms.created_at))
                ")
                ->whereRaw("
                    (SELECT EXTRACT(YEAR FROM current_date)) = (SELECT EXTRACT(YEAR FROM classrooms.created_at))
                ")
                ->join('coach_classrooms','coach_classrooms.classroom_id','classrooms.id')
                ->join('coaches','coaches.id','coach_classrooms.coach_id')
                ->WhereNull('classrooms.deleted_at')
                ->where('coaches.id',Auth::guard('coach')->id())
                ->orderBy('classrooms.created_at', 'asc')
                ->get();

            $range_time = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

            $kelas_booking = [0, 0, 0, 0, 0, 0, 0];
            $kelas_dihadiri = [0, 0, 0, 0, 0, 0, 0];
            foreach ($data as $key => $value) {
                if (date('N', strtotime($value->created_at)) == 1) {
                    if ($value->type == 1) { //1 kelas booking
                        $kelas_booking[0]++;
                    }
                    if ($value->type == 2) { //2 kelas hadir
                        $kelas_dihadiri[0]++;
                    }
                } elseif (date('N', strtotime($value->created_at)) == 2) {
                    if ($value->type == 1) { //1 kelas booking
                        $kelas_booking[1]++;
                    }
                    if ($value->type == 2) { //2 kelas hadir
                        $kelas_dihadiri[1]++;
                    }
                } elseif (date('N', strtotime($value->created_at)) == 3) {
                    if ($value->type == 1) { //1 kelas booking
                        $kelas_booking[2]++;
                    }
                    if ($value->type == 2) { //2 kelas hadir
                        $kelas_dihadiri[2]++;
                    }
                } elseif (date('N', strtotime($value->created_at)) == 4) {
                    $kelas_booking[3]++;
                    if ($value->type == 1) { //1 kelas booking
                        $kelas_booking[3]++;
                    }
                    if ($value->type == 2) { //2 kelas hadir
                        $kelas_dihadiri[3]++;
                    }
                } elseif (date('N', strtotime($value->created_at)) == 5) {
                    if ($value->type == 1) { //1 kelas booking
                        $kelas_booking[4]++;
                    }
                    if ($value->type == 2) { //2 kelas hadir
                        $kelas_dihadiri[5]++;
                    }
                } elseif (date('N', strtotime($value->created_at)) == 6) {
                    if ($value->type == 1) { //1 kelas booking
                        $kelas_booking[5]++;
                    }
                    if ($value->type == 2) { //2 kelas hadir
                        $kelas_dihadiri[5]++;
                    }
                } elseif (date('N', strtotime($value->created_at)) == 7) {
                    if ($value->type == 1) { //1 kelas booking
                        $kelas_booking[6]++;
                    }
                    if ($value->type == 2) { //2 kelas hadir
                        $kelas_dihadiri[6]++;
                    }
                }
            }

            $result = [
                'range_time' => $range_time,
                'kelas_booking' => $kelas_booking,
                'kelas_dihadiri' => $kelas_dihadiri
            ];
            return response([
                "status" => 200,
                "data" => $result,
                "message"   => 'Successfully!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "status" => 400,
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function side_summary_course()
    {
        try {

            $coach = DB::table('coaches')
                ->join('coach_classrooms', 'coach_classrooms.coach_id', 'coaches.id')
                ->join('session_videos', 'session_videos.coach_id', 'coaches.id')
                ->select(
                    DB::raw("COUNT(coach_classrooms.id) AS total_kelas"),
                    DB::raw("COUNT(session_videos.id) AS video_tutorial"),
                )
                ->first();

            return response([
                "status" => 200,
                "data" => $coach,
                "message"   => 'Successfully!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "status" => 400,
                "message" => $e->getMessage(),
            ]);
        }
    }
}

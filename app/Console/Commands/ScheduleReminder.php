<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use App\Models\StudentNotification;
use App\Models\CoachNotification;
use App\Models\Income;

use Mail;
use DateTime;

class ScheduleReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email-reminder:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Broadcast schedule reminder via email to students and coaches';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            date_default_timezone_set("Asia/Jakarta");

            $now = date('Y-m-d H:i:s');

            $student_schedules = DB::table('student_schedules')
                ->whereNull('deleted_at');

            $coach_classrooms = DB::table('coach_classrooms')
                ->whereNull('deleted_at');

            $coaches = DB::table('coaches')
                ->whereNull('deleted_at');

            // -6 Jam
            $student_notification_1 = DB::table('student_notifications')
                ->where('type', 11)
                ->distinct()
                ->whereNull('deleted_at');

            // -1 Jam
            $student_notification_2 = DB::table('student_notifications')
                ->where('type', 12)
                ->distinct()
                ->whereNull('deleted_at');

            // -5 Menit
            $student_notification_3 = DB::table('student_notifications')
                ->where('type', 13)
                ->distinct()
                ->whereNull('deleted_at');

            $status_schedules = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id as coach_schedule_id',
                    'students.name as student_name',
                    'students.email as student_email',
                    'students.id as student_id',
                    'student_schedules.id as student_schedule_id',
                    DB::raw("CASE
                        WHEN coach_schedules.datetime::timestamp <= '{$now}'::timestamp + INTERVAL '6 HOURS'
                            AND student_notification_1.id IS NULL
                            THEN 1
                        WHEN coach_schedules.datetime::timestamp <= '{$now}'::timestamp + INTERVAL '1 HOURS'
                            AND student_notification_2.id IS NULL
                            THEN 2
                        WHEN coach_schedules.datetime::timestamp <= '{$now}'::timestamp + INTERVAL '5 MINUTES'
                            AND student_notification_3.id IS NULL
                            THEN 3
                        ELSE 0
                    END status"),
                ])
                ->leftJoinSub($student_schedules, 'student_schedules', function($join){
                    $join->on('student_schedules.coach_schedule_id','coach_schedules.id');
                })
                ->leftJoinSub($student_notification_1, 'student_notification_1', function($join){
                    $join->on('student_notification_1.student_schedule_id','student_schedules.id');
                })
                ->leftJoinSub($student_notification_2, 'student_notification_2', function($join){
                    $join->on('student_notification_2.student_schedule_id','student_schedules.id');
                })
                ->leftJoinSub($student_notification_3, 'student_notification_3', function($join){
                    $join->on('student_notification_3.student_schedule_id','student_schedules.id');
                })
                ->leftJoin('student_classrooms','student_classrooms.id','=','student_schedules.student_classroom_id')
                ->leftJoin('students','students.id','=','student_classrooms.student_id')
                // ->whereRaw("coach_schedules.datetime::timestamp > '{$now}'::timestamp")
                ->whereNotNull('students.id')
                ->whereNull('coach_schedules.deleted_at');

            $coach_schedules = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime',
                    'platforms.name as platform',
                    'coach_schedules.platform_link',
                    'coaches.id as coach_id',
                    'coaches.email as coach_email',
                    'coaches.name as coach_name',
                    'classrooms.name as classroom',
                    'classrooms.price as price_classroom',
                    'classrooms.session_total',
                    'status_schedules.*',
                ])
                ->joinSub($coach_classrooms, 'coach_classrooms', function($join){
                    $join->on('coach_classrooms.id','coach_schedules.coach_classroom_id');
                })
                ->joinSub($coaches, 'coaches', function($join){
                    $join->on('coaches.id','coach_classrooms.coach_id');
                })
                ->joinSub($status_schedules, 'status_schedules', function($join){
                    $join->on('status_schedules.coach_schedule_id','coach_schedules.id');
                })
                ->leftJoin('platforms','coach_schedules.platform_id','=','platforms.id')
                ->leftJoin('classrooms','coach_classrooms.classroom_id','=','classrooms.id')
                // ->whereRaw("coach_schedules.datetime::timestamp > '{$now}'::timestamp")
                ->whereRaw("status_schedules.status != 0")
                ->whereNull('coach_schedules.deleted_at')
                ->orderBy('coach_schedules.datetime', 'asc')
                ->distinct('coach_schedules.datetime')
                ->get();

            foreach ($coach_schedules as $schedule) {
                $datetime = date('d M Y, H:i:s', strtotime($schedule->datetime));

                $messages = [
                    0 => '-',
                    1 => "Kelas {$schedule->classroom} pada {$datetime} akan dimulai 6 Jam lagi",
                    2 => "Kelas {$schedule->classroom} pada {$datetime} akan dimulai 1 Jam lagi",
                    3 => "Kelas {$schedule->classroom} pada {$datetime} akan dimulai 5 Menit lagi",
                ];

                $schedule->message = $messages[$schedule->status];

                DB::transaction(function () use($schedule){
                    if($schedule->status == 1){

                        $salary = ($schedule->price_classroom / $schedule->session_total) * 0.45;

                        Income::create([
                            'student_schedule_id' => $schedule->student_schedule_id,
                            'coach_id' => $schedule->coach_id,
                            'amount' => $salary,
                            'formula' => "({$schedule->price_classroom}/{$schedule->session_total})*0.45",
                        ]);
                    }

                    CoachNotification::create([
                        'coach_id' => $schedule->coach_id,
                        'coach_schedule_id' => $schedule->id,
                        'type' => (int)"2".$schedule->status,
                        'text' =>  $schedule->message,
                        'datetime' => date('Y-m-d H:i:s')
                    ]);

                    StudentNotification::create([
                        'student_id' => $schedule->student_id,
                        'student_schedule_id' => $schedule->student_schedule_id,
                        'type' => (int)"1".$schedule->status,
                        'text' => $schedule->message,
                        'datetime' => date('Y-m-d H:i:s')
                    ]);
                });

                Mail::send('mail.coach-class-reminder', compact('schedule'), function($message) use($schedule){
                    $message->to($schedule->coach_email, $schedule->coach_name)
                        ->from('info@mejaseni.com', 'MEJASENI')
                        ->subject('Class Reminder');
                });

                Mail::send('mail.student-class-reminder', compact('schedule'), function($message) use($schedule){
                    $message->to($schedule->student_email, $schedule->student_name)
                        ->from('info@mejaseni.com', 'MEJASENI')
                        ->subject('Class Reminder');
                });
            }

            $this->info('Running');
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}

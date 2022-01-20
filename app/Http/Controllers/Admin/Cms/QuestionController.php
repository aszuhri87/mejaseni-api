<?php

namespace App\Http\Controllers\Admin\Cms;


use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\BaseMenu;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Jobs\SendEmailReplyQuestion;

use App\Models\Question;

use DB;
use DataTables;

class QuestionController extends BaseMenu
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $navigation = [
            [
                'title' => 'CMS'
            ],
            [
                'title' => 'Question'
            ],
        ];

        return view('admin.cms.question.index', [
            'title' => 'Question',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function dt()
    {
        $data = DB::table('questions')
            ->select([
                'questions.*',
            ])
            ->where('is_reply',false)
            ->whereNull([
                'questions.deleted_at'
            ])
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Reply
     *
     * @return \Illuminate\Http\Response
     */
    public function reply(Request $request)
    {
        try {
            $result = DB::transaction(function () use($request){


                $name = $request->name;
                $question = $request->question;
                $answer = $request->answer;
                Mail::send('mail.reply', compact('question', 'answer','name'), function($message) use ($request){
                    $message->to($request->email, $request->name)
                        ->from('info@mejaseni.com', 'MEJASENI')
                        ->subject('FAQ');
                });

                $result = Question::find($request->id)->update([
                    'is_reply' => true
                ]);
            });


            return response([
                "message"   => 'Successfully send!'
            ], 200);
         } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> "Internal Server Error"
            ]);
         }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $result = DB::transaction(function () use($request){
                $result = Question::create([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'message' => $request->message
                ]);
                return $result;
            });

            return response([
                "data"      => $result,
                "message"   => 'Successfully saved!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> "Internal Server Error"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $result = Question::find($id);

            DB::transaction(function () use($result){
                $result->delete();
            });

            if ($result->trashed()) {
                return response([
                    "message"   => 'Successfully deleted!'
                ], 200);
            }
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }
}

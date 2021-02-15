<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Coach;
use Illuminate\Support\Facades\Hash;
use App\Models\CoachSosmed;
use Storage;

use DataTables;

use App\Http\Controllers\BaseMenu;

class CoachController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Master'
            ],
            [
                'title' => 'Coach'
            ],
        ];

        return view('admin.master.coach.index', [
            'title' => 'Coach',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function dt()
    {
        $path = Storage::disk('s3')->url('/');

        $data = DB::table('coaches')
            ->select([
                'coaches.*',
                DB::raw("CONCAT('{$path}',coaches.image) as image_url"),
            ])
            ->whereNull([
                'deleted_at'
            ])
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function store(Request $request)
    {
        try {
            $result = DB::transaction(function () use($request){
                $validated = $request->validate([
                    'password' => 'required|confirmed|min:6',
                ]);
                if(!empty($request->profile_avatar)){
                    $file = $request->file('profile_avatar');
                    $path = Storage::disk('s3')->put('media', $file);
                }
                $result = Coach::create([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'phone' => $request->phone,
                    'description' => $request->profil_description,
                    'expertise' => $request->expertise,
                    'image' => $path,
                ]);

                foreach ($request->sosmed as $key => $value) {
                    CoachSosmed::create([
                        'coach_id' => $result->id,
                        'url' => $request->url_sosmed[$key],
                        'sosmed_id' => $value
                    ]);
                }

                return $result;
            });

            return response([
                "data"      => $result,
                "message"   => 'Successfully saved!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $result = DB::transaction(function () use($request, $id){

                $coach = Coach::find($id);
                $update = [];
                if(!empty($request->profile_avatar)){
                    $file = $request->file('profile_avatar');
                    $path = Storage::disk('s3')->put('media', $file);
                    $update = [
                        'name' => $request->name,
                        'username' => $request->username,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'description' => $request->profil_description,
                        'expertise' => $request->expertise,
                        'image' => $path,
                    ];
                }else{
                    $update = [
                        'name' => $request->name,
                        'username' => $request->username,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'description' => $request->profil_description,
                        'expertise' => $request->expertise,
                    ];
                }
                $coach->update($update);

                foreach ($request->init_sosmed_coach as $key => $value) {
                    CoachSosmed::UpdateOrCreate(
                        ['id'=>$value],
                        [
                            'sosmed_id' => $request->sosmed[$key],
                            'coach_id' => $id,
                            'url' => $request->url_sosmed[$key]
                        ]
                    );
                }
                return $coach;
            });

            return response([
                "data"      => $result,
                "message"   => 'Successfully updated!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $result = Coach::find($id);

            DB::transaction(function () use($result){
                $coach_sosmeds = DB::table('coach_sosmeds')
                    ->where('coach_id',$result->id)
                    ->delete();

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

    public function coach_sosmed($coach_id)
    {
        try {
            $result = CoachSosmed::where('coach_id',$coach_id)->get();

            return response([
                "message"   => 'OK!',
                "data" => $result
            ], 200);

        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function delete_medsos($id)
    {
        try {
            $result = CoachSosmed::find($id);

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

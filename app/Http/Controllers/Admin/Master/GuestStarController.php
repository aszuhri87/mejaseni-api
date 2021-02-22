<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\GuestStar;

use DataTables;
use Storage;

class GuestStarController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Master'
            ],
            [
                'title' => 'Guest Star'
            ],
        ];

        return view('admin.master.guest-star.index', [
            'title' => 'Guest Star',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function dt()
    {
        $path = Storage::disk('s3')->url('/');

        $data = DB::table('guest_stars')
            ->select([
                'guest_stars.id',
                'guest_stars.coach_id',
                'guest_stars.expertise_id',
                'guest_stars.name',
                'guest_stars.image',
                'guest_stars.description',
                DB::raw("CONCAT('{$path}',guest_stars.image) as image_url"),
                'expertises.name as expertise'
            ])
            ->leftJoin('expertises','expertises.id','=', 'guest_stars.expertise_id')
            ->whereNull([
                'guest_stars.deleted_at'
            ])
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function store(Request $request)
    {
        try {
            if(!isset($request->file) && !isset($request->is_coach)){
                return response([
                    "message"   => 'Gambar harus diisi!'
                ], 400);
            }

            if(isset($request->is_coach)){
                $coach = DB::table('coaches')
                    ->where('id', $request->coach_id)
                    ->first();

                if(!$coach){
                    return response([
                        "message"   => 'Coach harus diisi!'
                    ], 400);
                }
            }

            $result = DB::transaction(function () use($request){
                if(isset($request->is_coach)){
                    $coach = DB::table('coaches')
                        ->where('id', $request->coach_id)
                        ->first();
                }

                $result = GuestStar::create([
                    'coach_id' => $request->coach_id,
                    'expertise_id' => $request->is_coach ? $coach->expertise_id : $request->expertise_id,
                    'name' => $request->is_coach ? $coach->name : $request->name,
                    'image' => $request->is_coach ? $coach->image : $request->file,
                    'description' => $request->is_coach ? $coach->description : $request->description,
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
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            if(!isset($request->file) && !isset($request->is_coach)){
                return response([
                    "message"   => 'Gambar harus diisi!'
                ], 400);
            }

            if(isset($request->is_coach)){
                $coach = DB::table('coaches')
                    ->where('id', $request->coach_id)
                    ->first();

                if(!$coach){
                    return response([
                        "message"   => 'Coach harus diisi!'
                    ], 400);
                }
            }

            $result = DB::transaction(function () use($request, $id){
                if(isset($request->is_coach)){
                    $coach = DB::table('coaches')
                        ->where('id', $request->coach_id)
                        ->first();
                }

                $result = GuestStar::find($id)->update([
                    'coach_id' => $request->coach_id,
                    'expertise_id' => $request->is_coach ? $coach->expertise_id : $request->expertise_id,
                    'name' => $request->is_coach ? $coach->name : $request->name,
                    'image' => $request->is_coach ? $coach->image : $request->file,
                    'description' => $request->is_coach ? $coach->description : $request->description,
                ]);

                return $result;
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
            $result = GuestStar::find($id);

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

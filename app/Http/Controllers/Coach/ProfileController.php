<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\BaseMenu;
use App\Models\Coach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends BaseMenu
{
    public function index()
    {
        $expertises = DB::table('expertises')
            ->whereNull('deleted_at')
            ->get();

        $navigation = [
            [
                'title' => 'Profile',
            ],
        ];

        $path = Storage::disk('s3')->url('/');

        return view('coach.profile.index', [
            'title' => 'Profile',
            'navigation' => $navigation,
            'list_menu' => $this->menu_coach(),
            'path' => $path,
            'expertises' => $expertises,
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            date_default_timezone_set('Asia/Jakarta');
            $validatedData = $request->validate([
                'username' => [
                    'required',
                    Rule::unique(Coach::class, 'username')
                        ->ignore($id),
                    'max:255',
                ],
                'email' => [
                    'required',
                    'email',
                    Rule::unique(Coach::class, 'email')
                        ->ignore($id),
                ],
                'name' => [
                    'required',
                    'string',
                ],
                'expertise' => [
                    'required',
                    'string',
                ],
            ]);

            $result = DB::transaction(function () use ($request,$id) {
                $data = [
                    'username' => $request->username,
                    'name' => $request->name,
                    'email' => $request->email,
                    'expertise' => $request->expertise,
                ];

                if (!empty($request->profile_avatar)) {
                    $file = $request->profile_avatar;
                    $image = Storage::disk('s3')->put('media', $file);
                    $data['image'] = $image;
                }

                if (!empty($request->coach_coordinate['radius'])) {
                    $data['radius'] = $request->coach_coordinate['radius'];
                }
                if (!empty($request->coach_coordinate['lat'])) {
                    $data['lat'] = $request->coach_coordinate['lat'];
                }
                if (!empty($request->coach_coordinate['lng'])) {
                    $data['lng'] = $request->coach_coordinate['lng'];
                }
                $data['home_course_available'] = $request->has('home_course_available');

                $update = Coach::find($id);
                $update->update($data);

                return $update;
            });

            return response([
                'status' => 200,
                'data' => $result,
                'message' => 'Successfully updated!',
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);

            return response([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function change_password(Request $request, $id)
    {
        try {
            $validate = $request->validate([
                'current_password' => 'required',
                'password' => 'required|confirmed',
            ]);

            $user = Coach::find($id);
            $result;
            if (Hash::check($request->current_password, $user->password)) {
                $result = DB::transaction(function () use ($user,$request) {
                    $update = $user->update([
                        'password' => Hash::make($request->password),
                    ]);

                    return $update;
                });

                return response([
                    'status' => 200,
                    'data' => $result,
                    'message' => 'Successfully change!',
                ], 200);
            } else {
                return response([
                    'status' => 400,
                    'message' => 'Current password does not match in database',
                ], 400);
            }
        } catch (Exception $e) {
            throw new Exception($e);

            return response([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }
}

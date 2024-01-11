<?php

namespace App\Http\Controllers;

use App\Mail\Createcount;
use App\Mail\Createmembre;
use App\Models\CompteUserModel;
use App\Models\TypeCompteModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CompteUserController extends Controller
{
    public function create_count(Request $request)
    {

        $request->validate([
            'userid' => 'required',
            'typecompte' => 'required',
            'currency' => 'required',
        ]);

        $usercount=CompteUserModel::where('userid',$request->userid)->first();
        $encode=mt_rand(1, 999);

        if ((count($usercount)) >= 0) {
            $count_number = "GOM" . date('y') . ("000") . ($usercount->count_number) . count($usercount);
        } else {
            $count_number = "Gom" . date('y') . ("000") . (count(CompteUserModel::all()));
        }

        $user = User::where('id', $request->userid)->first();

        if (User::where('id', $request->userid)->exists()) {

            if ($request->typecompte == 'courant' || $request->typecompte == "credit") {
                if ((count(CompteUserModel::where('userid',$request->userid)->get())) >= 10) {
                    return response()->json([
                        "message" => 'Vous avez déjà atteint les nombres total des comptes!',
                        "code" => 402,
                    ], 402);
                } else {
                    CompteUserModel::create([
                        'count_number' => $count_number,
                        'userid' => $request->userid,
                        'currency' => $request->currency,
                        'typecompte' => $request->typecompte
                    ]);

                    Mail::to($user->email)->send(new Createmembre(
                        $user->name,
                        $user->post_name,
                        $request->typecompte,
                        $request->currency,
                        $user->prename,
                        $count_number
                    ));

                    return response()->json([
                        "message" => 'success',
                        "code" => 200,
                        "data" => User::with('roles', 'type', 'permissions', 'count')->where('id', $request->userid)->first(),
                    ], 200);
                }
            } else {
                return response()->json([
                    "message" => 'Type compte doit courant ou credit!',
                    "code" => 402,
                ], 402);
            }
        } else {
            return response()->json([
                "message" => 'Cet utilisateur n\'est pas recconue dans le système!',
                "code" => 402,
            ], 402);
        }
    }
    public function listtypecompte()
    {

        return response()->json([
            "message" => 'success',
            "code" => 200,
            "data" => TypeCompteModel::all(),
        ], 200);
    }
    public function locked_count($id)
    {
        if (CompteUserModel::where('id', $id)->where('status',0)->exists()) {
            $compte = CompteUserModel::where('id', $id)->first();
            $compte->status = 1;
            $compte->update();
            return response()->json([
                "message" => 'success',
                "code" => 200,
                "data" => CompteUserModel::where('status', 0)->get(),
            ], 200);
        } else {
            return response()->json([
                "message" => 'Ce compte n\'existe pas!',
                "code" => 402,
            ], 402);
        }
    }

    public function getmemberbycount_number($count_number)
    {
       $count=CompteUserModel::where('count_number',$count_number)->first();
       $countt = CompteUserModel::with('membre','transaction.agent')->where('count_number', $count_number)->first();
        if ($countt == null) {
            return response()->json([
                "message" => 'Numèro de compte introuvable!',
                "code" => 402,
            ], 402);
        } else {
            $count=CompteUserModel::find($countt->id);
            return response()->json([
                "message" => 'Count exists',
                "code" => 200,
                "balance" => $count->balance(),
                "data" => $countt,
            ], 200);
        }
    }

}

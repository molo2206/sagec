<?php

namespace App\Http\Controllers;

use App\Models\CompteUserModel;
use App\Models\TransactionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use function PHPSTORM_META\type;

class TransactionController extends Controller
{
    public function make_transaction(Request $request)
    {
        $request->validate([
            'type' => "required",
            'amount' => "required",
            'mount_lettre' => "required",
            'compteid' => "required",
            'password' => 'required',
            'date' => 'required',
        ]);


        $compte = CompteUserModel::with('membre', 'transaction.agent')->where('id', $request->compteid)->orderby('created_at','DESC')->first();
        $count = CompteUserModel::where('id',$compte->id)->first();
        $user = Auth::user();
        if($count){
            if ($user->status == 1) {
                if (Hash::check($request->password, $user->pswd )) {
                    if ($request->type == 'credit' || $request->type == 'debit') {
                        if (!$compte) {
                            return response()->json([
                                "message" => "Le numèro de compte n'est pas recconue dans le système!",
                                "code" => "402"
                            ], 402);
                        } else {
                            if ($request->type == 'debit') {
                                $compte->transaction()->create([
                                    'designation' => $request->designation,
                                    'currency' => $compte->currency,
                                    'debit' => $request->amount,
                                    'solde' => $count->balance()+$request->amount,
                                    'mount_lettre' => $request->mount_lettre,
                                    'userid' => $user->id,
                                    'method' => "wallet",
                                    'date' => $request->date,
                                ]);
                                return response()->json([
                                    "message" => "Le compte " . " " . $compte->count_number . " " . "est debité avec succès!",
                                    "code" => 200,
                                    "balance" => $count->balance(),
                                    "data" => CompteUserModel::with('membre', 'transaction.agent')->where('id', $compte->id)->first(),
                                ], 200);
                            } else {
                                if ($compte->allowWithdraw($request->amount)) {
                                    $compte->transaction()->create([
                                        'designation' => $request->designation,
                                        'currency' => $compte->currency,
                                        'credit' => $request->amount,
                                        'solde' => $count->balance()-$request->amount,
                                        'mount_lettre' => $request->mount_lettre,
                                        'userid' => $user->id,
                                        'method' => "wallet",
                                         'date' => $request->date,
                                    ]);
                                    return response()->json([
                                        "message" => "Le compte " . " " . $compte->count_number . " " . "est credité avec succès!",
                                        "code" => 200,
                                        "balance" => $count->balance(),
                                        "data" => CompteUserModel::with('membre', 'transaction.agent')->where('id', $compte->id)->first(),
                                    ], 200);
                                } else {
                                    return response()->json([
                                        "message" => "Solde insuffisant!",
                                        "code" => "402"
                                    ], 402);
                                }
                            }
                        }
                    } else {
                        return response()->json([
                            "message" => "Le type de transaction doit etre credit ou debit!",
                            "code" => "402"
                        ], 402);
                    }
                } else {
                    return response()->json([
                        "message" => 'Le mot de passe est incorrect',
                        "code" => 422
                    ], 422);
                }
            } else {
                return response()->json([
                    "message" => 'Votre compte n\'est pas activé',
                    "code" => 422
                ], 422);
            }
        }else{
          return response()->json([
                 "message" => "C'est compte n'existe pas dans le système!",
                 "code" => 402,
          ],402);
        }

    }
}

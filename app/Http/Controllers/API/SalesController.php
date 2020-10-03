<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Notifications\AffiliateCommissionReceivedNotification;
use App\Notifications\CommissionReceivedNotification;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function sale(Request $request) {
        $request->validate([
            'amount' => 'required|integer'
        ]);
        $user = auth('sanctum')->user();

        $amount = $request->amount;
        $seller = $user->user_type;
        $affiliateCommission = 0;
        if($seller === 'B'){
            $commission = (20/100) * $amount;
            $affiliateCommission = (5/100) * $commission;
            $user->notify(new AffiliateCommissionReceivedNotification($user, $commission, $affiliateCommission));
        }else if($seller === 'F'){
            $commission = (15/100) * $amount;
            $user->notify(new CommissionReceivedNotification($user, $commission));
        } else if(in_array($seller, ['C', 'D', 'E'])){
            $commission = (15/100) * $amount;
            $affiliateCommission = (5/100) * $commission;
            $user->notify(new AffiliateCommissionReceivedNotification($user, $commission, $affiliateCommission));
        }else {
            return response()->json([
                'message' => 'User type incorrect!'
            ], 403);
        }

        $sale = Sale::create([
            'amount' => $amount,
            'commission' => $commission,
            'affiliateCommission' => $affiliateCommission,
            'user_id' => $user->id,
        ]);

        return response()->json([
            'data' => $sale,
        ]);
    }

    public function sales() {
        $user = auth('sanctum')->user();

        return response()->json([
            'data' => $user->sales,
        ]);
    }
}

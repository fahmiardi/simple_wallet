<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

class UserTopup extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'amount',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'fromable');
    }

    public function topup(User $user, $amount)
    {
        try {
            DB::beginTransaction();

            // create topup
            $topup = $user->topups()->create([
                'amount' => $amount,
                'status' => 'done'
            ]);
            
            // create transaction
            $transaction = $topup->transaction()->create([
                'amount' => $amount,
                'type' => 'credit',
                'balance_before' => $user->balance,
                'balance_after' => $newBalance = $user->balance + $amount,
            ]);

            // update balance user
            $user->balance = $newBalance;
            $user->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }
    }

    public function maximumBalance($userBalance, $amount)
    {
        $newBalance = $userBalance + $amount;

        if ($newBalance < config('app.max_balance')) return false;

        return $newBalance;
    }
}

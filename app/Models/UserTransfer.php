<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

class UserTransfer extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'amount',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'fromable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function createTransfer(User $fromUser, User $toUser, $amount)
    {
        try {
            DB::beginTransaction();

            // make transfer
            $transfer = static::newInstance([
                'amount' => $amount
            ]);
            $transfer->user()->associate($fromUser);
            $transfer->toUser()->associate($toUser);
            $transfer->save();
            
            // generate transaction credit
            $transactionCredit = new Transaction([
                'type' => 'credit',
                'balance_before' => $toUser->balance,
                'balance_after' => $newBalanceTo = $toUser->balance + $amount,
            ]);
            $transactionCredit->fromable()->associate($transfer);
            $transactionCredit->user()->associate($toUser);
            $transactionCredit->save();

            // generate transaction debit
            $transactionDebit = new Transaction([
                'type' => 'debit',
                'balance_before' => $fromUser->balance,
                'balance_after' => $newBalanceFrom = $fromUser->balance - $amount,
            ]);
            $transactionDebit->fromable()->associate($transfer);
            $transactionDebit->user()->associate($fromUser);
            $transactionDebit->save();

            // update destination balance
            $toUser->balance = $newBalanceTo;
            $toUser->save();

            // update source balance
            $fromUser->balance = $newBalanceFrom;
            $fromUser->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }  
    }
}

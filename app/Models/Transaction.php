<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

class Transaction extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'balance_before',
        'balance_after'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    public function fromable()
    {
        return $this->morphTo('fromable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactionsByUser(User $user, $limit = 10)
    {
        $transactions = $user->transactions()
            ->with('fromable')
            ->whereHasMorph('fromable', [UserTransfer::class])
            ->limit($limit)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $results = [];
        
        foreach ($transactions as $transaction) {
            $fromable = $transaction->fromable;

            if ($transaction->type === 'credit') {
                $username = $fromable->user->username;
                $amount = (float) $fromable->amount;
            } else {
                $username = $fromable->toUser->username;
                $amount = (float) ('-'.$fromable->amount);
            }

            array_push($results, [
                'username' => $username,
                'amount' => $amount,
            ]);
        }

        return $results;
    }

    public function transactionsByValue($limit = 10)
    {
        $transactions = static::query()
            ->join('user_transfers', 'user_transfers.id', '=', 'transactions.fromable_id')
            ->join('users', 'users.id', '=', 'transactions.user_id')
            ->selectRaw('users.username, sum(user_transfers.amount) as transacted_value')
            ->where('type', 'debit')
            ->groupBy('transactions.user_id')
            ->orderByDesc('transacted_value')
            ->limit($limit)
            ->get()
            ->map(function ($transaction) {
                return [
                    'username' => $transaction->username,
                    'transacted_value' => (float) $transaction->transacted_value,
                ];
            });

        return $transactions;
    }
}

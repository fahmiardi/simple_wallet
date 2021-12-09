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
}

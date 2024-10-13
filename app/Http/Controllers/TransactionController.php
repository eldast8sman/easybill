<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransationRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;
use PDO;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $limit = !empty($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $transactions = Transaction::orderBy('created_at', 'desc')->paginate($limit);
        return response([
            'status' => 'success',
            'message' => 'Transactions fetched successfully',
            'data' => TransactionResource::collection($transactions)->response()->getData(true)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $last_transaction = Transaction::where('user_id', $request->user_id)->orderBy('created_at', 'desc')->first();
        if(!empty($last_transaction)){
            $balance_before = $last_transaction->balance_after;
        } else {
            $balance_before = 0;
        }
        if($request->type == 'Credit'){
            $balance_after = $balance_before + $request->amount;
        } elseif($request->type == 'Debit'){
            $balance_after = $balance_before - $request->amount;
        } else {
            return response([
                'status' => 'failed',
                'message' => 'Transaction Type must e either Credit or Debit'
            ], 409);
        }

        $all = $request->all();
        $all['balance_before'] = $balance_before;
        $all['balance_after'] = $balance_after;

        $transaction = Transaction::create($all);

        return response([
            'status' => 'success',
            'message' => 'Transaction successfully created',
            'data' => new TransactionResource($transaction)
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        if(empty($transaction)){
            return response([
                'status' => 'failed',
                'message' => 'Transaction not found'
            ], 404);
        }

        return response([
            'status' => 'success',
            'message' => 'Transaction fetched succssfully',
            'data' => new TransactionResource($transaction)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransationRequest $request, Transaction $transaction)
    {
        $all = $request->all();
        $balance_before = $transaction->balance_before;
        if($request->type == 'Credit'){
            $balance_after = $balance_before + $request->amount;
        } elseif($request->type == 'Debit'){
            $balance_after = $balance_before - $request->amount;
        } else {
            return response([
                'status' => 'failed',
                'message' => 'Transaction Type must e either Credit or Debit'
            ], 409);
        }

        $all['balance_before'] = $balance_before;
        $all['balance_after'] = $balance_after;

        $transaction->update($all);

        defer(fn() => $this->update_later_transactions($transaction));

        return response([
            'status' => 'success',
            'message' => 'Transaction record successfully updated',
            'data' => new TransactionResource($transaction)
        ], 200);
    }

    private function update_later_transactions(Transaction $transaction){
        $later_transactions = Transaction::where('user_id', $transaction->user_id)->where('created_at', '>', $transaction->created_at)
                                ->orderBy('created_at', 'asc')->get();
        if(!empty($later_transactions)){
            foreach($later_transactions as $later_tranx){
                $prev_tranx = Transaction::where('user_id', $later_tranx->user_id)->where('created_at', '<', $later_tranx)
                                ->orderBy('created_at', 'desc')->first();
                if(empty($prev_tranx)){
                    $balance_before = 0;
                } else {
                    $balance_before = $prev_tranx->balance_after;
                }
                if($later_tranx->type == 'Credit'){
                    $balance_after = $balance_before + $later_tranx->amount;
                } else {
                    $balance_after = $balance_before - $later_tranx->amount;
                }
                $later_tranx->update([
                    'balance_after' => $balance_after
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        defer(fn() => $this->update_later_transactions($transaction));

        return response([
            'status' => 'success',
            'message' => 'Transaction redord successfully deleted'
        ], 200);
    }
}

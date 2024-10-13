<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_transaction(){
        $user = User::factory()->create();
        $data = [
            'user_id' => $user->id,
            'type' => 'Credit',
            'amount' => 1000.50,
            'remarks' => 'Test remarks'
        ];

        $transaction = $this->postJson(route('transaction.store'), $data)->assertOk()->json();
        $this->assertEquals('success', $transaction['status']);
        $this->assertDatabaseHas('transactions', ['amount' => $data['amount'], 'user_id' => $data['user_id']]);
    }

    public function test_fetch_transactions(){
        $user = User::factory()->create();
        $transaction = Transaction::factory()->create(['user_id' => $user->id]);

        $tranx = $this->getJson(route('transaction.index'))->assertOk()->json();
        $this->assertEquals($transaction->id, $tranx['data']['data'][0]['id']);
    }

    public function test_update_transaction(){
        $user = User::factory()->create();
        $transaction = Transaction::factory()->create(['user_id' => $user->id]);

        $data = [
            'type' => 'Credit',
            'amount' => 1000.50,
            'remarks' => 'Test remarks'
        ];

        $updated = $this->putJson(route('transaction.update', $transaction->id), $data)->assertOk()->json();
        $this->assertEquals($updated['status'], 'success');
        $this->assertDatabaseHas('transactions', [
            'type' => $data['type'],
            'amount' => $data['amount'],
            'remarks' => $data['remarks']
        ]);
    }

    public function test_delete_transaction(){
        $user = User::factory()->create();
        $transaction = Transaction::factory()->create(['user_id' => $user->id]);

        $this->deleteJson(route('transaction.delete', $transaction->id))->assertOk();
        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
    }
}

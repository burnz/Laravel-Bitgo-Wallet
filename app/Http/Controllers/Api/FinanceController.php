<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use bjolbordi\BitGoSDK\BitGoExpress;

class FinanceController extends Controller
{

	protected $hostName;
	protected $port;
	protected $token;
	protected $testNet;
    private $ssl;


    public function __construct()
    {
    	$this->hostName = config('blockchain.hostname');
    	$this->port = config('blockchain.port');
        $this->token = config('blockchain.accessToken');
        $this->testNet = config('blockchain.testNet');
        $this->ssl = config('blockchain.ssl');

    }

    public function buildTransaction(Request $request)
    {

    	try {
	    	$coin = $request->coin;
	    	$walletId = $request->wallet_id;
	    	$recipients = $request->recipients;

	    	$blocks = $request->block;
	    	$feeRate = $request->fee_rate;

		    $bitgoExpress = new BitGoExpress($this->ssl,$this->hostName, $this->port, $coin);
		    $bitgoExpress->accessToken = $this->token;
		    $bitgoExpress->walletId = $walletId;

		    $buildTransaction = $bitgoExpress->buildTransaction($recipients, $blocks, $feeRate);

		    return response()->json(['status' => 'success', 'data' => $buildTransaction, 'host_name'=>$this->hostName, 'port'=>$this->port]);

		} catch (\Exception $exception) {
			return response()->json(['status' => 'error', 'message' => $exception->getMessage()]);
		}
    }

    public function sendTransaction(Request $request)
   {
       try {
            $coin = $request->coin;
            $walletId = $request->wallet_id;
            $address = $request->address;
            $walletPassphrase = $request->password;
            $requestedAmountToBaseCoin = $request->amount;
            $blocks = $request->block;
            $feeRate = $request->fee_rate;

            $bitgoExpress = new BitGoExpress($this->ssl,$this->hostName, $this->port, $coin);
            $bitgoExpress->accessToken = $this->token;
            $bitgoExpress->walletId = $walletId;

            $sendTransaction = $bitgoExpress->sendTransaction($address, $requestedAmountToBaseCoin, $walletPassphrase, null, $blocks, $feeRate);
            return response()->json(['status' => 'success', 'data' => $sendTransaction]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()]);
        }
   }

    public function sendTransactionToMany(Request $request)
        {

        	try {

    	    	$coin = $request->coin;
    	    	$walletId = $request->wallet_id;
				$recipients = $request->recipients;
    	    	$walletPassphrase = $request->password;
    	    	$blocks = $request->block;
    	    	$feeRate = $request->fee_rate;

    	    	$bitgoExpress = new BitGoExpress($this->ssl,$this->hostName, $this->port, $coin);
    	    	$bitgoExpress->accessToken = $this->token;
    	    	$bitgoExpress->walletId = $walletId;

    	    	$sendTransaction = $bitgoExpress->sendTransactionToMany($recipients, $walletPassphrase, null, $blocks, $feeRate);

    	    	return response()->json(['status' => 'success', 'data' => $sendTransaction]);

    	    } catch (\Exception $exception) {
    	    	return response()->json(['status' => 'error', 'message' => $exception->getMessage()]);
    	    }

        }


}

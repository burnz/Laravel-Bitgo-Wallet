<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use bjolbordi\BitGoSDK\BitGoSDK;
use bjolbordi\BitGoSDK\BitGoExpress;


class WalletController extends Controller
{

    private $token;
    private $port;
    private $hostName;
    private $testNet;
    private $ssl;

    public function __construct()
    {
    	$this->hostName = config('blockchain.hostname');
    	$this->port = config('blockchain.port');
        $this->token = config('blockchain.accessToken');
        $this->ssl = config('blockchain.ssl');
        // $this->token = '1';
        $this->testNet = config('blockchain.testNet');

    }



    public function listPendingApprovals(Request $request) {
        try {

            $walletId =  $request->wallet_id;
            $coin =  $request->coin;

            $bitgo = new BitGoSDK($this->token, $coin, $this->testNet);
            $bitgo->walletId =  $walletId;
            $generateAddress = $bitgo->listPendingApprovals($walletId);

            return response()->json($generateAddress);

        } catch (\Exception $e) {

            $message = $e->getMessage();
            return response()->json($message);

        }
    }

    public function generateWallet(Request $request)
    {
        try {

            $coin =  $request->coin;
            $label =  $request->label;
            $passphrase =  $request->passphrase;

            $bitgoExpress = new BitGoExpress($this->ssl, $this->hostName, $this->port, $coin);
            $bitgoExpress->accessToken = $this->token;
            $generateWallet = $bitgoExpress->generateWallet($label, $passphrase);

            return response()->json($generateWallet);

        } catch (\Exception $e) {

            $message = $e->getMessage();
            return response()->json($message);

        }

    }





    /**
     * generate new address on the wallet.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generateAddress(Request $request)
    {

        try {

            $coin =  $request->coin;
            $walletId =  $request->wallet_id;

            $bitgo = new BitGoSDK($this->token, $coin, $this->testNet);
            $bitgo->walletId =  $walletId;
            $generateAddress = $bitgo->createWalletAddress();

            return response()->json($generateAddress);

        } catch (\Exception $e) {

            $message = $e->getMessage();
            return response()->json($message);

        }

    }

    public function getWalletAddress(Request $request) {

        try {

            $coin =  $request->coin;
            $walletId =  $request->wallet_id;
            $addressOrId =  $request->addressOrId;

            $bitgo = new BitGoSDK($this->token, $coin, $this->testNet);
            $bitgo->walletId =  $walletId;
            $generateAddress = $bitgo->getWalletAddress($addressOrId);

            return response()->json($generateAddress);

        } catch (\Exception $e) {

            $message = $e->getMessage();
            return response()->json($message);

        }
    }

    public function verifyAddress(Request $request)
    {
        try {

            $coin =  $request->coin;
            $address = $request->address;

            $bitgoExpress = new BitGoExpress($this->ssl,$this->hostName, $this->port, $coin);
            $bitgoExpress->accessToken = $this->token;

            $verifyData = $bitgoExpress->verifyAddress($address);

            return response()->json(['status' => 'success', 'data' => $verifyData]);

        } catch (\Exception $e) {
            $message = $e->getMessage();
            return response()->json(['status' => 'error', 'message' => $message]);

        }
    }

    public function listWallets(Request $request) {
        try {

            $coin =  $request->coin;

            $bitgo = new BitGoSDK($this->token, $coin, $this->testNet);

            $listWallets = $bitgo->listWallets();

            return response()->json($listWallets);

        } catch (\Exception $e) {

            $message = $e->getMessage();
            return response()->json($message);

        }
    }

    /**
     * This API call retrieves wallet object information by the wallet ID.
     *
     * @param bool $allTokens   Gets details of all tokens associated with this wallet. Only valid for ETH/TETH
     * @return array
     */
    public function getWallet(Request $request) {
        try {

            $walletId =  $request->walletId;
            $coin =  $request->coin;

            $bitgo = new BitGoSDK($this->token, $coin, $this->testNet);
            $bitgo->walletId =  $walletId;

            $wallet = $bitgo->getWallet($walletId);

            return response()->json($wallet);

        } catch (\Exception $e) {

            $message = $e->getMessage();
            return response()->json($message);

        }
    }

    /**
     * This API call retrieves wallet object information by an address belonging to the wallet.
     *
     * @param string $walletAddress The address
     * @return array
     */
    public function getWalletByAddress(Request $request) {
        try {

            $coin =  $request->coin;
            $walletAddress = $request->wallet_address;

            $bitgo = new BitGoSDK($this->token, $coin, $this->testNet);

            $wallet = $bitgo->getWalletByAddress($walletAddress);

            return response()->json($wallet);

        } catch (\Exception $e) {

            $message = $e->getMessage();
            return response()->json($message);

        }
    }



    /**
     * Wallet transfers represent digital currency sends and receives on your wallet.
     *
     * @param string $transactionId ID of the wallet transfer
     * @return array
     */
    public function getWalletTransfer(Request $request) {
        try {

            $walletId =  $request->wallet_id;
            $coin =  $request->coin;
            $transactionId =  $request->transaction_id;

            $bitgo = new BitGoSDK($this->token, $coin, $this->testNet);
            $bitgo->walletId =  $walletId;

            $wallet = $bitgo->getWalletTransfer($transactionId);

            return response()->json($wallet);

        } catch (\Exception $e) {

            $message = $e->getMessage();
            return response()->json($message);

        }
    }

    /**
     * Wallet transfers represent digital currency sends and receives on your wallet.
     *
     * @param string $transactionId ID of the wallet transfer
     * @return array
     */
    public function getWalletTransactions(Request $request) {
        try {

            $walletId =  $request->wallet_id;
            $coin =  $request->coin;

            $bitgo = new BitGoSDK($this->token, $coin, $this->testNet);
            $bitgo->walletId =  $walletId;

            $walletTransactions = $bitgo->getWalletTransactions();

            return response()->json($walletTransactions);

        } catch (\Exception $e) {

            $message = $e->getMessage();
            return response()->json($message);

        }
    }


    /**
     * Retrieves a list of transfers, which correspond to the deposits and withdrawals of digital currency on a wallet.
     *
     * @param string $prevId    Continue iterating from this prevId (provided by nextBatchPrevId in the previous list)
     * @param bool $allTokens   Gets transfers of all tokens associated with this wallet. Only valid for ETH/TETH.
     * @return array
     */
    public function listWalletTransfers(Request $request) {

        try {

            $walletId =  $request->wallet_id;
            $coin =  $request->coin;

            $bitgo = new BitGoSDK($this->token, $coin, $this->testNet);
            $bitgo->walletId =  $walletId;

            $walletTransfers = $bitgo->listWalletTransfers();

            return response()->json($walletTransfers);

        } catch (\Exception $e) {

            $message = $e->getMessage();
            return response()->json($message);

        }
    }

    public function generateUniqRandomToken()
    {
        try {
            $token = md5(uniqid(rand(), true));
            return response()->json(['status' => 'success', 'data' => $token]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()]);
        }

        return response()->json(['status' => 'success', 'data' => $token]);

    }


}

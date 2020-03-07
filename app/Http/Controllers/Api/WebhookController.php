<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use bjolbordi\BitGoSDK\BitGoSDK;

class WebhookController extends Controller
{
    private $token;
    private $port;
    private $hostname;
    private $testNet;


    public function __construct()
    {
    	$this->hostName = config('blockchain.hostname');
    	$this->port = config('blockchain.port');
    	$this->token = config('blockchain.accessToken');
    	$this->testNet = config('blockchain.testNet');
    }


    public function addUserWebhook(Request $request) {

        try {

            $coin =  $request->coin;
            $url =  $request->url;
            $type =  $request->type;
            $label =  $request->label;
            $numConfirmations =  $request->numConfirmations;

            $bitgo = new BitGoSDK($this->token, $coin, $this->testNet);

            $userWebhook = $bitgo->addUserWebhook($url, $type, $label, $numConfirmations);

            return response()->json($userWebhook);

        } catch (\Exception $e) {

            $message = $e->getMessage();
            return response()->json($message);

        }
    }


    public function addWalletWebhook(Request $request) {

        try {

            $WebhooksArray = [];

            $confirmUurl =  $request->confirm_url;
            $maxConfirmations =  $request->max_confirmations;

            $coin =  $request->coin;
            $url =  $request->url;
            $type =  $request->type;
            $walletId =  $request->wallet_id;
            $numConfirmations =  $request->numConfirmations;

            $bitgo = new BitGoSDK($this->token, $coin, $this->testNet);
            $bitgo->walletId = $walletId;

            $walletWebhook = $bitgo->addWalletWebhook($url, 'transfer', $numConfirmations);
            array_push($WebhooksArray,$walletWebhook);

            for ($i=1; $i <= $maxConfirmations ; $i++) {
                $confirmWebhook = $bitgo->addWalletWebhook($confirmUurl, $type, $i);
                array_push($WebhooksArray,$confirmWebhook);
            }

            return response()->json($WebhooksArray);

        } catch (\Exception $e) {

            $message = $e->getMessage();
            return response()->json($message);

        }
    }

    /**
     * Add a webhook that will result in an HTTP callback at the specified URL from BitGo when events are triggered.
     *
     * @param string $url           URL to fire the webhook to.
     * @param string $type          Type of event to listen to (can be 'block' or 'wallet_confirmation').
     * @param int $numConfirmations Number of confirmations before triggering the webhook. If 0 or unspecified, requests will be sent to the callback endpoint when the transfer is first seen and when it is confirmed.
     * @return array
     */
    public function addBlockWebhook(Request $request) {

        try {

            $coin =  $request->coin;
            $url =  $request->url;
            $type =  $request->type;
            $numConfirmations =  $request->numConfirmations;

            $bitgo = new BitGoSDK($this->token, $coin, $this->testNet);

            $walletWebhook = $bitgo->addBlockWebhook($url, $type, $numConfirmations);

            return response()->json($walletWebhook);

        } catch (\Exception $e) {

            $message = $e->getMessage();
            return response()->json($message);

        }
    }

    public function removeBlockWebhook(Request $request) {
        try {

            $coin =  $request->coin;
            $url =  $request->url;
            $type =  $request->type;

            $bitgo = new BitGoSDK($this->token, $coin, false);

            $walletWebhook = $bitgo->removeBlockWebhook($url, $type);

            return response()->json($walletWebhook);

        } catch (\Exception $e) {

            $message = $e->getMessage();
            return response()->json($message);

        }
    }

    /**
     * This API allows you to simulate and test a webhook so you can view its response.
     *
     * @param string $webhookId
     * @return array
     */
    public function simulateUserWebhook(Request $request) {

        try {

            $coin =  $request->coin;
            $webhookId =  $request->webhookId;
            $blockId =  $request->blockId;

            $bitgo = new BitGoSDK($this->token, $coin, $this->testNet);

            $simulation = $bitgo->simulateUserWebhook($webhookId, $blockId);

            return response()->json($simulation);

        } catch (\Exception $e) {

            $message = $e->getMessage();
            return response()->json($message);

        }

    }


    public function listUserWebhooks(Request $request) {

        try {

            $coin =  $request->coin;
            $bitgo = new BitGoSDK($this->token, $coin, $this->testNet);
            $marketData = $bitgo->listUserWebhooks();

            return response()->json($marketData);

        } catch (\Exception $e) {

            $message = $e->getMessage();
            return response()->json($message);

        }
    }

    /**
     * List all webhooks set up on the wallet.
     *
     * @param bool $allTokens   Gets details of all token pending approvals. Only valid for ETH/TETH
     * @return array
     */
    public function listWalletWebhooks(Request $request) {
        try {

            $coin =  $request->coin;
            $walletId =  $request->walletId;

            $bitgo = new BitGoSDK($this->token, $coin, $this->testNet);
            $bitgo->walletId = $walletId;

            $response = $bitgo->listWalletWebhooks();

            return response()->json($response);

        } catch (\Exception $e) {

            $message = $e->getMessage();
            return response()->json($message);

        }
    }


        /**
     * This API allows you to simulate and test a webhook so you can view its response.
     *
     * @param string $webhookId         Webhook ID.
     * @param string $transferId        ID of the transfer to be used in the simulation.
     * @param string $pendingApprovalId ID of the pending approval to be used in the simulation.
     * @return array
     */
    public function simulateWalletWebhook(Request $request) {
        try {

            $coin =  $request->coin;
            $webhookId =  $request->webhookId;
            $transferId =  $request->transferId;

            $walletId =  $request->walletId;
            $bitgo = new BitGoSDK($this->token, $coin, $this->testNet);
            $bitgo->walletId = $walletId;

            $simulation = $bitgo->simulateWalletWebhook($webhookId, $transferId);

            return response()->json($simulation);

        } catch (\Exception $e) {

            $message = $e->getMessage();
            return response()->json($message);

        }
    }


    public function getWebhookPayload(Request $request) {
        try {

            $coin =  $request->coin;
            $bitgo = new BitGoSDK($this->token, $coin, $this->testNet);

            $walletId =  $request->walletId;
            $bitgo->walletId = $walletId;

            $response = $bitgo->getWebhookPayload();

            return response()->json($response);

        } catch (\Exception $e) {

            $message = $e->getMessage();
            return response()->json($message);

        }
    }


    public function removeWalletWebhook(Request $request) {
        try {

            $coin =  $request->coin;
            $url =  $request->url;
            $type =  $request->type;
            $bitgo = new BitGoSDK($this->token, $coin, $this->testNet);
            $walletId =  $request->walletId;
            $bitgo->walletId = $walletId;

            $response = $bitgo->removeWalletWebhook($url,$type);

            return response()->json($response);

        } catch (\Exception $e) {

            $message = $e->getMessage();
            return response()->json($message);

        }
    }
}

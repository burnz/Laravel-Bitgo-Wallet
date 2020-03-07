<?php

use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('buildTransaction', 'Api\FinanceController@buildTransaction');
Route::get('buildTransaction1', 'Api\WalletController@buildTransaction1');
Route::post('signBuildTransaction1', 'Api\FinanceController@signBuildTransaction1');
Route::post('sendTransaction', 'Api\FinanceController@sendTransaction');
Route::post('sendTransactionToMany', 'Api\FinanceController@sendTransactionToMany');

/* kotes routes :D */
/* wallet routes */
Route::post('generateWallet', 'Api\WalletController@generateWallet');
Route::post('generateMultiWallet', 'Api\WalletController@generateMultiWallet');
Route::get('test', 'Api\WalletController@test');



Route::post('generateAddress', 'Api\WalletController@generateAddress');
Route::post('getWalletAddress', 'Api\WalletController@getWalletAddress');
Route::get('verifyAddress', 'Api\WalletController@verifyAddress');
Route::post('listWallets', 'Api\WalletController@listWallets');
Route::post('getWallet', 'Api\WalletController@getWallet');
Route::post('getWalletByAddress', 'Api\WalletController@getWalletByAddress');
Route::post('listWalletTransfers', 'Api\WalletController@listWalletTransfers');
Route::post('generateUniqRandomToken', 'Api\WalletController@generateUniqRandomToken');
Route::post('getWalletTransfer', 'Api\WalletController@getWalletTransfer');
Route::post('getWalletTransactions', 'Api\WalletController@getWalletTransactions');
Route::get('sweep', 'Api\WalletController@sweep');


/* market info routes */
Route::post('getMarketPriceData', 'Api\MarketController@getMarketPriceData');
Route::post('getExchangeRates', 'Api\MarketController@getExchangeRates');
Route::post('getExchangeRates_v2', 'Api\MarketController_v2@getExchangeRates');
Route::post('estimateTransactionFees', 'Api\MarketController@estimateTransactionFees');



/* webhook routes */
Route::post('addUserWebhook', 'Api\WebhookController@addUserWebhook');
Route::post('addBlockWebhook', 'Api\WebhookController@addBlockWebhook');
Route::post('addWalletWebhook', 'Api\WebhookController@addWalletWebhook');
Route::post('simulateUserWebhook', 'Api\WebhookController@simulateUserWebhook');
Route::post('simulateWalletWebhook', 'Api\WebhookController@simulateWalletWebhook');
Route::post('listPendingApprovals', 'Api\WalletController@listPendingApprovals');
Route::post('listUserWebhooks', 'Api\WebhookController@listUserWebhooks');
Route::post('listWalletWebhooks', 'Api\WebhookController@listWalletWebhooks');
Route::post('removeWalletWebhook', 'Api\WebhookController@removeWalletWebhook');
Route::post('removeBlockWebhook', 'Api\WebhookController@removeBlockWebhook');
Route::post('getWebhookPayload', 'Api\WebhookController@getWebhookPayload');


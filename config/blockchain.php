<?php

/*
 * This file is part of the Laravel Blockchain package.
 *
 * (c) Famurewa Taiwo <famurewa_taiwo@yahoo.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


return [
	/**
	* Blockchain api provided by blockchain.com
    */

    'accessToken' => env('BITGO_TOKEN'),

	/**
	* This is the default charge fee bitcoin miners at 0.00001
	*/
    'port' => env('BXS_PORT', 4000),
    'testNet' => env('TESTNET', true),
    // 'testNet' => false,
    /*
    * This is your own transaction fee in btc
    */
    'hostname' => env('BXS_URL', 'localhost'),
    'ssl' => env('SSL_VERIFICATION', false)
];

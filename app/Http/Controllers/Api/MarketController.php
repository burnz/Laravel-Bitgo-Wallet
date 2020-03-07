<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use bjolbordi\BitGoSDK\BitGoSDK;
use Cache;

class MarketController extends Controller
{
    private $token;
    private $port;
    private $hostName;
    private $testNet;


    public function __construct()
    {
    	$this->hostName = config('blockchain.hostname');
    	$this->port = config('blockchain.port');
        $this->token = config('blockchain.accessToken');
    	$this->testNet = config('blockchain.testNet');

    }

    /**
     * This API is still being developed. BitGo will update this section soon.
     *
     * @return array
     */
    public function getMarketPriceData(Request $request) {
        try {

            $coin =  $request->coin;
            $bitgo = new BitGoSDK($this->token, $coin, $this->testNet);

            $marketData = $bitgo->getMarketPriceData();

            return response()->json($marketData);

        } catch (\Exception $e) {

            $message = $e->getMessage();
            return response()->json($message);

        }
    }


    /**
     * function return exchange rates of given coin and the currency.
     *
     * @return array
     */
    public function getExchangeRates(Request $request) {

        try {

            $coin =  $request->coin;
            $curency =  strtoupper($request->curency);
            $marketResponse = [];

            foreach ($coin as $key => $value) {

                $bitgo = new BitGoSDK($this->token, $value, $this->testNet);
                $marketData = $bitgo->getMarketPriceData();

                $currencies = $marketData['latest']['currencies'];

                $usdRate = $marketData['latest']['currencies']['USD'];

                $prevDayHigh = $usdRate['24h_avg']*2 - $usdRate['last'];

                $dif = $this->changePercentage($prevDayHigh, $usdRate['last'] );


                if (array_key_exists($curency, $currencies )) {

                    $marketResponse[$value] = $marketData['latest']['currencies'][$curency];
                    $marketResponse[$value]['24h_dif'] = $dif;
                }else{
                    if ($curency == 'GEL') {
                        $currencyRateWithUSD = $this->gelRates()['data']['rate'];
                    }else{
                        $currencyRateWithUSD = $this->retesBetweenCurrencies('USD')['data'][$curency];
                    }

                    $rateWithUSD = $marketData['latest']['currencies']['USD'];
                    $marketResponse[$value]['24h_avg'] = $currencyRateWithUSD * $rateWithUSD['24h_avg'];
                    $marketResponse[$value]['last'] = $currencyRateWithUSD * $rateWithUSD['last'];
                    $marketResponse[$value]['24h_dif'] = $dif;


                }


            }

            return [ 'status' => 1 , 'data' => $marketResponse ];

        } catch (\Exception $e) {

            $message = $e->getMessage();
            return response()->json([ 'status' => 0 , 'data' => $message ]);

        }
    }

    public function changePercentage($last = 0, $prevDayAvg=0)
    {
        $diff = (($prevDayAvg - $last) / $last)*100;
        return $diff;
    }

    public function retesBetweenCurrencies($from = 'USD')
    {
        try {


            if (!Cache::has('usd_rates')) {

                Cache::forget('usd_rates');
                $client = new \GuzzleHttp\Client();
                $response = $client->request('GET', 'https://api.exchangeratesapi.io/latest?base=USD');
                $body = json_decode($response->getBody(),true);
                $exhcangeRate = $body;
                $rates = $exhcangeRate['rates'];

                Cache::remember('usd_rates', 50  * 60, function () use($rates){
                    return $rates;
                });

                return [ 'status' => 1 , 'data' => $rates];

            }else{

                $rates = Cache::get('usd_rates');
                return [ 'status' => 1 , 'data' => $rates];

            }

        } catch (\Throwable $th) {

            $message = $th->getMessage();
            return response()->json([ 'status' => 0 , 'data' => $message ]);
        }

    }


    public function gelRates()
    {

        try {

            if (!Cache::has('gel_rates')) {

                Cache::forget('gel_rates');
                // $client = new \GuzzleHttp\Client();
                // $response = $client->request('GET', 'https://www.freeforexapi.com/api/live?pairs=USDGEL');
                // $body = json_decode($response->getBody(),true);
                // $exhcangeRate = $body;

                $client = new \SoapClient('http://nbg.gov.ge/currency.wsdl');
                $exchangeRate = $client->GetCurrency('USD');

                $rates = [
                    "rate" => $exchangeRate,
                    "timestamp" => ""
                ];


                Cache::remember('gel_rates', 50 * 60, function () use($rates){
                    return $rates;
                });

                return [ 'status' => 1 , 'data' => $rates];

            }else{

                $rates = Cache::get('gel_rates');
                return [ 'status' => 1 , 'data' => $rates];

            }

        } catch (\Throwable $th) {

            $message = $th->getMessage();
            return response()->json([ 'status' => 0 , 'data' => $message ]);
        }
    }

    /**
     * Add a webhook that will result in an HTTP callback at the specified URL from BitGo when events are triggered.
     *
     * @param string $url           URL to fire the webhook to.
     * @param string $type          Type of event to listen to (can be of type 'block').
     * @param string $label         Label of the new webhook.
     * @param int $numConfirmations Number of confirmations before triggering the webhook.
     * @return array
     */



    /**
     * Returns the recommended fee rate per kilobyte to confirm a transaction within a target number of blocks.
     *
     * @param int $numBlocks    The target number of blocks for the transaction to be confirmed. The accepted range is 1 - 1000 and the default value is 2.
     * @return array
     */
    public function estimateTransactionFees(Request $request) {
        try {

            $coin =  $request->coin;
            $bitgo = new BitGoSDK($this->token, $coin, $this->testNet);
            $marketData = $bitgo->estimateTransactionFees();

            return response()->json($marketData);

        } catch (\Exception $e) {

            $message = $e->getMessage();
            return response()->json($message);

        }
    }

}

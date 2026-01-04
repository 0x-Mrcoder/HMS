<?php

namespace App\Classes;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Zainpay
{
    protected $baseUrl;
    protected $publicKey;

    public function __construct()
    {
        // Determine environment (sandbox or live)
        $this->baseUrl = config('hms.zainpay.mode') === 'live' 
            ? 'https://api.zainpay.ng' 
            : 'https://sandbox.zainpay.ng';
            
        $this->publicKey = config('hms.zainpay.public_key');
    }

    /**
     * Make a GET request to Zainpay API
     */
    protected function get($endpoint, $params = [])
    {
        try {
            $response = Http::withToken($this->publicKey)
                ->get("{$this->baseUrl}/{$endpoint}", $params);

            return $response->json();
        } catch (\Exception $e) {
            Log::error("Zainpay GET Error: {$e->getMessage()}", ['endpoint' => $endpoint]);
            return ['code' => '99', 'description' => 'Request Failed'];
        }
    }

    /**
     * Make a POST request to Zainpay API
     */
    protected function post($endpoint, $data = [])
    {
        try {
            $response = Http::withToken($this->publicKey)
                ->post("{$this->baseUrl}/{$endpoint}", $data);

            return $response->json();
        } catch (\Exception $e) {
            Log::error("Zainpay POST Error: {$e->getMessage()}", ['endpoint' => $endpoint]);
            return ['code' => '99', 'description' => 'Request Failed'];
        }
    }

    /**
     * Make a PATCH request to Zainpay API
     */
    protected function patch($endpoint, $data = [])
    {
        try {
            $response = Http::withToken($this->publicKey)
                ->patch("{$this->baseUrl}/{$endpoint}", $data);

            return $response->json();
        } catch (\Exception $e) {
            Log::error("Zainpay PATCH Error: {$e->getMessage()}", ['endpoint' => $endpoint]);
            return ['code' => '99', 'description' => 'Request Failed'];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Zainbox Management
    |--------------------------------------------------------------------------
    */

    /**
     * Create a new Zainbox
     * 
     * @param string $name Name of the Zainbox
     * @param string $callbackUrl Webhook URL
     * @param string $emailNotification Email for notifications
     * @param string $tags Tags for the Zainbox
     * @param string $codeName prefix (optional)
     * @param bool $allowAutoInternalTransfer (optional)
     */
    public function createZainbox($name, $callbackUrl, $emailNotification, $tags, $codeNamePrefix = null, $allowAutoInternalTransfer = false)
    {
        return $this->post('zainbox/create/request', [
            'name' => $name,
            'callbackUrl' => $callbackUrl,
            'emailNotification' => $emailNotification,
            'tags' => $tags,
            'codeNamePrefix' => $codeNamePrefix,
            'allowAutoInternalTransfer' => $allowAutoInternalTransfer,
            'description' => "Zainbox for {$name}"
        ]);
    }

    /**
     * Get all connected Zainboxes
     */
    public function listZainboxes()
    {
        return $this->get('zainbox/list');
    }

    /**
     * Update an existing Zainbox
     */
    public function updateZainbox($codeName, $name, $tags = null, $callbackUrl = null, $emailNotification = null)
    {
        return $this->patch('zainbox/update', array_filter([
            'codeName' => $codeName,
            'name' => $name,
            'tags' => $tags,
            'callbackUrl' => $callbackUrl,
            'emailNotification' => $emailNotification,
        ]));
    }

    /**
     * Get profile of a specific Zainbox
     */
    public function getZainboxProfile($zainboxCode)
    {
        return $this->get("zainbox/profile/{$zainboxCode}");
    }

    /**
     * Get total collected payments summary for a Zainbox
     */
    public function getZainboxCollectionSummary($zainboxCode, $dateFrom = null, $dateTo = null)
    {
        $params = array_filter([
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo
        ]);
        return $this->get("zainbox/transfer/deposit/summary/{$zainboxCode}", $params);
    }

    /*
    |--------------------------------------------------------------------------
    | Virtual Account Management
    |--------------------------------------------------------------------------
    */

    /**
     * Create a Virtual Account linked to a Zainbox
     * Bank types: "fidelity", "fcmb", "gtBank"
     */
    public function createVirtualAccount($data)
    {
        // Expected keys in $data: bankType, firstName, surname, email, mobileNumber, dob, gender, address, title, state, bvn, zainboxCode
        return $this->post('virtual-account/create/request', $data);
    }

    /**
     * Get all virtual accounts under a Zainbox
     */
    public function listVirtualAccounts($zainboxCode)
    {
        return $this->get("zainbox/virtual-accounts/{$zainboxCode}");
    }

    /**
     * Get balance of a specific virtual account
     */
    public function getVirtualAccountBalance($accountNumber)
    {
        return $this->get("virtual-account/wallet/balance/{$accountNumber}");
    }

    /**
     * Get balances of all virtual accounts in a Zainbox
     */
    public function getAllZainboxBalances($zainboxCode)
    {
        return $this->get("zainbox/accounts/balance/{$zainboxCode}");
    }

    /**
     * Update status of a virtual account (Activate/Deactivate)
     */
    public function updateVirtualAccountStatus($zainboxCode, $accountNumber, $status)
    {
        return $this->patch('virtual-account/change/account/status', [
            'zainboxCode' => $zainboxCode,
            'accountNumber' => $accountNumber,
            'status' => $status
        ]);
    }

    /**
     * Get transactions of a specific virtual account
     */
    public function getVirtualAccountTransactions($accountNumber)
    {
        return $this->get("virtual-account/wallet/transactions/{$accountNumber}");
    }

    /*
    |--------------------------------------------------------------------------
    | Dynamic Virtual Accounts (DVA)
    |--------------------------------------------------------------------------
    */

    /**
     * Create a Dynamic Virtual Account (DVA)
     */
    public function createDynamicVirtualAccount($data)
    {
        // Expected keys: bankType, email, amount (kobo), zainboxCode, txnRef, duration (sec), callBackUrl
        $data['accountName'] = 'Zainpay Checkout'; // Fixed requirement
        return $this->post('virtual-account/dynamic/create/request', $data);
    }

    /**
     * Query status of a DVA transaction
     */
    public function queryDynamicAccountStatus($txnRef)
    {
        return $this->get("virtual-account/dynamic/deposit/status/{$txnRef}");
    }

    /*
    |--------------------------------------------------------------------------
    | Transfers & Settlements
    |--------------------------------------------------------------------------
    */

    /**
     * Initiate a Fund Transfer
     */
    public function fundTransfer($data)
    {
        // Expected keys: destinationAccountNumber, destinationBankCode, amount (kobo), sourceAccountNumber, sourceBankCode, zainboxCode, txnRef, narration, callbackUrl
        return $this->post('zainbox/bank/transfer/v2', $data);
    }

    /**
     * Verify a Fund Transfer
     */
    public function verifyTransfer($txnRef)
    {
        return $this->get("virtual-account/wallet/transaction/verify/{$txnRef}");
    }

    /**
     * Verify a Deposit (Webhook Verification)
     */
    public function verifyDeposit($txnRef)
    {
        return $this->get("virtual-account/wallet/deposit/verify/v2/{$txnRef}");
    }

     /**
     * Get list of banks
     */
    public function getBankList()
    {
        return $this->get('zainbox/bank/list');
    }

    /**
     * Perform Name Enquiry
     */
    public function nameEnquiry($bankCode, $accountNumber)
    {
        return $this->get('zainbox/bank/name-enquiry', [
            'bankCode' => $bankCode,
            'accountNumber' => $accountNumber
        ]);
    }

    /**
     * Create Scheduled Settlement
     */
    public function createSettlement($data)
    {
        // Expected keys: name, zainboxCode, scheduleType (T1, T7, T30), schedulePeriod, settlementAccountList, status
        return $this->post('zainbox/settlement', $data);
    }

    /**
     * Get Settlements for a Zainbox
     */
    public function getSettlement($zainboxCode)
    {
        return $this->get('zainbox/settlement', ['zainboxCode' => $zainboxCode]);
    }
}

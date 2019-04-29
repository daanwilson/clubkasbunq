<?php

namespace App;

use App\Http\Controllers\BankAccountController;
use bunq\Model\Generated\Endpoint\AttachmentPublic;
use bunq\Model\Generated\Endpoint\AttachmentPublicContent;
use bunq\Model\Generated\Endpoint\AttachmentTab;
use bunq\Model\Generated\Endpoint\Avatar;
use bunq\Model\Generated\Endpoint\BunqMeTab;
use bunq\Model\Generated\Endpoint\BunqMeTabEntry;
use bunq\Model\Generated\Endpoint\CashRegister;
use bunq\Model\Generated\Endpoint\Invoice;
use bunq\Model\Generated\Endpoint\MonetaryAccount;
use bunq\Model\Generated\Endpoint\MonetaryAccountBank;
use bunq\Model\Generated\Endpoint\Payment;
use bunq\Model\Generated\Object\InvoiceItemGroup;
use bunq\test\Model\Generated\AttachmentPublicTest;
use Illuminate\Database\Eloquent\Model;

use bunq\Context\ApiContext;
use bunq\Context\BunqContext;
use bunq\Util\BunqEnumApiEnvironmentType;
use bunq\Model\Generated\Object\Amount;
use bunq\Model\Generated\Object\Pointer;

//use Http\Middleware\tableModel;
//use App\ModelCustom;

class Bunq
{
    protected static $authenticated;
    protected static $bankaccounts = [];

    function __construct()
    {
        if (static::$authenticated === null) {
            static::$authenticated = true;
            if (env('BUNQ_SANDBOX')) {
                $filename = base_path('bunq_sandbox.conf');
            } else {
                $filename = base_path('bunq.conf');
            }

            try {
                $apiContext = ApiContext::restore($filename);
                $apiContext->ensureSessionActive();
                //$apiContext->resetSession();
                $apiContext->save($filename);
                BunqContext::loadApiContext($apiContext);

            } catch (\Exception $e) {
                //dd($e->getMessage());
//                $filename = base_path('bunq_sandbox.conf');

                if (env('BUNQ_SANDBOX') == 'true') {
                    $environmentType = BunqEnumApiEnvironmentType::SANDBOX(); // Can also be BunqEnumApiEnvironmentType::PRODUCTION();
                    $apiKey = env('BUNQ_API_KEY_SANDBOX'); // Replace with your API key
                } else {
                    $environmentType = BunqEnumApiEnvironmentType::PRODUCTION(); // Can also be BunqEnumApiEnvironmentType::PRODUCTION();
                    $apiKey = env('BUNQ_API_KEY'); // Replace with your API key
                }
//                $apiKey = env('BUNQ_API_KEY'); // Replace with your API key
                $deviceDescription = env('APP_NAME'); // Replace with your device description
//                //$permittedIps = ['0.0.0.0']; // List the real expected IPs of this device or leave empty to use the current IP
                $permittedIps = []; // List the real expected IPs of this device or leave empty to use the current IP
                $apiContext = ApiContext::create(
                    $environmentType,
                    $apiKey,
                    $deviceDescription,
                    $permittedIps
                );
                $apiContext->save($filename);
                BunqContext::loadApiContext($apiContext);
//                //$fileName = '/path/to/save/bunq_sandbox.conf/file/'; // Replace with your own secure location to store the API context details
//                //$apiContext->save($fileName);
            }
        }
    }

    static function get()
    {
        return new static();
    }

    /**
     *
     * @return \bunq\Model\Generated\Endpoint\BunqResponseMonetaryAccountList
     */
    function getAccounts()
    {
        return \bunq\Model\Generated\Endpoint\MonetaryAccount::listing();
    }

    function getAccount($monetaryAccountId)
    {
        return \bunq\Model\Generated\Endpoint\MonetaryAccount::get($monetaryAccountId);
    }

    /**
     * @param $monetaryAccountId
     * @return MonetaryAccountBank
     */
    function BankAccount($monetaryAccountId)
    {
        if (!array_key_exists($monetaryAccountId, static::$bankaccounts)) {
            static::$bankaccounts[$monetaryAccountId] = MonetaryAccountBank::get($monetaryAccountId);
        }
        return static::$bankaccounts[$monetaryAccountId];
    }

    function getAccountFilters($monetaryAccountId)
    {
        return $this->BankAccount($monetaryAccountId)->getValue()->getNotificationFilters();
    }

    function updateAccountFilters($monetaryAccountId, $filters = [])
    {
        return MonetaryAccountBank::update($monetaryAccountId, null, null, null, null, null, null, null, $filters);
    }

    function getCashRegisters($monetaryAccountId)
    {
        return CashRegister::listing($monetaryAccountId);
    }

    function createCashRegister($monetaryAccountId, $name)
    {
        //$result = AttachmentPublic::create(file_get_contents(env('APP_LOGO_DIR')),['X-Bunq-Attachment-Description'=>"Logo","Content-Type"=>'image/png']);
        //
        //$result = AttachmentPublic::get('466cac97-8b21-4018-a23a-0e7614919639');
        $result = MonetaryAccount::get($monetaryAccountId);
        $avatarId = $result->getValue()->getMonetaryAccountBank()->getAvatar()->getUuid();
        //dd($avatarId);
        //dd($result);
        return CashRegister::create($name, 'PENDING_APPROVAL', $avatarId, $monetaryAccountId);
    }

    /**
     * Get a specific MonetaryAccount.
     *
     * @param int $monetaryAccountId
     *
     * @return \bunq\Model\Generated\Endpoint\BunqResponseMonetaryAccount
     */
    function getPayments($monetaryAccountId, $options = [])
    {
        return \bunq\Model\Generated\Endpoint\Payment::listing(
            $monetaryAccountId,
            $options
        );
    }

    function Pay($monetaryAccountId, $options = [])
    {
        $amount = new Amount('9.95', 'EUR');
        $counterpart = new Pointer('IBAN', 'NL53BUNQ9900018931', 'Scouting st. Jozef-/Emerentiana');

        Payment::create($amount, $counterpart, 'TEST');
    }

    function BunqInvoices($account)
    {
        $this->getBunqInvoices($account);
        $this->cashInvoices($account);
    }

    protected function getBunqInvoices($bankaccount)
    {
        $monetaryAccountId = $bankaccount->external_id;
        $newer_id = \App\Invoice::where('invoiceAccountId',$bankaccount->id)->max('invoiceId');
        $options = [
            "count" => 12,
            "newer_id" => $newer_id,
        ];
        $invoices = Invoice::listing($monetaryAccountId, $options);
        foreach ($invoices->getValue() as $invoice) {
            if ($invoice->getStatus() == 'CREDITED') {
                $records = $invoice->getGroup();
                if (is_array($records)) {
                    foreach ($records as $record) {
                        if ($record->getType() == 'MONETARY_ACCOUNT') {
                            $account = BankAccount::getByName($record->getInstanceDescription());

                            if (!empty($account) && $account->id > 0) {
                                $i = new \App\Invoice();
                                $i->invoiceId = $invoice->getId();
                                $i->invoiceNumber = $invoice->getInvoiceNumber();
                                $i->invoiceDate = $invoice->getInvoiceDate();
                                $i->invoiceAmount = $invoice->getTotalVatInclusive()->getValue();
                                $i->invoiceAccountId = $bankaccount->id;
                                $i->accountId = $account->id;
                                $i->transactionAmount = $record->getProductVatInclusive()->getValue();
                                $i->save();
                            }
                        }
                    }

                }
            }
        }
    }

    protected function cashInvoices($bankaccount)
    {
        $monetaryAccountId = $bankaccount->external_id;
        $bunqaccount = Bunq::get()->BankAccount($monetaryAccountId);
        $counterpartIBAN = null;
        foreach ($bunqaccount->getValue()->getAlias() as $alias) {
            if($alias->getType()=='IBAN'){
                $counterpartIBAN = $alias;
            }
        }
        if(empty($counterpartIBAN)){
            return;
        }

        $invoices = \App\Invoice::all()->where('invoiceAccountId','=',$bankaccount->id)->where('cashed', '=', 0)->all();
        if (is_array($invoices)) {
            foreach ($invoices as $invoice) {
                $account = BankAccount::find($invoice->accountId);
                if ($account->id > 0) {
                    if($account->external_id != $monetaryAccountId){
                        $price = $invoice->transactionAmount;
                        $description = "Bankkosten (".\NumberFormat($price).")";
                        if((int)$account->debit_cards>0){
                            $cardprice = (double)Setting('debitcard_price') * (int)$account->debit_cards;
                            $price+= $cardprice;
                            $description.=" Paskosten (".\NumberFormat($cardprice).")";
                        }
                        $invoiceDate = new \DateTime($invoice->invoiceDate);
                        $description.= " Factuur ".$invoice->invoiceNumber." ".$invoiceDate->format('F Y');
                        if($price > 0.10){
                            //prijzen onder de 0.10 cent niet incasseren. Dat kost meer dan het oplevert.
                            $amount = new Amount((string)number_format($price,2,'.',''), 'EUR');
                            Payment::create($amount, $counterpartIBAN, $description, $account->external_id);

                        }
                    }
                    $invoice->cashed = 1;
                    $invoice->save();
                    //dd($price);

                }
            }
        }
        exit;
    }

    /**
     * Get a specific MonetaryAccount.
     *
     * @param int $monetaryAccountId
     *
     * @return \bunq\Model\Generated\Endpoint\BunqResponseMonetaryAccount
     */
    function getRequests($monetaryAccountId, $options = [])
    {
        /*return \bunq\Model\Generated\Endpoint\RequestInquiry::listing(
                $monetaryAccountId,
                $options
                );*/
        return BunqMeTab::listing($monetaryAccountId, $options);
    }

    function getPayRequests($monetaryAccountId, $options = [])
    {
        return \bunq\Model\Generated\Endpoint\RequestInquiry::listing(
            $monetaryAccountId,
            $options
        );
    }

    function getRequest($monetaryAccountId, $request_id)
    {
        return BunqMeTab::get($request_id, $monetaryAccountId);

    }

    /**
     * Get a specific MonetaryAccount.
     *
     * @param int $monetaryAccountId
     *
     * @return \bunq\Model\Generated\Endpoint\BunqResponseMonetaryAccount
     */
    function getRequestsResponse($monetaryAccountId, $options = [])
    {
        return \bunq\Model\Generated\Endpoint\RequestResponse::listing(
            $monetaryAccountId,
            $options
        );
    }

    function makeRequest($monetaryAccountId, $options = [])
    {
        $bunqMeTabEntry = new BunqMeTabEntry(new Amount($options['amount'], $options['currency']), $options['description'], $options['redirectUrl']);
        $createBunqMeTab = BunqMeTab::create($bunqMeTabEntry, $monetaryAccountId)->getValue();
        return BunqMeTab::get($createBunqMeTab, $monetaryAccountId);
    }

    function updateRequest($monetaryAccountId, $bunqMeTabId, $status)
    {
        return BunqMeTab::update($bunqMeTabId, $monetaryAccountId, $status);
    }
}

<?php

/**
 *
 * PHP Pro Bid
 *
 * @link        http://www.phpprobid.com
 * @copyright   Copyright (c) 2014 Online Ventures Software LTD & CodeCube SRL
 * @license     http://www.phpprobid.com/license Commercial License
 *
 * @version     7.2
 */

/**
 * QuickPay payment gateway model class
 *
 * https://learn.quickpay.net/tech-talk/payments/form/
 *
 * https://learn.quickpay.net/tech-talk/api/callback/#callback
 *
 * https://manage.quickpay.net/account/116982/settings/integration
 */
/**
 * MOD:- QUICKPAY INTEGRATION
 */

namespace Ppb\Model\PaymentGateway;

use Cube\Controller\Request\AbstractRequest,
    Ppb\Service,
    QuickPay\QuickPay as QuickPayLibrary;

class QuickPay extends AbstractPaymentGateway
{
    /**
     * payment gateway name
     */

    const NAME = 'QuickPay';

    /**
     * required settings
     */
    const MERCHANT_ID = 'QuickPayMerchantID';
    const MERCHANT_PRIVATE_KEY = 'QuickPayMerchantPrivateKey';
    const API_KEY = 'QuickPayApiKey';
//    const AGREEMENT_ID = 'QuickPayAgreementID';
//    const AGREEMENT_API_KEY = 'QuickPayAgreementApiKey';

    /**
     * form post url
     */
    const POST_URL = 'https://payment.quickpay.net';

    /**
     *
     * accepted currencies
     * first currency in array will be the default currency
     *
     * @var array
     */
    protected $_acceptedCurrencies = array('DKK');

    /**
     * quickpay description
     */
    protected $_description = 'Click to pay through QuickPay.';

    /**
     *
     * stripe payment form custom partial
     *
     * @var string
     */
    protected $_paymentFormPartial = 'forms/quickpay-payment.phtml';

    /**
     *
     * no automatic redirect
     *
     * @var bool
     */
    public static $automaticRedirect = false;

    /**
     *
     * generated payment link
     *
     * @var string
     */
    protected $_paymentLink;

    /**
     *
     * error message
     *
     * @var string
     */
    protected $_errorMessage;

    public function __construct($userId = null)
    {
        parent::__construct(self::NAME, $userId);
    }

    /**
     *
     * get payment link
     *
     * @return string
     */
    public function getPaymentLink()
    {
        return $this->_paymentLink;
    }

    /**
     *
     * set payment link
     *
     * @param string $paymentLink
     *
     * @return $this
     */
    public function setPaymentLink($paymentLink)
    {
        $this->_paymentLink = $paymentLink;

        return $this;
    }

    /**
     *
     * get error message
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->_errorMessage;
    }

    /**
     *
     * set error message
     *
     * @param string $errorMessage
     *
     * @return $this
     */
    public function setErrorMessage($errorMessage)
    {
        $this->_errorMessage = $errorMessage;

        return $this;
    }

    /**
     *
     * check if the gateway is enabled
     *
     * @return bool
     */
    public function enabled()
    {
        if (!empty($this->_data[self::MERCHANT_ID]) && !empty($this->_data[self::MERCHANT_PRIVATE_KEY]) && !empty($this->_data[self::API_KEY])) {
            return true;
        }

        return false;
    }

    /**
     *
     * get setup form elements
     *
     * @return array
     */
    public function getElements()
    {
        return array(
            array(
                'form_id'     => 'QuickPay',
                'id'          => self::MERCHANT_ID,
                'element'     => 'text',
                'label'       => $this->_('QuickPay Merchant ID'),
                'description' => $this->_('Enter your QuickPay merchant account id.'),
                'attributes'  => array(
                    'class' => 'form-control input-medium',
                ),
            ),
            array(
                'form_id'     => 'QuickPay',
                'id'          => self::MERCHANT_PRIVATE_KEY,
                'element'     => 'text',
                'label'       => $this->_('QuickPay Merchant Private Key'),
                'description' => $this->_('Enter the Merchant private key corresponding to your merchant account id.'),
                'attributes'  => array(
                    'class' => 'form-control input-medium',
                ),
            ),
            array(
                'form_id'     => 'QuickPay',
                'id'          => self::API_KEY,
                'element'     => 'text',
                'label'       => $this->_('QuickPay API Key'),
                'description' => $this->_('Enter your API Key.'),
                'attributes'  => array(
                    'class' => 'form-control input-medium',
                ),
            ),
        );
    }

    /**
     *
     * get ipn url
     *
     * @return string
     */
    public function getIpnUrl()
    {
        $params = self::$ipnUrl;
        $params['gateway'] = strtolower($this->getGatewayName());
        if ($transactionId = $this->getTransactionId()) {
            $params['transaction_id'] = $transactionId;
        }

        return $this->getView()->url($params);
    }

    public function formElements()
    {
        try {
            //Initialize client
            $client = new QuickPayLibrary(':' . $this->_data[self::API_KEY]);

            $amount = $this->getAmount() * 100;
            //Create payment
            $payment = $client->request->post('/payments', [
                'order_id' => $this->getTransactionId(),
                'currency' => $this->getCurrency(),
            ]);

            $status = $payment->httpStatus();

            //Determine if payment was created successfully
            if ($status === 201) {
                $paymentObject = $payment->asObject();

                //Construct url to create payment link
                $endpoint = sprintf("/payments/%s/link", $paymentObject->id);

                //Issue a put request to create payment link
                $link = $client->request->put($endpoint, [
                    'amount'       => $amount, //amount in cents
                    "continue_url" => $this->getSuccessUrl(),
                    "cancel_url"   => $this->getFailureUrl(),
                    "callback_url" => $this->getIpnUrl(),
                ]);

                //Determine if payment link was created succesfully
                if ($link->httpStatus() === 200) {
                    $transactionsService = new Service\Transactions();
                    $transaction = $transactionsService->findBy('id', $this->getTransactionId());

                    $transaction->save(array(
                        'quickpay_payment_id' => $paymentObject->id
                    ));

                    //Get payment link url
                    $this->setPaymentLink(
                        $link->asObject()->url);
                }
            }
            else {
                $object = $payment->asObject();
//                var_dump($object->errors);
                $this->setErrorMessage(implode('<br>', $payment->asObject()->errors));
            }
        } catch (\Exception $e) {
            $this->setErrorMessage(
                $e->getMessage());
        }

        return array();
    }

    public function getPostUrl()
    {
        return self::POST_URL;
    }

    /**
     *
     * process ipn
     *
     * @param \Cube\Controller\Request\AbstractRequest $request
     *
     * @return bool
     */
    public function processIpn(AbstractRequest $request)
    {
        $response = false;

        $transactionsService = new Service\Transactions();
        $transaction = $transactionsService->findBy('id', $request->getParam('transaction_id'));

        if ($transaction !== null && !empty($transaction['quickpay_payment_id'])) {
            try {
                //Initialize client
                $client = new QuickPayLibrary(':' . $this->_data[self::API_KEY]);

                $payment = $client->request->get('/payments/' . $transaction['quickpay_payment_id']);

                $status = $payment->httpStatus();

                $this->setTransactionId($request->getParam('transaction_id'))
                    ->setAmount($transaction['amount'])
                    ->setCurrency($transaction['currency'])
                    ->setGatewayPaymentStatus($status)
                    ->setGatewayTransactionCode('quickpay_payment_id');

                if ($payment->isSuccess()) {
                    $response = true;
                }
            } catch (\Exception $e) {
                $this->setGatewayPaymentStatus(
                    $e->getMessage());
            }
        }

        return $response;
    }

}


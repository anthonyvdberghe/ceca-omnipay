<?php

namespace Omnipay\Ceca\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * RedSys Complete Purchase Request     SWISNL
 */
class CompletePurchaseRequest extends PurchaseRequest
{

    public function getDirectPayment($directPayment)
    {
        return true;
    }

	public function getData()
	{
		$data = array();

		$data['Ds_Merchant_MerchantCode'] = $this->getParameter('merchantCode');
		$data['Ds_Merchant_Terminal'] = $this->getParameter('terminal');
		$data['Ds_Merchant_Currency'] = $this->getCurrency();
		$data['Ds_Merchant_TransactionType'] = $this->getTransactionType();
		$data['Ds_Merchant_Amount'] = (float)$this->getAmount();
		$data['Ds_Merchant_Order'] = $this->getToken();
		$data['Ds_Merchant_Identifier'] = $this->getParameter('identifier');
		$data['Ds_Merchant_MerchantURL'] = $this->getParameter('merchantURL');
		$data['Ds_Merchant_MerchantData'] = $this->getParameter('extraData');
		$data['Ds_Merchant_DirectPayment'] = $this->getParameter('directPayment');

		$merchantParameters = base64_encode(json_encode($data));

		return array(
			'Ds_MerchantParameters' => $merchantParameters,
			'Ds_Signature' => $this->generateSignature($merchantParameters),
			'Ds_SignatureVersion' => 'HMAC_SHA256_V1'
		);

	}

	// public function sendData($data)
	// {
	// 	return $this->response = new CompletePurchaseResponse($this, $data);
	// }

    public function sendData($data)
    {
        $url = $this->getEndpoint();
        $httpResponse = $this->httpClient->post($url, null, $data)->send();

        return $this->response = new CompletePurchaseResponse($this, $httpResponse->getStatusCode());

    }
}
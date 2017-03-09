<?php

namespace Omnipay\Ceca\Message;

use Omnipay\Ceca\Encryptor\Encryptor;
use Symfony\Component\HttpFoundation\Request;
use Omnipay\Ceca\Exception\BadSignatureException;
use Omnipay\Ceca\Exception\CallbackException;

/**
 * Ceca (Redsys)  Callback Response
 */
class CallbackResponse
{

    private $request;
    private $merchantKey;

    public function __construct(Request $request, $merchantKey)
    {
        $this->request = $request;
        $this->merchantKey = $merchantKey;
        $this->error = '';
    }

    /**
     * Check callback response from tpv
     *
     * @return boolean
     * @throws BadSignatureException
     * @throws CallbackException
     */
    public function isSuccessful()
    {
        $rawParameters = $this->request->get('Ds_MerchantParameters');
        \Cake\Log\Log::notice('CallbackResponse');
        \Cake\Log\Log::notice($this->request->get('MerchantID'));
        $decodedParameters = json_decode(base64_decode(strtr($rawParameters, '-_', '+/')), true);

        if (!$this->checkSignature(
            $rawParameters,
            $decodedParameters['Ds_Order'],
            $this->request->get('Ds_Signature')
        )
        ) {
            throw new BadSignatureException();
        }

        //check response, code "000" to "099" means success
        if ((int)$decodedParameters['Ds_Response'] > 99) {
            throw new CallbackException(null, (int)$decodedParameters['Ds_Response']);
        }

        return true;
    }

    private function checkSignature($data, $orderId, $expectedSignature)
    {
        $key = Encryptor::encrypt_3DES($orderId, base64_decode($this->merchantKey));

        return strtr(base64_encode(hash_hmac('sha256', $data, $key, true)), '+/', '-_') == $expectedSignature;
    }
}

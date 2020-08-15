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
        $data = [];
        $clave_encriptacion = $this->getParameter('clave_encriptacion');

        $data['MerchantID'] = $this->getParameter('MerchantID');
        $data['AcquirerBIN'] = $this->getParameter('AcquirerBIN');
        $data['TerminalID'] = $this->getParameter('TerminalID');

        $data['Num_operacion'] = $this->getParameter('Num_operacion');
        $data['Importe'] = $this->getAmount();
        $data['TipoMoneda'] = $this->getCurrencyCeca();
        $data['Exponente'] = $this->getParameter('Exponente');
        
        $data['URL_OK'] = $this->getParameter('URL_OK');
        $data['URL_NOK'] = $this->getParameter('URL_NOK');
        $data['Cifrado'] = $this->getParameter('Cifrado');
        $data['Idioma'] = $this->getParameter('Idioma');
        $data['Pago_soportado'] = $this->getParameter('Pago_soportado');
        $data['Descripcion'] = $this->getParameter('Descripcion');

        $data['Firma'] = $this->generateSignature($data, $clave_encriptacion);
        return $data;
    }

	 public function sendData($data)
	 {
	 	return $this->response = new CompletePurchaseResponse($this, $data);
	 }

    //public function sendData($data)
    //{
    //    $url = $this->getEndpoint();
    //    $httpResponse = $this->httpClient->post($url, null, $data)->send();
    //
    //    return $this->response = new CompletePurchaseResponse($this, $httpResponse->getStatusCode());

    //}
}
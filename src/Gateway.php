<?php

namespace Omnipay\Ceca;

use Symfony\Component\HttpFoundation\Request;
use Omnipay\Common\AbstractGateway;
use Omnipay\Ceca\Message\CallbackResponse;

/**
 * Ceca (Redsys) Gateway
 *
 * @author Javier Sampedro <jsampedro77@gmail.com>
 * @author NitsNets Studio <github@nitsnets.com>
 */
class Gateway extends AbstractGateway
{
    
    public function getName()
    {
        return 'Ceca';
    }

    public function getDefaultParameters()
    {
        return array(
            'MerchantID' => '',
            'AcquirerBIN' => '',
            'TerminalID' => '00000003',
            'TipoMoneda' => '978',
            'Exponente' => '2',
            'Idioma' => '1',
            'Cifrado' => 'SHA1',
            'Pago_soportado' => 'SSL',

            'testMode' => false

        );
    }

    //Set merchanID - required
    public function setMerchantID($MerchantID)
    {
        return $this->setParameter('MerchantID', $MerchantID);
    }
    //Set AcquirerBIN - required
    public function setAcquirerBIN($AcquirerBIN)
    {
        return $this->setParameter('AcquirerBIN', $AcquirerBIN);
    }   
    //Set TerminalID - required
    public function setTerminal($TerminalID)
    {
        return $this->setParameter('TerminalID', $TerminalID);
    }
    //Set TipoMoneda - required
    public function setTipoMoneda($TipoMoneda)
    {
        return $this->setParameter('TipoMoneda', $TipoMoneda);
    }
    //Set Idioma - required
    public function setIdioma($Idioma)
    {
        return $this->setParameter('Idioma', $Idioma);
    }
    //Set Idioma - required
    public function setClaveEncriptacion($clave_encriptacion)
    {
        return $this->setParameter('clave_encriptacion', $clave_encriptacion);
    }
    //Set Idioma - required
    public function setUrlOk($url)
    {
        return $this->setParameter('URL_OK', $url);
    }
    //Set Idioma - required
    public function setUrlNoOk($url)
    {
        return $this->setParameter('URL_NOK', $url);
    }
  

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Ceca\Message\PurchaseRequest', $parameters);
    }
    
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Ceca\Message\CompletePurchaseRequest', $parameters);
    }


    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function checkCallbackResponse(Request $request)
    {
        $response = new CallbackResponse($request, $this->getParameter('clave_encriptacion'));

        return $response->isSuccessful();
    }

    public function decodeCallbackResponse(Request $request)
    {
        return json_decode(base64_decode(strtr($request->get('Ds_MerchantParameters'), '-_', '+/')), true);
    }
}

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
        return [
            'MerchantID' => '',
            'AcquirerBIN' => '',
            'TerminalID' => '00000003',
            'TipoMoneda' => 'EUR',
            'Exponente' => '2',
            'Idioma' => '1',
            'Cifrado' => 'SHA2',
            'Pago_soportado' => 'SSL',

            'testMode' => false

        ];
    }

    //Set merchantID - required
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
    //Set Clave Encriptacion - required
    public function setClaveEncriptacion($clave_encriptacion)
    {
        return $this->setParameter('clave_encriptacion', $clave_encriptacion);
    }
    //Set UrlOk - required
    public function setUrlOk($url)
    {
        return $this->setParameter('URL_OK', $url);
    }
    //Set UrlNoOk - required
    public function setUrlNoOk($url)
    {
        return $this->setParameter('URL_NOK', $url);
    }
    public function setCurrencyMerchant($currency)
    {
        $this->setParameter('merchantCurrency', $currency);
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
    public function checkCallbackResponse(Request $request, $returnObject=false)
    {
        $response = new CallbackResponse($request, $this->getParameter('clave_encriptacion'));

        if($returnObject){
            return $response;
        }
        
        return $response->isSuccessful();
    }

    public function decodeCallbackResponse(Request $request)
    {

        $returnedParameters = [];
        $returnedParameters['MerchantID'] = $request->get('MerchantID');
        $returnedParameters['AcquirerBIN'] = $request->get('AcquirerBIN');
        $returnedParameters['TerminalID'] = $request->get('TerminalID');
        $returnedParameters['Num_operacion'] = $request->get('Num_operacion');
        $returnedParameters['Importe'] = $request->get('Importe');
        $returnedParameters['TipoMoneda'] = $request->get('TipoMoneda');
        $returnedParameters['Exponente'] = $request->get('Exponente');
        $returnedParameters['Referencia'] = $request->get('Referencia');
        $returnedParameters['Num_aut'] = $request->get('Num_aut');
        $returnedParameters['BIN'] = $request->get('BIN');
        $returnedParameters['FinalPAN'] = $request->get('FinalPAN');
        $returnedParameters['Cambio_moneda'] = $request->get('Cambio_moneda');
        $returnedParameters['Pais'] = $request->get('Pais');
        $returnedParameters['Tipo_tarjeta'] = $request->get('Tipo_tarjeta');
        $returnedParameters['Descripcion'] = $request->get('Descripcion');

        return $returnedParameters;
    }
}

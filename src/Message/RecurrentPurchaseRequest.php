<?php

namespace Omnipay\Ceca\Message;

/**
 * Ceca (Redsys) Recurrent Purchase Request
 *
 * @author Javier Sampedro <jsampedro77@gmail.com>
 */
class RecurrentPurchaseRequest extends PurchaseRequest
{

    public function getTransactionType()
    {
        return 'L'; //L for initial transaction M for recurrent
    }
}

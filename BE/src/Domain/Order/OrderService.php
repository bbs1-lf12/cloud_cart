<?php

declare(strict_types=1);

namespace App\Domain\Order;

use App\Domain\Order\Entity\Order;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

class OrderService
{
    public function __construct(
        private readonly Security $security,
    ) {
    }

    public function placeOrder(
        Request $request,
    ): Order {
        $currentUser = $this->security
            ->getUser()
        ;

        // get the cart
        // validate if cart exists
        // if not, throw api exception

        // create the order
        // assign the cart to the order
        // close the cart
        // get the billing address
        // get the shipping address
        // calculate the total

        // set status to pending
        // save the order
    }
}

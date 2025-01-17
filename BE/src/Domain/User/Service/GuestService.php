<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\Api\Exceptions\ApiException;
use App\Domain\User\Entity\Guest;
use Symfony\Component\HttpFoundation\Request;

class GuestService
{
    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    public function getGuestInformationFromRequest(
        Request $request,
    ): Guest
    {
        $payload = $request->getPayload();
        $guest = new Guest();

        $email = $payload->get('email');
        $billingAddress = $payload->get('billing_address');
        $shippingAddress = $payload->get('shipping_address');

        if (
            empty($email)
            || !filter_var(
                $email,
                FILTER_VALIDATE_EMAIL,
            )
            || empty($billingAddress)
            || empty($shippingAddress)
        ) {
            throw new ApiException(
                'Invalid guest information',
                400,
            );
        }

        $guest->setEmail($email);
        $guest->setBillingAddress($billingAddress);
        $guest->setShippingAddress($shippingAddress);

        return $guest;
    }
}

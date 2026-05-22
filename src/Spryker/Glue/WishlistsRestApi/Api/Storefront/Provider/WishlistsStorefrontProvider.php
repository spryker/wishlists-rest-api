<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\WishlistsRestApi\Api\Storefront\Provider;

use Generated\Api\Storefront\WishlistsStorefrontResource;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WishlistFilterTransfer;
use Spryker\ApiPlatform\State\Provider\AbstractStorefrontProvider;
use Spryker\Client\Wishlist\WishlistClientInterface;
use Spryker\Glue\WishlistsRestApi\Api\Storefront\Exception\WishlistsExceptionFactory;
use Spryker\Glue\WishlistsRestApi\Api\Storefront\Mapper\WishlistsResourceMapper;

class WishlistsStorefrontProvider extends AbstractStorefrontProvider
{
    protected const string KEY_UUID = 'uuid';

    public function __construct(
        protected WishlistClientInterface $wishlistClient,
        protected WishlistsResourceMapper $wishlistsResourceMapper,
        protected WishlistsExceptionFactory $exceptionFactory,
    ) {
    }

    /**
     * @return array<\Generated\Api\Storefront\WishlistsStorefrontResource>
     */
    protected function provideCollection(): array
    {
        $customerReference = (string)$this->getCustomer()->getCustomerReference();

        $wishlistCollectionTransfer = $this->wishlistClient->getWishlistCollection(
            (new CustomerTransfer())->setCustomerReference($customerReference),
        );

        $resources = [];

        foreach ($wishlistCollectionTransfer->getWishlists() as $wishlistTransfer) {
            $resources[] = $this->wishlistsResourceMapper->mapWishlistTransferToResource(
                $wishlistTransfer,
                new WishlistsStorefrontResource(),
            );
        }

        return $resources;
    }

    protected function provideItem(): ?object
    {
        $wishlistResponseTransfer = $this->wishlistClient->getWishlistByFilter(
            (new WishlistFilterTransfer())
                ->setIdCustomer($this->getCustomer()->getIdCustomerOrFail())
                ->setUuid((string)$this->getUriVariable(static::KEY_UUID)),
        );

        if (!$wishlistResponseTransfer->getIsSuccess()) {
            throw $this->exceptionFactory->createWishlistNotFoundException();
        }

        $wishlistTransfer = $wishlistResponseTransfer->getWishlist();

        if ($wishlistTransfer === null) {
            throw $this->exceptionFactory->createWishlistNotFoundException();
        }

        return $this->wishlistsResourceMapper->mapWishlistTransferToResource(
            $wishlistTransfer,
            new WishlistsStorefrontResource(),
        );
    }
}

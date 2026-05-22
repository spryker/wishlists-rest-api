<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\WishlistsRestApi\Api\Storefront\Processor;

use Generated\Api\Storefront\WishlistsStorefrontResource;
use Generated\Shared\Transfer\WishlistFilterTransfer;
use Generated\Shared\Transfer\WishlistRequestTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\ApiPlatform\State\Processor\AbstractStorefrontProcessor;
use Spryker\Client\Wishlist\WishlistClientInterface;
use Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface;
use Spryker\Glue\WishlistsRestApi\Api\Storefront\Exception\WishlistsExceptionFactory;
use Spryker\Glue\WishlistsRestApi\Api\Storefront\Mapper\WishlistsResourceMapper;

class WishlistsStorefrontProcessor extends AbstractStorefrontProcessor
{
    protected const string KEY_UUID = 'uuid';

    public function __construct(
        protected WishlistClientInterface $wishlistClient,
        protected WishlistsRestApiClientInterface $wishlistsRestApiClient,
        protected WishlistsResourceMapper $wishlistsResourceMapper,
        protected WishlistsExceptionFactory $exceptionFactory,
    ) {
    }

    /**
     * @param \Generated\Api\Storefront\WishlistsStorefrontResource $data
     *
     * @throws \Spryker\ApiPlatform\Exception\GlueApiException
     *
     * @return \Generated\Api\Storefront\WishlistsStorefrontResource
     */
    protected function processPost(mixed $data): mixed
    {
        $wishlistTransfer = $this->wishlistsResourceMapper
            ->mapResourceToWishlistTransfer($data, new WishlistTransfer())
            ->setFkCustomer($this->getCustomer()->getIdCustomerOrFail());

        $wishlistResponseTransfer = $this->wishlistClient->validateAndCreateWishlist($wishlistTransfer);

        if (!$wishlistResponseTransfer->getIsSuccess()) {
            $errors = $wishlistResponseTransfer->getErrors();

            if (count($errors) > 0) {
                throw $this->exceptionFactory->createExceptionFromRawError((string)$errors[0]);
            }

            throw $this->exceptionFactory->createUnknownErrorException();
        }

        $createdWishlistTransfer = $wishlistResponseTransfer->getWishlist();

        if ($createdWishlistTransfer === null) {
            throw $this->exceptionFactory->createUnknownErrorException();
        }

        return $this->wishlistsResourceMapper->mapWishlistTransferToResource(
            $createdWishlistTransfer,
            new WishlistsStorefrontResource(),
        );
    }

    /**
     * @param \Generated\Api\Storefront\WishlistsStorefrontResource $data
     *
     * @throws \Spryker\ApiPlatform\Exception\GlueApiException
     *
     * @return \Generated\Api\Storefront\WishlistsStorefrontResource
     */
    protected function processPatch(mixed $data): mixed
    {
        $wishlistTransfer = $this->wishlistsResourceMapper
            ->mapResourceToWishlistTransfer($data, new WishlistTransfer());

        $wishlistRequestTransfer = (new WishlistRequestTransfer())
            ->setUuid((string)$this->getUriVariable(static::KEY_UUID))
            ->setIdCustomer($this->getCustomer()->getIdCustomerOrFail())
            ->setWishlist($wishlistTransfer);

        $wishlistResponseTransfer = $this->wishlistsRestApiClient->updateWishlist($wishlistRequestTransfer);

        if (!$wishlistResponseTransfer->getIsSuccess()) {
            throw $this->exceptionFactory->createExceptionFromErrorIdentifier(
                (string)$wishlistResponseTransfer->getErrorIdentifier(),
            );
        }

        $updatedWishlistTransfer = $wishlistResponseTransfer->getWishlist();

        if ($updatedWishlistTransfer === null) {
            throw $this->exceptionFactory->createUnknownErrorException();
        }

        return $this->wishlistsResourceMapper->mapWishlistTransferToResource(
            $updatedWishlistTransfer,
            new WishlistsStorefrontResource(),
        );
    }

    protected function processDelete(): mixed
    {
        $wishlistResponseTransfer = $this->wishlistsRestApiClient->deleteWishlist(
            (new WishlistFilterTransfer())
                ->setUuid((string)$this->getUriVariable(static::KEY_UUID))
                ->setIdCustomer($this->getCustomer()->getIdCustomerOrFail()),
        );

        if (!$wishlistResponseTransfer->getIsSuccess()) {
            throw $this->exceptionFactory->createExceptionFromErrorIdentifier(
                (string)$wishlistResponseTransfer->getErrorIdentifier(),
            );
        }

        return null;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\WishlistsRestApi\Api\Storefront\Processor;

use Generated\Api\Storefront\WishlistItemsStorefrontResource;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Spryker\ApiPlatform\State\Processor\AbstractStorefrontProcessor;
use Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface;
use Spryker\Glue\WishlistsRestApi\Api\Storefront\Exception\WishlistsExceptionFactory;
use Spryker\Glue\WishlistsRestApi\Api\Storefront\Mapper\WishlistsResourceMapper;

class WishlistItemsStorefrontProcessor extends AbstractStorefrontProcessor
{
    protected const string KEY_WISHLIST_UUID = 'wishlistUuid';

    protected const string KEY_UUID = 'uuid';

    public function __construct(
        protected WishlistsRestApiClientInterface $wishlistsRestApiClient,
        protected WishlistsResourceMapper $wishlistsResourceMapper,
        protected WishlistsExceptionFactory $exceptionFactory,
    ) {
    }

    /**
     * @param \Generated\Api\Storefront\WishlistItemsStorefrontResource $data
     *
     * @throws \Spryker\ApiPlatform\Exception\GlueApiException
     *
     * @return \Generated\Api\Storefront\WishlistItemsStorefrontResource
     */
    protected function processPost(mixed $data): mixed
    {
        $wishlistUuid = (string)$this->getUriVariable(static::KEY_WISHLIST_UUID);

        if ($wishlistUuid === '') {
            throw $this->exceptionFactory->createWishlistNotFoundException();
        }

        $wishlistItemRequestTransfer = (new WishlistItemRequestTransfer())
            ->setIdCustomer($this->getCustomer()->getIdCustomerOrFail())
            ->setUuidWishlist($wishlistUuid);

        $wishlistItemRequestTransfer = $this->wishlistsResourceMapper
            ->mapAttributesArrayToWishlistItemRequestTransfer($this->getRawAttributes(), $wishlistItemRequestTransfer);

        $wishlistItemResponseTransfer = $this->wishlistsRestApiClient->addWishlistItem($wishlistItemRequestTransfer);

        if (!$wishlistItemResponseTransfer->getIsSuccess()) {
            throw $this->exceptionFactory->createCantAddItemException();
        }

        $wishlistItemTransfer = $wishlistItemResponseTransfer->getWishlistItem();

        if ($wishlistItemTransfer === null) {
            throw $this->exceptionFactory->createCantAddItemException();
        }

        return $this->wishlistsResourceMapper->mapWishlistItemTransferToResource(
            $wishlistItemTransfer,
            new WishlistItemsStorefrontResource(),
            $wishlistUuid,
        );
    }

    /**
     * @param \Generated\Api\Storefront\WishlistItemsStorefrontResource $data
     *
     * @throws \Spryker\ApiPlatform\Exception\GlueApiException
     *
     * @return \Generated\Api\Storefront\WishlistItemsStorefrontResource
     */
    protected function processPatch(mixed $data): mixed
    {
        $wishlistItemRequestTransfer = (new WishlistItemRequestTransfer())
            ->setIdCustomer($this->getCustomer()->getIdCustomerOrFail())
            ->setUuidWishlist((string)$this->getUriVariable(static::KEY_WISHLIST_UUID))
            ->setUuid((string)$this->getUriVariable(static::KEY_UUID));

        $wishlistItemRequestTransfer = $this->wishlistsResourceMapper
            ->mapAttributesArrayToWishlistItemRequestTransfer($this->getRawAttributes(), $wishlistItemRequestTransfer);

        $wishlistItemResponseTransfer = $this->wishlistsRestApiClient->updateWishlistItem($wishlistItemRequestTransfer);

        if (!$wishlistItemResponseTransfer->getIsSuccess()) {
            $errorIdentifier = (string)$wishlistItemResponseTransfer->getErrorIdentifier();

            if ($errorIdentifier === '') {
                throw $this->exceptionFactory->createUnknownErrorException();
            }

            throw $this->exceptionFactory->createExceptionFromErrorIdentifier($errorIdentifier);
        }

        $wishlistItemTransfer = $wishlistItemResponseTransfer->getWishlistItem();

        if ($wishlistItemTransfer === null) {
            throw $this->exceptionFactory->createUnknownErrorException();
        }

        return $this->wishlistsResourceMapper->mapWishlistItemTransferToResource(
            $wishlistItemTransfer,
            new WishlistItemsStorefrontResource(),
            (string)$this->getUriVariable(static::KEY_WISHLIST_UUID),
        );
    }

    protected function processDelete(): mixed
    {
        $wishlistItemRequestTransfer = (new WishlistItemRequestTransfer())
            ->setIdCustomer($this->getCustomer()->getIdCustomerOrFail())
            ->setUuidWishlist((string)$this->getUriVariable(static::KEY_WISHLIST_UUID))
            ->setUuid((string)$this->getUriVariable(static::KEY_UUID));

        $wishlistItemResponseTransfer = $this->wishlistsRestApiClient->deleteWishlistItem($wishlistItemRequestTransfer);

        if (!$wishlistItemResponseTransfer->getIsSuccess()) {
            throw $this->exceptionFactory->createExceptionFromErrorIdentifier(
                (string)$wishlistItemResponseTransfer->getErrorIdentifier(),
            );
        }

        return null;
    }

    /**
     * Reads `data.attributes` from the raw JSON:API request body. Used to pass through composed
     * properties (e.g. `configuration`, `productOfferReference`) that are contributed by other
     * modules to `RestWishlistItemsAttributesTransfer` but are not declared on this module's YAML
     * resource — letting the legacy `WishlistItemRequestMapperPluginInterface` plugin chain see them.
     *
     * @return array<string, mixed>
     */
    protected function getRawAttributes(): array
    {
        $content = (string)$this->getRequest()->getContent();

        if ($content === '') {
            return [];
        }

        $decoded = json_decode($content, true);

        if (!is_array($decoded)) {
            return [];
        }

        $attributes = $decoded['data']['attributes'] ?? [];

        return is_array($attributes) ? $attributes : [];
    }
}

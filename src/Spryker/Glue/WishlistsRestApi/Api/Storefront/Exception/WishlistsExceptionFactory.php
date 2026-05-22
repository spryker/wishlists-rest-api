<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\WishlistsRestApi\Api\Storefront\Exception;

use Spryker\ApiPlatform\Exception\GlueApiException;
use Spryker\Glue\WishlistsRestApi\WishlistsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

/**
 * Builds pre-configured `GlueApiException` instances for known wishlist scenarios.
 *
 * Uses {@see WishlistsRestApiConfig::getErrorIdentifierToRestErrorMapping()} as the source of
 * truth for `errorIdentifier → [code, status, detail]` translation; falls back to "unknown
 * error" (200/422) when no mapping matches, preserving legacy contract.
 */
class WishlistsExceptionFactory
{
    protected const string KEY_STATUS = 'status';

    protected const string KEY_CODE = 'code';

    protected const string KEY_DETAIL = 'detail';

    // Raw error strings produced by Wishlist Zed Writer (not ERROR_IDENTIFIER_* constants)
    protected const string RAW_ERROR_NAME_ALREADY_EXISTS = 'wishlist.validation.error.name.already_exists';

    protected const string RAW_ERROR_NAME_WRONG_FORMAT = 'wishlist.validation.error.name.wrong_format';

    public function __construct(
        protected WishlistsRestApiConfig $wishlistsRestApiConfig,
    ) {
    }

    public function createWishlistNotFoundException(): GlueApiException
    {
        return new GlueApiException(
            Response::HTTP_NOT_FOUND,
            WishlistsRestApiConfig::RESPONSE_CODE_WISHLIST_NOT_FOUND,
            WishlistsRestApiConfig::RESPONSE_DETAIL_WISHLIST_NOT_FOUND,
        );
    }

    public function createWishlistNameInvalidException(): GlueApiException
    {
        return new GlueApiException(
            Response::HTTP_BAD_REQUEST,
            WishlistsRestApiConfig::RESPONSE_CODE_WISHLIST_NAME_INVALID,
            WishlistsRestApiConfig::RESPONSE_DETAIL_WISHLIST_NAME_INVALID,
        );
    }

    public function createWishlistAlreadyExistsException(): GlueApiException
    {
        return new GlueApiException(
            Response::HTTP_BAD_REQUEST,
            WishlistsRestApiConfig::RESPONSE_CODE_WISHLIST_WITH_SAME_NAME_ALREADY_EXISTS,
            WishlistsRestApiConfig::RESPONSE_DETAIL_WISHLIST_WITH_SAME_NAME_ALREADY_EXISTS,
        );
    }

    public function createCantAddItemException(): GlueApiException
    {
        return new GlueApiException(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            WishlistsRestApiConfig::RESPONSE_CODE_WISHLIST_CANT_ADD_ITEM,
            WishlistsRestApiConfig::RESPONSE_DETAIL_WISHLIST_CANT_ADD_ITEM,
        );
    }

    public function createUnknownErrorException(): GlueApiException
    {
        return new GlueApiException(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            WishlistsRestApiConfig::RESPONSE_CODE_WISHLIST_UNKNOWN_ERROR,
            WishlistsRestApiConfig::RESPONSE_DETAIL_WISHLIST_UNKNOWN_ERROR,
        );
    }

    /**
     * Maps raw error strings from the Wishlist Zed Writer (e.g. `'wishlist.validation.error.name.wrong_format'`)
     * to the correct exception. These strings are NOT ERROR_IDENTIFIER_* constants — they come from
     * `WishlistResponseTransfer::getErrors()` populated by the base Wishlist module.
     */
    public function createExceptionFromRawError(string $rawError): GlueApiException
    {
        return match ($rawError) {
            static::RAW_ERROR_NAME_WRONG_FORMAT => $this->createWishlistNameInvalidException(),
            static::RAW_ERROR_NAME_ALREADY_EXISTS => $this->createWishlistAlreadyExistsException(),
            default => $this->createUnknownErrorException(),
        };
    }

    public function createExceptionFromErrorIdentifier(string $errorIdentifier): GlueApiException
    {
        $mapping = $this->wishlistsRestApiConfig->getErrorIdentifierToRestErrorMapping();

        if (!isset($mapping[$errorIdentifier])) {
            return $this->createUnknownErrorException();
        }

        $mappedError = $mapping[$errorIdentifier];

        return new GlueApiException(
            (int)$mappedError[static::KEY_STATUS],
            (string)$mappedError[static::KEY_CODE],
            (string)$mappedError[static::KEY_DETAIL],
        );
    }
}

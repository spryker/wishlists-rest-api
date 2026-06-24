<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\WishlistsRestApi\WishlistsRestApiConfig as SharedWishlistRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class WishlistsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @var string
     */
    public const RESOURCE_WISHLISTS = 'wishlists';

    /**
     * @api
     *
     * @var string
     */
    public const RESOURCE_WISHLIST_ITEMS = 'wishlist-items';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_WISHLIST_UNKNOWN_ERROR = '200';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_WISHLIST_NOT_FOUND = '201';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_WISHLIST_WITH_SAME_NAME_ALREADY_EXISTS = '202';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_WISHLIST_CANT_CREATE_WISHLIST = '203';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_WISHLIST_CANT_UPDATE_WISHLIST = '204';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_WISHLIST_CANT_DELETE_WISHLIST = '205';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_WISHLIST_CANT_ADD_ITEM = '206';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_NO_ITEM_WITH_PROVIDED_ID = '208';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_ID_IS_NOT_SPECIFIED = '209';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_WISHLIST_NAME_INVALID = '210';

    /**
     * @api
     *
     * @uses \Spryker\Glue\AuthRestApi\AuthRestApiConfig::RESPONSE_CODE_FORBIDDEN
     *
     * @var string
     */
    public const RESPONSE_CODE_FORBIDDEN = '002';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_DETAIL_WISHLIST_UNKNOWN_ERROR = 'Unknown error.';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_DETAIL_WISHLIST_NOT_FOUND = 'Can\'t find wishlist.';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_DETAIL_WISHLIST_WITH_SAME_NAME_ALREADY_EXISTS = 'A wishlist with the same name already exists.';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_DETAIL_WISHLIST_CANT_ADD_ITEM = 'Can\'t add an item.';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_DETAIL_NO_ITEM_WITH_PROVIDED_SKU = 'No item with provided sku in wishlist.';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_DETAIL_ID_IS_NOT_SPECIFIED = 'Id is not specified.';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_DETAIL_WISHLIST_NAME_INVALID = 'Please enter name using only letters, numbers, underscores, spaces or dashes.';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_DETAIL_WISHLIST_CANT_BE_UPDATED = 'Can\'t update wishlist';

    /**
     * @api
     *
     * @uses \Spryker\Glue\AuthRestApi\AuthRestApiConfig::RESPONSE_DETAIL_MISSING_ACCESS_TOKEN
     *
     * @var string
     */
    public const RESPONSE_DETAIL_MISSING_ACCESS_TOKEN = 'Missing access token.';

    /**
     * @api
     *
     * @return array<string, array<string, mixed>>
     */
    public function getErrorIdentifierToRestErrorMapping(): array
    {
        return [
            SharedWishlistRestApiConfig::ERROR_IDENTIFIER_WISHLIST_NOT_FOUND => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_WISHLIST_NOT_FOUND,
                RestErrorMessageTransfer::STATUS => Response::HTTP_NOT_FOUND,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_WISHLIST_NOT_FOUND,
            ],
            SharedWishlistRestApiConfig::ERROR_IDENTIFIER_WISHLIST_NAME_ALREADY_EXIST => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_WISHLIST_WITH_SAME_NAME_ALREADY_EXISTS,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_WISHLIST_WITH_SAME_NAME_ALREADY_EXISTS,
            ],
            SharedWishlistRestApiConfig::ERROR_IDENTIFIER_WISHLIST_NAME_WRONG_FORMAT => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_WISHLIST_NAME_INVALID,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_WISHLIST_NAME_INVALID,
            ],
            SharedWishlistRestApiConfig::ERROR_IDENTIFIER_WISHLIST_CANT_BE_UPDATED => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_WISHLIST_CANT_UPDATE_WISHLIST,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_WISHLIST_CANT_BE_UPDATED,
            ],
            SharedWishlistRestApiConfig::ERROR_IDENTIFIER_ITEM_WITH_SKU_NOT_FOUND_IN_WISHLIST => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_NO_ITEM_WITH_PROVIDED_ID,
                RestErrorMessageTransfer::STATUS => Response::HTTP_NOT_FOUND,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_NO_ITEM_WITH_PROVIDED_SKU,
            ],
            SharedWishlistRestApiConfig::ERROR_IDENTIFIER_WISHLIST_ITEM_CANT_BE_ADDED => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_WISHLIST_CANT_ADD_ITEM,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_WISHLIST_CANT_ADD_ITEM,
            ],
        ];
    }
}

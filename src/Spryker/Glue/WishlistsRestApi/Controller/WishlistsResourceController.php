<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Controller;

use Generated\Shared\Transfer\RestWishlistsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\WishlistsRestApi\WishlistsRestApiFactory getFactory()
 */
class WishlistsResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves wishlist data by id."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "404": "Wishlist not found.",
     *              "422": "Unprocessable entity."
     *          }
     *     },
     *     "getCollection": {
     *          "summary": [
     *              "Retrieves all customer wishlists."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }]
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        return $this->getFactory()
            ->createWishlistReader()
            ->findWishlists($restRequest);
    }

    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Creates wishlist."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "400": "Wishlist with the same name already exists, Wishlist name has wrong format.",
     *              "422": "Unprocessable entity."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestWishlistsAttributesTransfer $restWishlistsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(RestRequestInterface $restRequest, RestWishlistsAttributesTransfer $restWishlistsAttributesTransfer): RestResponseInterface
    {
        return $this->getFactory()
            ->createWishlistCreator()
            ->create($restWishlistsAttributesTransfer, $restRequest);
    }

    /**
     * @Glue({
     *     "patch": {
     *          "summary": [
     *              "Updates customer wishlist."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "400": "A wishlist with the same name already exists.",
     *              "404": "Wishlist not found.",
     *              "422": "Unprocessable entity."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestWishlistsAttributesTransfer $restWishlistsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function patchAction(RestRequestInterface $restRequest, RestWishlistsAttributesTransfer $restWishlistsAttributesTransfer): RestResponseInterface
    {
        return $this->getFactory()
            ->createWishlistUpdater()
            ->update($restWishlistsAttributesTransfer, $restRequest);
    }

    /**
     * @Glue({
     *     "delete": {
     *          "summary": [
     *              "Removes customer wishlist."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "404": "Wishlist not found.",
     *              "422": "Unprocessable entity."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function deleteAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        return $this->getFactory()
            ->createWishlistDeleter()
            ->delete($restRequest);
    }
}

<?php

namespace huqis\resource;

/**
 * Interface to lookup template resources
 */
interface TemplateResourceHandler {

    /**
     * Gets a resource by the provided name
     * @param string $name Name of a template resource. This is the name which
     * is passed as resource to the template engine and used in extends and
     * include blocks
     * @return string Contents of the template.
     * @throws \huqis\exception\NotFoundTemplateException when the
     * resource does not exist
     */
    public function getResource($name);

    /**
     * Gets the modification time of the provided resource
     * @param string $name Name of the template resource as used in getResource
     * @return integer|null Timestamp of the modification time or null when
     * unknown
     * @throws \huqis\exception\NotFoundTemplateException when the
     * resource does not exist
     * @see getResource
     */
    public function getModificationTime($name);

    /**
     * Gets and resets the requested resources
     * @return array Array with a template resource name as key
     */
    public function getRequestedResources();

}

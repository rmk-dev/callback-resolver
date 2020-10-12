<?php
/**
 * CallbackResolver class
 *
 * @category CallbackResolver
 * @package  Rmk\Lib\Resolver
 * @author   Kiril Savchev <k.savchev@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.rakiika.com/
 *
 */
namespace Rmk\CallbackResolver;

use Psr\Container\ContainerInterface as PsrContainer;

/**
 * Class CallbackResolver
 *
 * @package Rmk\Lib\Resolver
 */
class CallbackResolver
{

    /**
     * Service container
     *
     * @var PsrContainer
     */
    protected $container;

    /**
     * CallbackResolver constructor.
     *
     * @param PsrContainer $container Container with registered services
     */
    public function __construct(PsrContainer $container)
    {
        $this->setContainer($container);
    }

    /**
     * Resolve the provided parameter as a ready-to-use callable value
     *
     * If an array is passed as a parameter it must contains exactly 2 string elements. The first one will be used as
     * service name and the resolver will attempt to retrieve it from the service container. If no such service is
     * registered in the container, the resolver will try to use the element as a class name for new object. If class
     * with such name does not exists, the resolver will throw ResolverException. No additional argument will be passed
     * to the class constructor. The second array element will be used as object's method. If no such method exists, the
     * resolver will throw ResolverException. Otherwise it will return an array with the service object and its method.
     * If the passed parameter is callback it will be returned as-is.
     * If the provided parameter is not two-elements array, neither a callback, a ResolverException is thrown.
     *
     * @param array|callable $potentialCallback Potential callback value
     *
     * @return callable Resolved callback with ready-to-use service (if any)
     *
     * @throws ResolverException If the passed parameter is not in correct form
     */
    public function resolve($potentialCallback): callable
    {
        if (is_string($potentialCallback) && !is_callable($potentialCallback)) {
            $potentialCallback = [$potentialCallback];
        }

        if (is_array($potentialCallback) && count($potentialCallback) >= 1) {
            $first = array_shift($potentialCallback);
            if (!is_string($first) && !is_object($first)) {
                throw new ResolverException(ResolverException::INVALID_ARRAY_ELEMENTS);
            }

            if (is_object($first)) {
                $service = $first;
            } elseif ($this->getContainer()->has($first)) {
                $service = $this->getContainer()->get($first);
            } elseif (class_exists($first)) {
                $service = new $first();
            } else {
                throw new ResolverException(ResolverException::INVALID_OBJECT_ELEMENT);
            }

            if (count($potentialCallback)) {
                $method = array_shift($potentialCallback);
                if (method_exists($service, $method)) {
                    return [$service, $method];
                }
                throw new ResolverException(ResolverException::INVALID_METHOD_ELEMENT);
            }

            return $service;
        }

        if (is_callable($potentialCallback)) {
            return $potentialCallback;
        }

        throw new ResolverException(ResolverException::INVALID_PARAMETER_TYPE);
    }

    /**
     * Container getter
     *
     * @return PsrContainer
     */
    public function getContainer(): PsrContainer
    {
        return $this->container;
    }

    /**
     * Container setter
     *
     * @param PsrContainer $container
     *
     * @return CallbackResolver
     */
    public function setContainer(PsrContainer $container): self
    {
        $this->container = $container;

        return $this;
    }
}

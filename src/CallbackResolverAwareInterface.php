<?php
/**
 * CallbackResolverAwareInterface class
 *
 * @category CallbackResolverAwareInterface
 * @package  Rmk\Lib\Resolver
 * @author   Kiril Savchev <k.savchev@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.rakiika.com/
 *
 */
namespace Terry\CallbackResolver;

/**
 * Interface CallbackResolverAwareInterface
 *
 * @package Rmk\Lib\Resolver
 */
interface CallbackResolverAwareInterface
{

    /**
     * @return CallbackResolver
     */
    public function getCallbackResolver(): CallbackResolver;

    /**
     * @param CallbackResolver $callbackResolver
     *
     * @return self
     */
    public function setCallbackResolver(CallbackResolver $callbackResolver);
}

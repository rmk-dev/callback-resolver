<?php
/**
 * CallbackResolverAwareTrait class
 *
 * @category CallbackResolverAwareTrait
 * @package  Terry\Lib\Resolver
 * @author   Kiril Savchev <k.savchev@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.rakiika.com/
 *
 */
namespace Terry\CallbackResolver;

/**
 * Trait CallbackResolverAwareTrait
 *
 * @package Terry\Lib\Resolver
 */
trait CallbackResolverAwareTrait
{

    /**
     * @var CallbackResolver
     */
    protected $callbackResolver;

    /**
     * @return CallbackResolver
     */
    public function getCallbackResolver(): CallbackResolver
    {
        return $this->callbackResolver;
    }

    /**
     * @param CallbackResolver $callbackResolver
     *
     * @return self
     */
    public function setCallbackResolver(CallbackResolver $callbackResolver)
    {
        $this->callbackResolver = $callbackResolver;

        return $this;
    }
}

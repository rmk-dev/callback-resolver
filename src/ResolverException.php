<?php
/**
* ResolverException class
*
* @category ResolverException
* @package  Rmk\Lib\Resolver
* @author   Kiril Savchev <k.savchev@gmail.com>
* @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
* @link     http://www.rakiika.com/
*
*/
namespace Terry\CallbackResolver;

use InvalidArgumentException;
use Throwable;

/**
 * Class ResolverException
 *
 * @package Terry\CallbackResolver
 */
class ResolverException extends InvalidArgumentException
{

    public const UNKNOWN_ERROR = 0;

    public const INVALID_PARAMETER_TYPE = 1;

    public const INVALID_ARRAY_ELEMENTS = 2;

    public const INVALID_OBJECT_ELEMENT = 3;

    public const INVALID_METHOD_ELEMENT = 4;

    public const ERROR_MESSAGES = [
        self::UNKNOWN_ERROR => 'An error occurred while resolving callbacks',
        self::INVALID_PARAMETER_TYPE => 'Provided parameter must be either a callable or array with exactly 2 string elements',
        self::INVALID_ARRAY_ELEMENTS => 'Elements of the callback array must be two strings',
        self::INVALID_OBJECT_ELEMENT => 'The first array element must be registered service name or class name',
        self::INVALID_METHOD_ELEMENT => 'No such method in the resolved service',
    ];

    public function __construct($code = 0, Throwable $previous = null)
    {
        $message = self::ERROR_MESSAGES[$code] ?? self::ERROR_MESSAGES[self::UNKNOWN_ERROR];
        parent::__construct($message, $code, $previous);
    }
}

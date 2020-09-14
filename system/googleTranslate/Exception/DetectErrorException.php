<?php

namespace GoogleTranslate\Exception;

/**
 * Google Translate API PHP Client
 *
 * @link https://github.com/viniciusgava/google-translate-php-client
 * @license http://www.gnu.org/copyleft/gpl.html
 * @author Vinicius Gava (gava.vinicius@gmail.com)
 */
class DetectErrorException extends \DomainException
{
    /** @inheritdoc */
    public function __construct(
        $message = 'Detect Error',
        $code = 6,
        \Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}

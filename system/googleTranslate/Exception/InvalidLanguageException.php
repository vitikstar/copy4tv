<?php

namespace GoogleTranslate\Exception;

/**
 * Google Translate API PHP Client
 *
 * @link https://github.com/viniciusgava/google-translate-php-client
 * @license http://www.gnu.org/copyleft/gpl.html
 * @author Vinicius Gava (gava.vinicius@gmail.com)
 */
class InvalidLanguageException extends \InvalidArgumentException
{
    /** @inheritdoc */
    public function __construct(
        $message = 'Invalid language',
        $code = 3,
        \Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}

<?php

namespace App\Controller\Validation;

class Validator
{
    const ACCOUNT_HAS_BEEN_CREATED = 'Account has been created';
    const SOMETHING_WENT_WRONG = 'Something went terribly wrong. Our gerbils are working hard to solve the problem';
    const UNABLE_TO_CREATE_ACCOUNT = 'Unable to create account';

    const VALIDATE_USER_NOT_FOUND = 'Unable to login';
    const VALIDATE_INVALID_DOMAIN = 'Invalid email address (domain)';
    const VALIDATE_INVALID_EMAIL = 'Invalid email address';
    const VALIDATE_INVALID_PASSWORD = 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.';
    const VALIDATE_INVALID_PASSWORD_REPEAT = 'Please enter the same password twice';

    /**
     * returns empty message when valid.
     *
     * @param string $userName
     * @param string $userEmail
     * @param $userPassword
     * @param $userPasswordRepeat
     * @return string
     */
    public function validateRegistration(string $userName, string $userEmail, $userPassword, $userPasswordRepeat): string
    {
        if ($userPassword != $userPasswordRepeat) {
            // In case someone disabled Javascript.
            return self::VALIDATE_INVALID_PASSWORD_REPEAT;
        }

        // Some basic checks password todo:
        // Password needs to be at least 8 characters
        // Password needs to have at least one number, caps or non number
        if (!$this->validatePassword($userPassword)) {
            return self::VALIDATE_INVALID_PASSWORD;
        }

        // some basic checks email: xyz@abc.com.
        // check @ and . and extension. Ow, this is also in the filter validate email filters.
        if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            return self::VALIDATE_INVALID_EMAIL;
        }

        $domainParts = explode("@", $userEmail, 2);

        if (isset($domainParts[1]) and !$this->isValidDns($domainParts[1])) {
            return self::VALIDATE_INVALID_DOMAIN;
        }

        // Name validation.
        // Todo: come up with an idea... I have non at the moment.

        // Everything is fine, return empty message
        return '';
    }

    /**
     * @param string $domain
     * @return bool
     */
    private function isValidDns(string $domain)
    {
        return checkdnsrr($domain);
    }

    /**
     * @param string $userPassword
     * @return bool
     */
    private function validatePassword(string $userPassword): bool
    {
        $uppercase = preg_match('@[A-Z]@', $userPassword);
        $lowercase = preg_match('@[a-z]@', $userPassword);
        $number    = preg_match('@[0-9]@', $userPassword);
        $specialChars = preg_match('@[^\w]@', $userPassword);
        if (!$uppercase or !$lowercase or !$number or !$specialChars or strlen($userPassword) < 8) {
            return false;
        }

        return true;
    }
}

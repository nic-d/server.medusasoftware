<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 25/03/2018
 * Time: 09:28
 */

namespace User\Validator;

use User\Entity\User;
use Zend\Validator\AbstractValidator;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class UserExistsValidator
 * @package User\Validator
 */
class UserExistsValidator extends AbstractValidator
{
    /** @var array $options */
    protected $options = [
        'entityManager' => null,
        'user'          => null,
    ];

    protected $messageTemplates = [
        self::NOT_SCALAR => 'The email must be a scalar value',
        self::USER_EXISTS => 'Email already exists',
    ];

    const NOT_SCALAR = 'notScalar';
    const USER_EXISTS = 'userExists';

    /**
     * UserExistsValidator constructor.
     * @param array $options
     */
    public function __construct($options = [])
    {
        if (empty($options)) {
            if (isset($options['entityManager'])) {
                $this->options['entityManager'] = $options['entityManager'];
            } else {
                $this->options['user'] = $options['user'];
            }
        }

        parent::__construct($options);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        if (!is_scalar($value)) {
            $this->error(self::NOT_SCALAR);
            return false;
        }

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->options['entityManager'];

        /** @var User $user */
        $user = $entityManager->getRepository(User::class)
            ->findOneBy([
                'email' => $value,
            ]);

        if (is_null($this->options['user'])) {
            $isValid = ($user === null);
        } else {
            if ($this->options['user']->getEmail() !== $value && !is_null($user)) {
                $isValid = false;
            } else {
                $isValid = true;
            }
        }

        if (!$isValid) {
            $this->error(self::USER_EXISTS);
        }

        return $isValid;
    }
}
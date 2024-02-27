<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ChangeAuth Entity.
 *
 * @property int $id
 * @property int $user_id
 * @property \App\Model\Entity\User $user
 * @property string $user_name
 * @property string $user_phone_number
 * @property string $user_email
 * @property string $user_bank_name
 * @property string $user_account_number
 * @property string $request
 * @property \Cake\I18n\Time $created
 */
class ChangeAuth extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}

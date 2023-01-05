<?php

namespace App\Enum;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;
use App\Traits\ChoicesFromEnvTrait;

/**
 * Class DocumentTypeEnum
 * @EnumAnnotation
 * @package App\Enum
 */
class DocumentTypeEnum extends Enum
{
    use ChoicesFromEnvTrait;

    private const ENV_VAR = 'DOCUMENT_TYPE_ENUM';
    private const NULLABLE_DISABLED = true;

    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = self::class;

    public const RF_CITIZEN_PASSPORT = 'RF_CITIZEN_PASSPORT';
    public const FOREIGN_CITIZEN_PASSPORT = 'FOREIGN_CITIZEN_PASSPORT';
    public const USSR_CITIZEN_PASSPORT = 'USSR_CITIZEN_PASSPORT';
    public const USSR_CITIZEN_INTERNATIONAL_PASSPORT = 'USSR_CITIZEN_INTERNATIONAL_PASSPORT';
    public const BIRTH_CERTIFICATE = 'BIRTH_CERTIFICATE';
    public const OFFICER_IDENTITY_CARD = 'OFFICER_IDENTITY_CARD';
    public const RELEASE_FROM_PRISON_CERTIFICATE = 'RELEASE_FROM_PRISON_CERTIFICATE';
    public const NAVY_MINISTRY_PASSPORT = 'NAVY_MINISTRY_PASSPORT';
    public const MILITARY_ID = 'MILITARY_ID';
    public const TEMPORARY_ID_INSTEAD_OF_MILITARY_ID = 'TEMPORARY_ID_INSTEAD_OF_MILITARY_ID';
    public const DIPLOMATIC_PASSPORT = 'DIPLOMATIC_PASSPORT';
    public const CERTIFICATE_APPLICATION_REFUGEE = 'CERTIFICATE_APPLICATION_REFUGEE';
    public const RESIDENCE_PERMIT = 'RESIDENCE_PERMIT';
    public const REFUGEE_CERTIFICATE = 'REFUGEE_CERTIFICATE';
    public const RF_CITIZEN_TEMPORARY_IDENTITY_CARD = 'RF_CITIZEN_TEMPORARY_IDENTITY_CARD';
    public const TEMPORARY_RESIDENCE_PERMIT = 'TEMPORARY_RESIDENCE_PERMIT';
    public const TEMPORARY_ASYLUM_CERTIFICATE = 'TEMPORARY_ASYLUM_CERTIFICATE';
    public const RF_CITIZEN_INTERNATIONAL_PASSPORT = 'RF_CITIZEN_INTERNATIONAL_PASSPORT';
    public const FOREIGN_STATE_BIRTH_CERTIFICATE = 'FOREIGN_STATE_BIRTH_CERTIFICATE';
    public const RF_SERVICEMAN_IDENTITY_CARD = 'RF_SERVICEMAN_IDENTITY_CARD';
    public const SAILOR_PASSPORT = 'SAILOR_PASSPORT';
    public const STOCK_OFFICER_MILITARY_CARD = 'STOCK_OFFICER_MILITARY_CARD';
    public const RESIDENCE_PLACE_CONFIRM_DOCS = 'RESIDENCE_PLACE_CONFIRM_DOCS';
    public const RESIDENCE_PLACE_REGISTRATION_CERTIFICATE = 'RESIDENCE_PLACE_REGISTRATION_CERTIFICATE';
    public const FOREIGN_CITIZEN_RESIDENCE_PERMIT = 'FOREIGN_CITIZEN_RESIDENCE_PERMIT';
    public const DEATH_CERTIFICATE = 'DEATH_CERTIFICATE';
    public const OTHER_DOCS = 'OTHER_DOCS';

    /**
     * @var array
     */
    protected static $choices = [
        self::RF_CITIZEN_PASSPORT => 'document_type.rf_citizen_passport',
        self::FOREIGN_CITIZEN_PASSPORT => 'document_type.foreign_citizen_passport',
        self::USSR_CITIZEN_PASSPORT => 'document_type.ussr_citizen_passport',
        self::USSR_CITIZEN_INTERNATIONAL_PASSPORT => 'document_type.ussr_citizen_international_passport',
        self::BIRTH_CERTIFICATE => 'document_type.birth_certificate',
        self::OFFICER_IDENTITY_CARD => 'document_type.officer_identity_card',
        self::RELEASE_FROM_PRISON_CERTIFICATE => 'document_type.release_from_prison_certificate',
        self::NAVY_MINISTRY_PASSPORT => 'document_type.navy_ministry_passport',
        self::MILITARY_ID => 'document_type.military_id',
        self::TEMPORARY_ID_INSTEAD_OF_MILITARY_ID => 'document_type.temporary_id_instead_of_military_id',
        self::DIPLOMATIC_PASSPORT => 'document_type.diplomatic_passport',
        self::CERTIFICATE_APPLICATION_REFUGEE => 'document_type.certificate_application_refugee',
        self::RESIDENCE_PERMIT => 'document_type.residence_permit',
        self::REFUGEE_CERTIFICATE => 'document_type.refugee_certificate',
        self::RF_CITIZEN_TEMPORARY_IDENTITY_CARD => 'document_type.rf_citizen_temporary_identity_card',
        self::TEMPORARY_RESIDENCE_PERMIT => 'document_type.temporary_residence_permit',
        self::TEMPORARY_ASYLUM_CERTIFICATE => 'document_type.temporary_asylum_certificate',
        self::RF_CITIZEN_INTERNATIONAL_PASSPORT => 'document_type.rf_citizen_international_passport',
        self::FOREIGN_STATE_BIRTH_CERTIFICATE => 'document_type.foreign_state_birth_certificate',
        self::RF_SERVICEMAN_IDENTITY_CARD => 'document_type.rf_serviceman_identity_card',
        self::SAILOR_PASSPORT => 'document_type.sailor_passport',
        self::STOCK_OFFICER_MILITARY_CARD => 'document_type.stock_officer_military_card',
        self::RESIDENCE_PLACE_CONFIRM_DOCS => 'document_type.residence_place_confirm_docs',
        self::RESIDENCE_PLACE_REGISTRATION_CERTIFICATE => 'document_type.residence_place_registration_certificate',
        self::FOREIGN_CITIZEN_RESIDENCE_PERMIT => 'document_type.foreign_citizen_residence_permit',
        self::DEATH_CERTIFICATE => 'document_type.death_certificate',
        self::OTHER_DOCS => 'document_type.other_docs',
    ];
}

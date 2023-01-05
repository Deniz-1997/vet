<?php


namespace App\Command;

use App\Entity\Security\Group;
use App\Entity\Security\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateGroupRolesCommand extends Command
{

//    const ROLES_DOCTOR = [
//        'ROLE_WRITE_APPOINTMENT', 'ROLE_READ_APPOINTMENT', 'ROLE_ADD_APPOINTMENT', 'ROLE_UPDATE_APPOINTMENT', 'ROLE_DELETE_APPOINTMENT',
//        'ROLE_WRITE_APPOINTMENT_TYPE', 'ROLE_READ_APPOINTMENT_TYPE', 'ROLE_ADD_APPOINTMENT_TYPE', 'ROLE_UPDATE_APPOINTMENT_TYPE', 'ROLE_DELETE_APPOINTMENT_TYPE',
//        'ROLE_WRITE_OWNER', 'ROLE_READ_OWNER', 'ROLE_ADD_OWNER', 'ROLE_UPDATE_OWNER', 'ROLE_DELETE_OWNER',
//        'ROLE_WRITE_TEMPERATURE', 'ROLE_READ_TEMPERATURE', 'ROLE_ADD_TEMPERATURE', 'ROLE_UPDATE_TEMPERATURE', 'ROLE_DELETE_TEMPERATURE',
//        'ROLE_WRITE_WEIGHT', 'ROLE_READ_WEIGHT', 'ROLE_ADD_WEIGHT', 'ROLE_UPDATE_WEIGHT', 'ROLE_DELETE_WEIGHT',
//        'ROLE_WRITE_PET', 'ROLE_READ_PET', 'ROLE_ADD_PET', 'ROLE_UPDATE_PET', 'ROLE_DELETE_PET',
//        'ROLE_WRITE_PET_TO_OWNER', 'ROLE_READ_PET_TO_OWNER', 'ROLE_ADD_PET_TO_OWNER', 'ROLE_UPDATE_PET_TO_OWNER', 'ROLE_DELETE_PET_TO_OWNER',
//        'ROLE_WRITE_APPOINTMENT_STATUS', 'ROLE_READ_APPOINTMENT_STATUS', 'ROLE_ADD_APPOINTMENT_STATUS', 'ROLE_UPDATE_APPOINTMENT_STATUS', 'ROLE_DELETE_APPOINTMENT_STATUS',
//        'ROLE_WRITE_BREED', 'ROLE_READ_BREED', 'ROLE_ADD_BREED', 'ROLE_UPDATE_BREED', 'ROLE_DELETE_BREED',
//        'ROLE_WRITE_ACTIVITY', 'ROLE_READ_ACTIVITY', 'ROLE_ADD_ACTIVITY', 'ROLE_UPDATE_ACTIVITY', 'ROLE_DELETE_ACTIVITY',
//        'ROLE_WRITE_LEGAL_FORM', 'ROLE_READ_LEGAL_FORM', 'ROLE_ADD_LEGAL_FORM', 'ROLE_UPDATE_LEGAL_FORM', 'ROLE_DELETE_LEGAL_FORM',
//        'ROLE_WRITE_STATUS', 'ROLE_READ_STATUS', 'ROLE_ADD_STATUS', 'ROLE_UPDATE_STATUS', 'ROLE_DELETE_STATUS',
//        'ROLE_WRITE_PET_TYPE', 'ROLE_READ_PET_TYPE', 'ROLE_ADD_PET_TYPE', 'ROLE_UPDATE_PET_TYPE', 'ROLE_DELETE_PET_TYPE',
//        'ROLE_WRITE_UNIT', 'ROLE_READ_UNIT', 'ROLE_ADD_UNIT', 'ROLE_UPDATE_UNIT', 'ROLE_DELETE_UNIT',
//        'ROLE_WRITE_WILD_ANIMAL', 'ROLE_READ_WILD_ANIMAL', 'ROLE_ADD_WILD_ANIMAL', 'ROLE_UPDATE_WILD_ANIMAL', 'ROLE_DELETE_WILD_ANIMAL',
//        'ROLE_WRITE_UPLOADED_FILE', 'ROLE_READ_UPLOADED_FILE', 'ROLE_ADD_UPLOADED_FILE', 'ROLE_UPDATE_UPLOADED_FILE', 'ROLE_DELETE_UPLOADED_FILE',
//        'ROLE_WRITE_EMAIL', 'ROLE_READ_EMAIL', 'ROLE_UPDATE_EMAIL', 'ROLE_DELETE_EMAIL',
//        'ROLE_WRITE_USER', 'ROLE_READ_USER', 'ROLE_UPDATE_USER', 'ROLE_DELETE_USER',
//        'ROLE_WRITE_ANIMAL_DEATH', 'ROLE_READ_ANIMAL_DEATH', 'ROLE_ADD_ANIMAL_DEATH', 'ROLE_UPDATE_ANIMAL_DEATH', 'ROLE_DELETE_ANIMAL_DEATH',
//        'ROLE_WRITE_PRODUCT', 'ROLE_READ_PRODUCT', 'ROLE_ADD_PRODUCT', 'ROLE_UPDATE_PRODUCT', 'ROLE_DELETE_PRODUCT',
//        'ROLE_WRITE_PROFESSION', 'ROLE_READ_PROFESSION', 'ROLE_ADD_PROFESSION', 'ROLE_UPDATE_PROFESSION', 'ROLE_DELETE_PROFESSION',
//        'ROLE_WRITE_WILD_ANIMAL', 'ROLE_READ_WILD_ANIMAL', 'ROLE_ADD_WILD_ANIMAL', 'ROLE_UPDATE_WILD_ANIMAL', 'ROLE_DELETE_WILD_ANIMAL',
//        'ROLE_WRITE_CONTRACTOR', 'ROLE_READ_CONTRACTOR', 'ROLE_ADD_CONTRACTOR', 'ROLE_UPDATE_CONTRACTOR', 'ROLE_DELETE_CONTRACTOR',
//        'ROLE_WRITE_CULLING_REGISTRATION', 'ROLE_READ_CULLING_REGISTRATION', 'ROLE_ADD_CULLING_REGISTRATION', 'ROLE_UPDATE_CULLING_REGISTRATION', 'ROLE_DELETE_CULLING_REGISTRATION',
//        'ROLE_WRITE_SHELTER', 'ROLE_READ_SHELTER', 'ROLE_ADD_SHELTER', 'ROLE_UPDATE_SHELTER', 'ROLE_DELETE_SHELTER',
//        'ROLE_WRITE_WILD_ANIMAL_FILE', 'ROLE_READ_WILD_ANIMAL_FILE', 'ROLE_ADD_WILD_ANIMAL_FILE', 'ROLE_UPDATE_WILD_ANIMAL_FILE', 'ROLE_DELETE_WILD_ANIMAL_FILE',
//        'ROLE_WRITE_TAG_COLOR', 'ROLE_READ_TAG_COLOR', 'ROLE_ADD_TAG_COLOR', 'ROLE_UPDATE_TAG_COLOR', 'ROLE_DELETE_TAG_COLOR',
//        'ROLE_WRITE_TAG_FORM', 'ROLE_READ_TAG_FORM', 'ROLE_ADD_TAG_FORM', 'ROLE_UPDATE_TAG_FORM', 'ROLE_DELETE_TAG_FORM',
//        'ROLE_WRITE_STOCK', 'ROLE_READ_STOCK', 'ROLE_ADD_STOCK', 'ROLE_UPDATE_STOCK', 'ROLE_DELETE_STOCK',
//        'ROLE_WRITE_FILE', 'ROLE_READ_FILE', 'ROLE_ADD_FILE', 'ROLE_UPDATE_FILE', 'ROLE_DELETE_FILE',
//        'ROLE_WRITE_FILE_TYPE', 'ROLE_READ_FILE_TYPE', 'ROLE_ADD_FILE_TYPE', 'ROLE_UPDATE_FILE_TYPE', 'ROLE_DELETE_FILE_TYPE',
//        'ROLE_WRITE_EVENT', 'ROLE_READ_EVENT', 'ROLE_ADD_EVENT', 'ROLE_UPDATE_EVENT', 'ROLE_DELETE_EVENT',
//        'ROLE_WRITE_EVENT_STATUS', 'ROLE_READ_EVENT_STATUS', 'ROLE_ADD_EVENT_STATUS', 'ROLE_UPDATE_EVENT_STATUS', 'ROLE_DELETE_EVENT_STATUS',
//        'ROLE_WRITE_CULLING_REGISTRATIONS_FILE', 'ROLE_READ_CULLING_REGISTRATIONS_FILE', 'ROLE_ADD_CULLING_REGISTRATIONS_FILE', 'ROLE_UPDATE_CULLING_REGISTRATIONS_FILE', 'ROLE_DELETE_CULLING_REGISTRATIONS_FILE',
//        'ROLE_WRITE_ORGANIZATION', 'ROLE_READ_ORGANIZATION', 'ROLE_ADD_ORGANIZATION', 'ROLE_UPDATE_ORGANIZATION', 'ROLE_DELETE_ORGANIZATION',
//        'ROLE_WRITE_PRINT_FORM', 'ROLE_READ_PRINT_FORM', 'ROLE_ADD_PRINT_FORM', 'ROLE_UPDATE_PRINT_FORM', 'ROLE_DELETE_PRINT_FORM',
//        'ROLE_WRITE_FILE_OWNER', 'ROLE_READ_FILE_OWNER', 'ROLE_ADD_FILE_OWNER', 'ROLE_UPDATE_FILE_OWNER', 'ROLE_DELETE_FILE_OWNER',
//        'ROLE_READ_PET_LEAR', 'ROLE_ADD_PET_LEAR',
//        'ROLE_WRITE_IDENTIFIER', 'ROLE_READ_IDENTIFIER', 'ROLE_ADD_IDENTIFIER', 'ROLE_UPDATE_IDENTIFIER', 'ROLE_DELETE_IDENTIFIER',
//        'ROLE_WRITE_IDENTIFIER_TYPE', 'ROLE_READ_IDENTIFIER_TYPE', 'ROLE_ADD_IDENTIFIER_TYPE', 'ROLE_UPDATE_IDENTIFIER_TYPE', 'ROLE_DELETE_IDENTIFIER_TYPE',
//        'ROLE_WRITE_APPOINTMENT_TEMPLATE', 'ROLE_READ_APPOINTMENT_TEMPLATE', 'ROLE_ADD_APPOINTMENT_TEMPLATE', 'ROLE_UPDATE_APPOINTMENT_TEMPLATE', 'ROLE_DELETE_APPOINTMENT_TEMPLATE',
//        'ROLE_READ_CASH_RECEIPT', 'ROLE_READ_CASH_REGISTER', 'ROLE_READ_SHIFT',
//        'ROLE_WRITE_PRODUCT_RECEIPT', 'ROLE_READ_PRODUCT_RECEIPT', 'ROLE_DELETE_PRODUCT_RECEIPT', 'ROLE_ADD_PRODUCT_RECEIPT', 'ROLE_UPDATE_PRODUCT_RECEIPT',
//        'ROLE_WRITE_PRODUCT_TRANSFER', 'ROLE_READ_PRODUCT_TRANSFER', 'ROLE_DELETE_PRODUCT_TRANSFER', 'ROLE_ADD_PRODUCT_TRANSFER', 'ROLE_UPDATE_PRODUCT_TRANSFER',
//        'ROLE_WRITE_PRODUCT_INVENTORY', 'ROLE_READ_PRODUCT_INVENTORY', 'ROLE_DELETE_PRODUCT_INVENTORY', 'ROLE_ADD_PRODUCT_INVENTORY', 'ROLE_UPDATE_PRODUCT_INVENTORY'
//    ];
//
//    const ROLES_RECEPTIONIST = [
//        'ROLE_WRITE_APPOINTMENT', 'ROLE_READ_APPOINTMENT', 'ROLE_ADD_APPOINTMENT', 'ROLE_UPDATE_APPOINTMENT', 'ROLE_DELETE_APPOINTMENT',
//        'ROLE_WRITE_APPOINTMENT_TYPE', 'ROLE_READ_APPOINTMENT_TYPE', 'ROLE_ADD_APPOINTMENT_TYPE', 'ROLE_UPDATE_APPOINTMENT_TYPE', 'ROLE_DELETE_APPOINTMENT_TYPE',
//        'ROLE_WRITE_OWNER', 'ROLE_READ_OWNER', 'ROLE_ADD_OWNER', 'ROLE_UPDATE_OWNER', 'ROLE_DELETE_OWNER',
//        'ROLE_WRITE_TEMPERATURE', 'ROLE_READ_TEMPERATURE', 'ROLE_ADD_TEMPERATURE', 'ROLE_UPDATE_TEMPERATURE', 'ROLE_DELETE_TEMPERATURE',
//        'ROLE_WRITE_WEIGHT', 'ROLE_READ_WEIGHT', 'ROLE_ADD_WEIGHT', 'ROLE_UPDATE_WEIGHT', 'ROLE_DELETE_WEIGHT',
//        'ROLE_WRITE_PET', 'ROLE_READ_PET', 'ROLE_ADD_PET', 'ROLE_UPDATE_PET', 'ROLE_DELETE_PET',
//        'ROLE_WRITE_PET_TO_OWNER', 'ROLE_READ_PET_TO_OWNER', 'ROLE_ADD_PET_TO_OWNER', 'ROLE_UPDATE_PET_TO_OWNER', 'ROLE_DELETE_PET_TO_OWNER',
//        'ROLE_WRITE_APPOINTMENT_STATUS', 'ROLE_READ_APPOINTMENT_STATUS', 'ROLE_ADD_APPOINTMENT_STATUS', 'ROLE_UPDATE_APPOINTMENT_STATUS', 'ROLE_DELETE_APPOINTMENT_STATUS',
//        'ROLE_WRITE_BREED', 'ROLE_READ_BREED', 'ROLE_ADD_BREED', 'ROLE_UPDATE_BREED', 'ROLE_DELETE_BREED',
//        'ROLE_WRITE_ACTIVITY', 'ROLE_READ_ACTIVITY', 'ROLE_ADD_ACTIVITY', 'ROLE_UPDATE_ACTIVITY', 'ROLE_DELETE_ACTIVITY',
//        'ROLE_WRITE_LEGAL_FORM', 'ROLE_READ_LEGAL_FORM', 'ROLE_ADD_LEGAL_FORM', 'ROLE_UPDATE_LEGAL_FORM', 'ROLE_DELETE_LEGAL_FORM',
//        'ROLE_WRITE_STATUS', 'ROLE_READ_STATUS', 'ROLE_ADD_STATUS', 'ROLE_UPDATE_STATUS', 'ROLE_DELETE_STATUS',
//        'ROLE_WRITE_PET_TYPE', 'ROLE_READ_PET_TYPE', 'ROLE_ADD_PET_TYPE', 'ROLE_UPDATE_PET_TYPE', 'ROLE_DELETE_PET_TYPE',
//        'ROLE_WRITE_UNIT', 'ROLE_READ_UNIT', 'ROLE_ADD_UNIT', 'ROLE_UPDATE_UNIT', 'ROLE_DELETE_UNIT',
//        'ROLE_WRITE_WILD_ANIMAL', 'ROLE_READ_WILD_ANIMAL', 'ROLE_ADD_WILD_ANIMAL', 'ROLE_UPDATE_WILD_ANIMAL', 'ROLE_DELETE_WILD_ANIMAL',
//        'ROLE_WRITE_UPLOADED_FILE', 'ROLE_READ_UPLOADED_FILE', 'ROLE_ADD_UPLOADED_FILE', 'ROLE_UPDATE_UPLOADED_FILE', 'ROLE_DELETE_UPLOADED_FILE',
//        'ROLE_WRITE_EMAIL', 'ROLE_READ_EMAIL', 'ROLE_UPDATE_EMAIL', 'ROLE_DELETE_EMAIL',
//        'ROLE_WRITE_USER', 'ROLE_READ_USER', 'ROLE_UPDATE_USER', 'ROLE_DELETE_USER',
//        'ROLE_WRITE_ANIMAL_DEATH', 'ROLE_READ_ANIMAL_DEATH', 'ROLE_ADD_ANIMAL_DEATH', 'ROLE_UPDATE_ANIMAL_DEATH', 'ROLE_DELETE_ANIMAL_DEATH',
//        'ROLE_WRITE_PRODUCT', 'ROLE_READ_PRODUCT', 'ROLE_ADD_PRODUCT', 'ROLE_UPDATE_PRODUCT', 'ROLE_DELETE_PRODUCT',
//        'ROLE_WRITE_PROFESSION', 'ROLE_READ_PROFESSION', 'ROLE_ADD_PROFESSION', 'ROLE_UPDATE_PROFESSION', 'ROLE_DELETE_PROFESSION',
//        'ROLE_WRITE_WILD_ANIMAL', 'ROLE_READ_WILD_ANIMAL', 'ROLE_ADD_WILD_ANIMAL', 'ROLE_UPDATE_WILD_ANIMAL', 'ROLE_DELETE_WILD_ANIMAL',
//        'ROLE_WRITE_CONTRACTOR', 'ROLE_READ_CONTRACTOR', 'ROLE_ADD_CONTRACTOR', 'ROLE_UPDATE_CONTRACTOR', 'ROLE_DELETE_CONTRACTOR',
//        'ROLE_WRITE_CULLING_REGISTRATION', 'ROLE_READ_CULLING_REGISTRATION', 'ROLE_ADD_CULLING_REGISTRATION', 'ROLE_UPDATE_CULLING_REGISTRATION', 'ROLE_DELETE_CULLING_REGISTRATION',
//        'ROLE_WRITE_SHELTER', 'ROLE_READ_SHELTER', 'ROLE_ADD_SHELTER', 'ROLE_UPDATE_SHELTER', 'ROLE_DELETE_SHELTER',
//        'ROLE_WRITE_WILD_ANIMAL_FILE', 'ROLE_READ_WILD_ANIMAL_FILE', 'ROLE_ADD_WILD_ANIMAL_FILE', 'ROLE_UPDATE_WILD_ANIMAL_FILE', 'ROLE_DELETE_WILD_ANIMAL_FILE',
//        'ROLE_WRITE_TAG_COLOR', 'ROLE_READ_TAG_COLOR', 'ROLE_ADD_TAG_COLOR', 'ROLE_UPDATE_TAG_COLOR', 'ROLE_DELETE_TAG_COLOR',
//        'ROLE_WRITE_TAG_FORM', 'ROLE_READ_TAG_FORM', 'ROLE_ADD_TAG_FORM', 'ROLE_UPDATE_TAG_FORM', 'ROLE_DELETE_TAG_FORM',
//        'ROLE_WRITE_STOCK', 'ROLE_READ_STOCK', 'ROLE_ADD_STOCK', 'ROLE_UPDATE_STOCK', 'ROLE_DELETE_STOCK',
//        'ROLE_WRITE_FILE', 'ROLE_READ_FILE', 'ROLE_ADD_FILE', 'ROLE_UPDATE_FILE', 'ROLE_DELETE_FILE',
//        'ROLE_WRITE_FILE_TYPE', 'ROLE_READ_FILE_TYPE', 'ROLE_ADD_FILE_TYPE', 'ROLE_UPDATE_FILE_TYPE', 'ROLE_DELETE_FILE_TYPE',
//        'ROLE_WRITE_EVENT', 'ROLE_READ_EVENT', 'ROLE_ADD_EVENT', 'ROLE_UPDATE_EVENT', 'ROLE_DELETE_EVENT',
//        'ROLE_WRITE_EVENT_STATUS', 'ROLE_READ_EVENT_STATUS', 'ROLE_ADD_EVENT_STATUS', 'ROLE_UPDATE_EVENT_STATUS', 'ROLE_DELETE_EVENT_STATUS',
//        'ROLE_WRITE_CULLING_REGISTRATIONS_FILE', 'ROLE_READ_CULLING_REGISTRATIONS_FILE', 'ROLE_ADD_CULLING_REGISTRATIONS_FILE', 'ROLE_UPDATE_CULLING_REGISTRATIONS_FILE', 'ROLE_DELETE_CULLING_REGISTRATIONS_FILE',
//        'ROLE_WRITE_ORGANIZATION', 'ROLE_READ_ORGANIZATION', 'ROLE_ADD_ORGANIZATION', 'ROLE_UPDATE_ORGANIZATION', 'ROLE_DELETE_ORGANIZATION',
//        'ROLE_WRITE_PRINT_FORM', 'ROLE_READ_PRINT_FORM', 'ROLE_ADD_PRINT_FORM', 'ROLE_UPDATE_PRINT_FORM', 'ROLE_DELETE_PRINT_FORM',
//        'ROLE_WRITE_FILE_OWNER', 'ROLE_READ_FILE_OWNER', 'ROLE_ADD_FILE_OWNER', 'ROLE_UPDATE_FILE_OWNER', 'ROLE_DELETE_FILE_OWNER',
//        'ROLE_WRITE_IDENTIFIER', 'ROLE_READ_IDENTIFIER', 'ROLE_ADD_IDENTIFIER', 'ROLE_UPDATE_IDENTIFIER', 'ROLE_DELETE_IDENTIFIER',
//        'ROLE_WRITE_IDENTIFIER_TYPE', 'ROLE_READ_IDENTIFIER_TYPE', 'ROLE_ADD_IDENTIFIER_TYPE', 'ROLE_UPDATE_IDENTIFIER_TYPE', 'ROLE_DELETE_IDENTIFIER_TYPE',
//        'ROLE_WRITE_APPOINTMENT_TEMPLATE', 'ROLE_READ_APPOINTMENT_TEMPLATE', 'ROLE_ADD_APPOINTMENT_TEMPLATE', 'ROLE_UPDATE_APPOINTMENT_TEMPLATE', 'ROLE_DELETE_APPOINTMENT_TEMPLATE',
//        'ROLE_READ_PET_LEAR', 'ROLE_ADD_PET_LEAR',
//        'ROLE_READ_CASH_RECEIPT', 'ROLE_READ_CASH_REGISTER', 'ROLE_READ_SHIFT',
//        'ROLE_WRITE_PRODUCT_RECEIPT', 'ROLE_READ_PRODUCT_RECEIPT', 'ROLE_DELETE_PRODUCT_RECEIPT', 'ROLE_ADD_PRODUCT_RECEIPT', 'ROLE_UPDATE_PRODUCT_RECEIPT',
//        'ROLE_WRITE_PRODUCT_TRANSFER', 'ROLE_READ_PRODUCT_TRANSFER', 'ROLE_DELETE_PRODUCT_TRANSFER', 'ROLE_ADD_PRODUCT_TRANSFER', 'ROLE_UPDATE_PRODUCT_TRANSFER',
//        'ROLE_WRITE_PRODUCT_INVENTORY', 'ROLE_READ_PRODUCT_INVENTORY', 'ROLE_DELETE_PRODUCT_INVENTORY', 'ROLE_ADD_PRODUCT_INVENTORY', 'ROLE_UPDATE_PRODUCT_INVENTORY'
//    ];
    const ROLES_DOCTOR = [
        'ROLE_WRITE_APPOINTMENT', 'ROLE_READ_APPOINTMENT', 'ROLE_ADD_APPOINTMENT', 'ROLE_UPDATE_APPOINTMENT', 'ROLE_DELETE_APPOINTMENT',
        'ROLE_WRITE_APPOINTMENT_TYPE', 'ROLE_READ_APPOINTMENT_TYPE', 'ROLE_ADD_APPOINTMENT_TYPE', 'ROLE_UPDATE_APPOINTMENT_TYPE', 'ROLE_DELETE_APPOINTMENT_TYPE',
        'ROLE_WRITE_OWNER', 'ROLE_READ_OWNER', 'ROLE_ADD_OWNER', 'ROLE_UPDATE_OWNER', 'ROLE_DELETE_OWNER',
        'ROLE_WRITE_TEMPERATURE', 'ROLE_READ_TEMPERATURE', 'ROLE_ADD_TEMPERATURE', 'ROLE_UPDATE_TEMPERATURE', 'ROLE_DELETE_TEMPERATURE',
        'ROLE_WRITE_WEIGHT', 'ROLE_READ_WEIGHT', 'ROLE_ADD_WEIGHT', 'ROLE_UPDATE_WEIGHT', 'ROLE_DELETE_WEIGHT',
        'ROLE_WRITE_PET', 'ROLE_READ_PET', 'ROLE_ADD_PET', 'ROLE_UPDATE_PET', 'ROLE_DELETE_PET',
        'ROLE_WRITE_PET_TO_OWNER', 'ROLE_READ_PET_TO_OWNER', 'ROLE_ADD_PET_TO_OWNER', 'ROLE_UPDATE_PET_TO_OWNER', 'ROLE_DELETE_PET_TO_OWNER',
        'ROLE_WRITE_APPOINTMENT_STATUS', 'ROLE_READ_APPOINTMENT_STATUS', 'ROLE_ADD_APPOINTMENT_STATUS', 'ROLE_UPDATE_APPOINTMENT_STATUS', 'ROLE_DELETE_APPOINTMENT_STATUS',
        'ROLE_WRITE_BREED', 'ROLE_READ_BREED', 'ROLE_ADD_BREED', 'ROLE_UPDATE_BREED', 'ROLE_DELETE_BREED',
        'ROLE_WRITE_ACTIVITY', 'ROLE_READ_ACTIVITY', 'ROLE_ADD_ACTIVITY', 'ROLE_UPDATE_ACTIVITY', 'ROLE_DELETE_ACTIVITY',
        'ROLE_WRITE_LEGAL_FORM', 'ROLE_READ_LEGAL_FORM', 'ROLE_ADD_LEGAL_FORM', 'ROLE_UPDATE_LEGAL_FORM', 'ROLE_DELETE_LEGAL_FORM',
        'ROLE_WRITE_STATUS', 'ROLE_READ_STATUS', 'ROLE_ADD_STATUS', 'ROLE_UPDATE_STATUS', 'ROLE_DELETE_STATUS',
        'ROLE_WRITE_PET_TYPE', 'ROLE_READ_PET_TYPE', 'ROLE_ADD_PET_TYPE', 'ROLE_UPDATE_PET_TYPE', 'ROLE_DELETE_PET_TYPE',
        'ROLE_WRITE_UNIT', 'ROLE_READ_UNIT', 'ROLE_ADD_UNIT', 'ROLE_UPDATE_UNIT', 'ROLE_DELETE_UNIT',
        'ROLE_WRITE_WILD_ANIMAL', 'ROLE_READ_WILD_ANIMAL', 'ROLE_ADD_WILD_ANIMAL', 'ROLE_UPDATE_WILD_ANIMAL', 'ROLE_DELETE_WILD_ANIMAL',
        'ROLE_WRITE_UPLOADED_FILE', 'ROLE_READ_UPLOADED_FILE', 'ROLE_ADD_UPLOADED_FILE', 'ROLE_UPDATE_UPLOADED_FILE', 'ROLE_DELETE_UPLOADED_FILE',
        'ROLE_WRITE_EMAIL', 'ROLE_READ_EMAIL', 'ROLE_UPDATE_EMAIL', 'ROLE_DELETE_EMAIL',
        'ROLE_WRITE_USER', 'ROLE_READ_USER', 'ROLE_UPDATE_USER', 'ROLE_DELETE_USER',
        'ROLE_WRITE_ANIMAL_DEATH', 'ROLE_READ_ANIMAL_DEATH', 'ROLE_ADD_ANIMAL_DEATH', 'ROLE_UPDATE_ANIMAL_DEATH', 'ROLE_DELETE_ANIMAL_DEATH',
        'ROLE_WRITE_PRODUCT', 'ROLE_READ_PRODUCT', 'ROLE_ADD_PRODUCT', 'ROLE_UPDATE_PRODUCT', 'ROLE_DELETE_PRODUCT',
        'ROLE_WRITE_PROFESSION', 'ROLE_READ_PROFESSION', 'ROLE_ADD_PROFESSION', 'ROLE_UPDATE_PROFESSION', 'ROLE_DELETE_PROFESSION',
        'ROLE_WRITE_WILD_ANIMAL', 'ROLE_READ_WILD_ANIMAL', 'ROLE_ADD_WILD_ANIMAL', 'ROLE_UPDATE_WILD_ANIMAL', 'ROLE_DELETE_WILD_ANIMAL',
        'ROLE_WRITE_CONTRACTOR', 'ROLE_READ_CONTRACTOR', 'ROLE_ADD_CONTRACTOR', 'ROLE_UPDATE_CONTRACTOR', 'ROLE_DELETE_CONTRACTOR',
        'ROLE_WRITE_CULLING_REGISTRATION', 'ROLE_READ_CULLING_REGISTRATION', 'ROLE_ADD_CULLING_REGISTRATION', 'ROLE_UPDATE_CULLING_REGISTRATION', 'ROLE_DELETE_CULLING_REGISTRATION',
        'ROLE_WRITE_SHELTER', 'ROLE_READ_SHELTER', 'ROLE_ADD_SHELTER', 'ROLE_UPDATE_SHELTER', 'ROLE_DELETE_SHELTER',
        'ROLE_WRITE_WILD_ANIMAL_FILE', 'ROLE_READ_WILD_ANIMAL_FILE', 'ROLE_ADD_WILD_ANIMAL_FILE', 'ROLE_UPDATE_WILD_ANIMAL_FILE', 'ROLE_DELETE_WILD_ANIMAL_FILE',
        'ROLE_WRITE_TAG_COLOR', 'ROLE_READ_TAG_COLOR', 'ROLE_ADD_TAG_COLOR', 'ROLE_UPDATE_TAG_COLOR', 'ROLE_DELETE_TAG_COLOR',
        'ROLE_WRITE_TAG_FORM', 'ROLE_READ_TAG_FORM', 'ROLE_ADD_TAG_FORM', 'ROLE_UPDATE_TAG_FORM', 'ROLE_DELETE_TAG_FORM',
        'ROLE_WRITE_STOCK', 'ROLE_READ_STOCK', 'ROLE_ADD_STOCK', 'ROLE_UPDATE_STOCK', 'ROLE_DELETE_STOCK',
        'ROLE_WRITE_FILE', 'ROLE_READ_FILE', 'ROLE_ADD_FILE', 'ROLE_UPDATE_FILE', 'ROLE_DELETE_FILE',
        'ROLE_WRITE_FILE_TYPE', 'ROLE_READ_FILE_TYPE', 'ROLE_ADD_FILE_TYPE', 'ROLE_UPDATE_FILE_TYPE', 'ROLE_DELETE_FILE_TYPE',
        'ROLE_WRITE_EVENT', 'ROLE_READ_EVENT', 'ROLE_ADD_EVENT', 'ROLE_UPDATE_EVENT', 'ROLE_DELETE_EVENT',
        'ROLE_WRITE_EVENT_STATUS', 'ROLE_READ_EVENT_STATUS', 'ROLE_ADD_EVENT_STATUS', 'ROLE_UPDATE_EVENT_STATUS', 'ROLE_DELETE_EVENT_STATUS',
        'ROLE_WRITE_CULLING_REGISTRATIONS_FILE', 'ROLE_READ_CULLING_REGISTRATIONS_FILE', 'ROLE_ADD_CULLING_REGISTRATIONS_FILE', 'ROLE_UPDATE_CULLING_REGISTRATIONS_FILE', 'ROLE_DELETE_CULLING_REGISTRATIONS_FILE',
        'ROLE_WRITE_ORGANIZATION', 'ROLE_READ_ORGANIZATION', 'ROLE_ADD_ORGANIZATION', 'ROLE_UPDATE_ORGANIZATION', 'ROLE_DELETE_ORGANIZATION',
        'ROLE_WRITE_PRINT_FORM', 'ROLE_READ_PRINT_FORM', 'ROLE_ADD_PRINT_FORM', 'ROLE_UPDATE_PRINT_FORM', 'ROLE_DELETE_PRINT_FORM',
        'ROLE_WRITE_FILE_OWNER', 'ROLE_READ_FILE_OWNER', 'ROLE_ADD_FILE_OWNER', 'ROLE_UPDATE_FILE_OWNER', 'ROLE_DELETE_FILE_OWNER',
        'ROLE_READ_PET_LEAR', 'ROLE_ADD_PET_LEAR',
        'ROLE_WRITE_IDENTIFIER', 'ROLE_READ_IDENTIFIER', 'ROLE_ADD_IDENTIFIER', 'ROLE_UPDATE_IDENTIFIER', 'ROLE_DELETE_IDENTIFIER',
        'ROLE_WRITE_IDENTIFIER_TYPE', 'ROLE_READ_IDENTIFIER_TYPE', 'ROLE_ADD_IDENTIFIER_TYPE', 'ROLE_UPDATE_IDENTIFIER_TYPE', 'ROLE_DELETE_IDENTIFIER_TYPE',
        'ROLE_WRITE_APPOINTMENT_TEMPLATE', 'ROLE_READ_APPOINTMENT_TEMPLATE', 'ROLE_ADD_APPOINTMENT_TEMPLATE', 'ROLE_UPDATE_APPOINTMENT_TEMPLATE', 'ROLE_DELETE_APPOINTMENT_TEMPLATE',
        'ROLE_READ_CASH_RECEIPT', 'ROLE_READ_CASH_REGISTER', 'ROLE_READ_SHIFT',
        'ROLE_WRITE_PRODUCT_RECEIPT', 'ROLE_READ_PRODUCT_RECEIPT', 'ROLE_DELETE_PRODUCT_RECEIPT', 'ROLE_ADD_PRODUCT_RECEIPT', 'ROLE_UPDATE_PRODUCT_RECEIPT',
        'ROLE_WRITE_PRODUCT_TRANSFER', 'ROLE_READ_PRODUCT_TRANSFER', 'ROLE_DELETE_PRODUCT_TRANSFER', 'ROLE_ADD_PRODUCT_TRANSFER', 'ROLE_UPDATE_PRODUCT_TRANSFER',
        'ROLE_WRITE_PRODUCT_INVENTORY', 'ROLE_READ_PRODUCT_INVENTORY', 'ROLE_DELETE_PRODUCT_INVENTORY', 'ROLE_ADD_PRODUCT_INVENTORY', 'ROLE_UPDATE_PRODUCT_INVENTORY'
    ];

    const ROLES_RECEPTIONIST = [
        'ROLE_WRITE_APPOINTMENT', 'ROLE_READ_APPOINTMENT', 'ROLE_ADD_APPOINTMENT', 'ROLE_UPDATE_APPOINTMENT', 'ROLE_DELETE_APPOINTMENT',
        'ROLE_WRITE_APPOINTMENT_TYPE', 'ROLE_READ_APPOINTMENT_TYPE', 'ROLE_ADD_APPOINTMENT_TYPE', 'ROLE_UPDATE_APPOINTMENT_TYPE', 'ROLE_DELETE_APPOINTMENT_TYPE',
        'ROLE_WRITE_OWNER', 'ROLE_READ_OWNER', 'ROLE_ADD_OWNER', 'ROLE_UPDATE_OWNER', 'ROLE_DELETE_OWNER',
        'ROLE_WRITE_TEMPERATURE', 'ROLE_READ_TEMPERATURE', 'ROLE_ADD_TEMPERATURE', 'ROLE_UPDATE_TEMPERATURE', 'ROLE_DELETE_TEMPERATURE',
        'ROLE_WRITE_WEIGHT', 'ROLE_READ_WEIGHT', 'ROLE_ADD_WEIGHT', 'ROLE_UPDATE_WEIGHT', 'ROLE_DELETE_WEIGHT',
        'ROLE_WRITE_PET', 'ROLE_READ_PET', 'ROLE_ADD_PET', 'ROLE_UPDATE_PET', 'ROLE_DELETE_PET',
        'ROLE_WRITE_PET_TO_OWNER', 'ROLE_READ_PET_TO_OWNER', 'ROLE_ADD_PET_TO_OWNER', 'ROLE_UPDATE_PET_TO_OWNER', 'ROLE_DELETE_PET_TO_OWNER',
        'ROLE_WRITE_APPOINTMENT_STATUS', 'ROLE_READ_APPOINTMENT_STATUS', 'ROLE_ADD_APPOINTMENT_STATUS', 'ROLE_UPDATE_APPOINTMENT_STATUS', 'ROLE_DELETE_APPOINTMENT_STATUS',
        'ROLE_WRITE_BREED', 'ROLE_READ_BREED', 'ROLE_ADD_BREED', 'ROLE_UPDATE_BREED', 'ROLE_DELETE_BREED',
        'ROLE_WRITE_ACTIVITY', 'ROLE_READ_ACTIVITY', 'ROLE_ADD_ACTIVITY', 'ROLE_UPDATE_ACTIVITY', 'ROLE_DELETE_ACTIVITY',
        'ROLE_WRITE_LEGAL_FORM', 'ROLE_READ_LEGAL_FORM', 'ROLE_ADD_LEGAL_FORM', 'ROLE_UPDATE_LEGAL_FORM', 'ROLE_DELETE_LEGAL_FORM',
        'ROLE_WRITE_STATUS', 'ROLE_READ_STATUS', 'ROLE_ADD_STATUS', 'ROLE_UPDATE_STATUS', 'ROLE_DELETE_STATUS',
        'ROLE_WRITE_PET_TYPE', 'ROLE_READ_PET_TYPE', 'ROLE_ADD_PET_TYPE', 'ROLE_UPDATE_PET_TYPE', 'ROLE_DELETE_PET_TYPE',
        'ROLE_WRITE_UNIT', 'ROLE_READ_UNIT', 'ROLE_ADD_UNIT', 'ROLE_UPDATE_UNIT', 'ROLE_DELETE_UNIT',
        'ROLE_WRITE_WILD_ANIMAL', 'ROLE_READ_WILD_ANIMAL', 'ROLE_ADD_WILD_ANIMAL', 'ROLE_UPDATE_WILD_ANIMAL', 'ROLE_DELETE_WILD_ANIMAL',
        'ROLE_WRITE_UPLOADED_FILE', 'ROLE_READ_UPLOADED_FILE', 'ROLE_ADD_UPLOADED_FILE', 'ROLE_UPDATE_UPLOADED_FILE', 'ROLE_DELETE_UPLOADED_FILE',
        'ROLE_WRITE_EMAIL', 'ROLE_READ_EMAIL', 'ROLE_UPDATE_EMAIL', 'ROLE_DELETE_EMAIL',
        'ROLE_WRITE_USER', 'ROLE_READ_USER', 'ROLE_UPDATE_USER', 'ROLE_DELETE_USER',
        'ROLE_WRITE_ANIMAL_DEATH', 'ROLE_READ_ANIMAL_DEATH', 'ROLE_ADD_ANIMAL_DEATH', 'ROLE_UPDATE_ANIMAL_DEATH', 'ROLE_DELETE_ANIMAL_DEATH',
        'ROLE_WRITE_PRODUCT', 'ROLE_READ_PRODUCT', 'ROLE_ADD_PRODUCT', 'ROLE_UPDATE_PRODUCT', 'ROLE_DELETE_PRODUCT',
        'ROLE_WRITE_PROFESSION', 'ROLE_READ_PROFESSION', 'ROLE_ADD_PROFESSION', 'ROLE_UPDATE_PROFESSION', 'ROLE_DELETE_PROFESSION',
        'ROLE_WRITE_WILD_ANIMAL', 'ROLE_READ_WILD_ANIMAL', 'ROLE_ADD_WILD_ANIMAL', 'ROLE_UPDATE_WILD_ANIMAL', 'ROLE_DELETE_WILD_ANIMAL',
        'ROLE_WRITE_CONTRACTOR', 'ROLE_READ_CONTRACTOR', 'ROLE_ADD_CONTRACTOR', 'ROLE_UPDATE_CONTRACTOR', 'ROLE_DELETE_CONTRACTOR',
        'ROLE_WRITE_CULLING_REGISTRATION', 'ROLE_READ_CULLING_REGISTRATION', 'ROLE_ADD_CULLING_REGISTRATION', 'ROLE_UPDATE_CULLING_REGISTRATION', 'ROLE_DELETE_CULLING_REGISTRATION',
        'ROLE_WRITE_SHELTER', 'ROLE_READ_SHELTER', 'ROLE_ADD_SHELTER', 'ROLE_UPDATE_SHELTER', 'ROLE_DELETE_SHELTER',
        'ROLE_WRITE_WILD_ANIMAL_FILE', 'ROLE_READ_WILD_ANIMAL_FILE', 'ROLE_ADD_WILD_ANIMAL_FILE', 'ROLE_UPDATE_WILD_ANIMAL_FILE', 'ROLE_DELETE_WILD_ANIMAL_FILE',
        'ROLE_WRITE_TAG_COLOR', 'ROLE_READ_TAG_COLOR', 'ROLE_ADD_TAG_COLOR', 'ROLE_UPDATE_TAG_COLOR', 'ROLE_DELETE_TAG_COLOR',
        'ROLE_WRITE_TAG_FORM', 'ROLE_READ_TAG_FORM', 'ROLE_ADD_TAG_FORM', 'ROLE_UPDATE_TAG_FORM', 'ROLE_DELETE_TAG_FORM',
        'ROLE_WRITE_STOCK', 'ROLE_READ_STOCK', 'ROLE_ADD_STOCK', 'ROLE_UPDATE_STOCK', 'ROLE_DELETE_STOCK',
        'ROLE_WRITE_FILE', 'ROLE_READ_FILE', 'ROLE_ADD_FILE', 'ROLE_UPDATE_FILE', 'ROLE_DELETE_FILE',
        'ROLE_WRITE_FILE_TYPE', 'ROLE_READ_FILE_TYPE', 'ROLE_ADD_FILE_TYPE', 'ROLE_UPDATE_FILE_TYPE', 'ROLE_DELETE_FILE_TYPE',
        'ROLE_WRITE_EVENT', 'ROLE_READ_EVENT', 'ROLE_ADD_EVENT', 'ROLE_UPDATE_EVENT', 'ROLE_DELETE_EVENT',
        'ROLE_WRITE_EVENT_STATUS', 'ROLE_READ_EVENT_STATUS', 'ROLE_ADD_EVENT_STATUS', 'ROLE_UPDATE_EVENT_STATUS', 'ROLE_DELETE_EVENT_STATUS',
        'ROLE_WRITE_CULLING_REGISTRATIONS_FILE', 'ROLE_READ_CULLING_REGISTRATIONS_FILE', 'ROLE_ADD_CULLING_REGISTRATIONS_FILE', 'ROLE_UPDATE_CULLING_REGISTRATIONS_FILE', 'ROLE_DELETE_CULLING_REGISTRATIONS_FILE',
        'ROLE_WRITE_ORGANIZATION', 'ROLE_READ_ORGANIZATION', 'ROLE_ADD_ORGANIZATION', 'ROLE_UPDATE_ORGANIZATION', 'ROLE_DELETE_ORGANIZATION',
        'ROLE_WRITE_PRINT_FORM', 'ROLE_READ_PRINT_FORM', 'ROLE_ADD_PRINT_FORM', 'ROLE_UPDATE_PRINT_FORM', 'ROLE_DELETE_PRINT_FORM',
        'ROLE_WRITE_FILE_OWNER', 'ROLE_READ_FILE_OWNER', 'ROLE_ADD_FILE_OWNER', 'ROLE_UPDATE_FILE_OWNER', 'ROLE_DELETE_FILE_OWNER',
        'ROLE_WRITE_IDENTIFIER', 'ROLE_READ_IDENTIFIER', 'ROLE_ADD_IDENTIFIER', 'ROLE_UPDATE_IDENTIFIER', 'ROLE_DELETE_IDENTIFIER',
        'ROLE_WRITE_IDENTIFIER_TYPE', 'ROLE_READ_IDENTIFIER_TYPE', 'ROLE_ADD_IDENTIFIER_TYPE', 'ROLE_UPDATE_IDENTIFIER_TYPE', 'ROLE_DELETE_IDENTIFIER_TYPE',
        'ROLE_WRITE_APPOINTMENT_TEMPLATE', 'ROLE_READ_APPOINTMENT_TEMPLATE', 'ROLE_ADD_APPOINTMENT_TEMPLATE', 'ROLE_UPDATE_APPOINTMENT_TEMPLATE', 'ROLE_DELETE_APPOINTMENT_TEMPLATE',
        'ROLE_READ_PET_LEAR', 'ROLE_ADD_PET_LEAR',
        'ROLE_READ_CASH_RECEIPT', 'ROLE_READ_CASH_REGISTER', 'ROLE_READ_SHIFT',
        'ROLE_WRITE_PRODUCT_RECEIPT', 'ROLE_READ_PRODUCT_RECEIPT', 'ROLE_DELETE_PRODUCT_RECEIPT', 'ROLE_ADD_PRODUCT_RECEIPT', 'ROLE_UPDATE_PRODUCT_RECEIPT',
        'ROLE_WRITE_PRODUCT_TRANSFER', 'ROLE_READ_PRODUCT_TRANSFER', 'ROLE_DELETE_PRODUCT_TRANSFER', 'ROLE_ADD_PRODUCT_TRANSFER', 'ROLE_UPDATE_PRODUCT_TRANSFER',
        'ROLE_WRITE_PRODUCT_INVENTORY', 'ROLE_READ_PRODUCT_INVENTORY', 'ROLE_DELETE_PRODUCT_INVENTORY', 'ROLE_ADD_PRODUCT_INVENTORY', 'ROLE_UPDATE_PRODUCT_INVENTORY',
        'ROLE_READ_FORM_TEMPLATE', 'ROLE_ADD_FORM_TEMPLATE', 'ROLE_UPDATE_FORM_TEMPLATE', 'ROLE_DELETE_FORM_TEMPLATE',
        'ROLE_READ_FORM_FIELD', 'ROLE_ADD_FORM_FIELD', 'ROLE_UPDATE_FORM_FIELD', 'ROLE_DELETE_FORM_FIELD',
        'ROLE_READ_FORM_FIELD_PROPERTY', 'ROLE_ADD_FORM_FIELD_PROPERTY', 'ROLE_UPDATE_FORM_FIELD_PROPERTY', 'ROLE_DELETE_FORM_FIELD_PROPERTY',
        'ROLE_READ_FORM_TEMPLATE', 'ROLE_ADD_FORM_TEMPLATE', 'ROLE_UPDATE_FORM_TEMPLATE', 'ROLE_DELETE_FORM_TEMPLATE',
        'ROLE_READ_FORM_FIELD', 'ROLE_ADD_FORM_FIELD', 'ROLE_UPDATE_FORM_FIELD', 'ROLE_DELETE_FORM_FIELD',
        'ROLE_READ_FORM_FIELD_PROPERTY', 'ROLE_ADD_FORM_FIELD_PROPERTY', 'ROLE_UPDATE_FORM_FIELD_PROPERTY', 'ROLE_DELETE_FORM_FIELD_PROPERTY',
    ];

    const ROLES_ADMIN = [
        'ROLE_READ_APPOINTMENT', 'ROLE_ADD_APPOINTMENT', 'ROLE_UPDATE_APPOINTMENT', 'ROLE_DELETE_APPOINTMENT',
        'ROLE_READ_APPOINTMENT_TYPE', 'ROLE_ADD_APPOINTMENT_TYPE', 'ROLE_UPDATE_APPOINTMENT_TYPE', 'ROLE_DELETE_APPOINTMENT_TYPE',
        'ROLE_READ_OWNER', 'ROLE_ADD_OWNER', 'ROLE_UPDATE_OWNER', 'ROLE_DELETE_OWNER',
        'ROLE_READ_TEMPERATURE', 'ROLE_ADD_TEMPERATURE', 'ROLE_UPDATE_TEMPERATURE', 'ROLE_DELETE_TEMPERATURE',
        'ROLE_READ_WEIGHT', 'ROLE_ADD_WEIGHT', 'ROLE_UPDATE_WEIGHT', 'ROLE_DELETE_WEIGHT',
        'ROLE_READ_PET', 'ROLE_ADD_PET', 'ROLE_UPDATE_PET', 'ROLE_DELETE_PET',
        'ROLE_READ_PET_TO_OWNER', 'ROLE_ADD_PET_TO_OWNER', 'ROLE_UPDATE_PET_TO_OWNER', 'ROLE_DELETE_PET_TO_OWNER',
        'ROLE_READ_APPOINTMENT_STATUS', 'ROLE_ADD_APPOINTMENT_STATUS', 'ROLE_UPDATE_APPOINTMENT_STATUS', 'ROLE_DELETE_APPOINTMENT_STATUS',
        'ROLE_READ_BREED', 'ROLE_ADD_BREED', 'ROLE_UPDATE_BREED', 'ROLE_DELETE_BREED',
        'ROLE_READ_ACTIVITY', 'ROLE_ADD_ACTIVITY', 'ROLE_UPDATE_ACTIVITY', 'ROLE_DELETE_ACTIVITY',
        'ROLE_READ_LEGAL_FORM', 'ROLE_ADD_LEGAL_FORM', 'ROLE_UPDATE_LEGAL_FORM', 'ROLE_DELETE_LEGAL_FORM',
        'ROLE_READ_STATUS', 'ROLE_ADD_STATUS', 'ROLE_UPDATE_STATUS', 'ROLE_DELETE_STATUS',
        'ROLE_READ_PET_TYPE', 'ROLE_ADD_PET_TYPE', 'ROLE_UPDATE_PET_TYPE', 'ROLE_DELETE_PET_TYPE',
        'ROLE_READ_UNIT', 'ROLE_ADD_UNIT', 'ROLE_UPDATE_UNIT', 'ROLE_DELETE_UNIT',
        'ROLE_READ_WILD_ANIMAL', 'ROLE_ADD_WILD_ANIMAL', 'ROLE_UPDATE_WILD_ANIMAL', 'ROLE_DELETE_WILD_ANIMAL',
        'ROLE_READ_UPLOADED_FILE', 'ROLE_ADD_UPLOADED_FILE', 'ROLE_UPDATE_UPLOADED_FILE', 'ROLE_DELETE_UPLOADED_FILE',
        'ROLE_READ_EMAIL', 'ROLE_UPDATE_EMAIL', 'ROLE_DELETE_EMAIL',
        'ROLE_READ_USER', 'ROLE_UPDATE_USER', 'ROLE_DELETE_USER',
        'ROLE_READ_ANIMAL_DEATH', 'ROLE_ADD_ANIMAL_DEATH', 'ROLE_UPDATE_ANIMAL_DEATH', 'ROLE_DELETE_ANIMAL_DEATH',
        'ROLE_READ_PRODUCT', 'ROLE_ADD_PRODUCT', 'ROLE_UPDATE_PRODUCT', 'ROLE_DELETE_PRODUCT',
        'ROLE_READ_PROFESSION', 'ROLE_ADD_PROFESSION', 'ROLE_UPDATE_PROFESSION', 'ROLE_DELETE_PROFESSION',
        'ROLE_READ_WILD_ANIMAL', 'ROLE_ADD_WILD_ANIMAL', 'ROLE_UPDATE_WILD_ANIMAL', 'ROLE_DELETE_WILD_ANIMAL',
        'ROLE_READ_CONTRACTOR', 'ROLE_ADD_CONTRACTOR', 'ROLE_UPDATE_CONTRACTOR', 'ROLE_DELETE_CONTRACTOR',
        'ROLE_READ_CULLING_REGISTRATION', 'ROLE_ADD_CULLING_REGISTRATION', 'ROLE_UPDATE_CULLING_REGISTRATION', 'ROLE_DELETE_CULLING_REGISTRATION',
        'ROLE_READ_SHELTER', 'ROLE_ADD_SHELTER', 'ROLE_UPDATE_SHELTER', 'ROLE_DELETE_SHELTER',
        'ROLE_READ_WILD_ANIMAL_FILE', 'ROLE_ADD_WILD_ANIMAL_FILE', 'ROLE_UPDATE_WILD_ANIMAL_FILE', 'ROLE_DELETE_WILD_ANIMAL_FILE',
        'ROLE_READ_TAG_COLOR', 'ROLE_ADD_TAG_COLOR', 'ROLE_UPDATE_TAG_COLOR', 'ROLE_DELETE_TAG_COLOR',
        'ROLE_READ_TAG_FORM', 'ROLE_ADD_TAG_FORM', 'ROLE_UPDATE_TAG_FORM', 'ROLE_DELETE_TAG_FORM',
        'ROLE_READ_STOCK', 'ROLE_ADD_STOCK', 'ROLE_UPDATE_STOCK', 'ROLE_DELETE_STOCK',
        'ROLE_READ_FILE', 'ROLE_ADD_FILE', 'ROLE_UPDATE_FILE', 'ROLE_DELETE_FILE',
        'ROLE_READ_FILE_TYPE', 'ROLE_ADD_FILE_TYPE', 'ROLE_UPDATE_FILE_TYPE', 'ROLE_DELETE_FILE_TYPE',
        'ROLE_READ_EVENT', 'ROLE_ADD_EVENT', 'ROLE_UPDATE_EVENT', 'ROLE_DELETE_EVENT',
        'ROLE_READ_EVENT_STATUS', 'ROLE_ADD_EVENT_STATUS', 'ROLE_UPDATE_EVENT_STATUS', 'ROLE_DELETE_EVENT_STATUS',
        'ROLE_READ_CULLING_REGISTRATIONS_FILE', 'ROLE_ADD_CULLING_REGISTRATIONS_FILE', 'ROLE_UPDATE_CULLING_REGISTRATIONS_FILE', 'ROLE_DELETE_CULLING_REGISTRATIONS_FILE',
        'ROLE_READ_ORGANIZATION', 'ROLE_ADD_ORGANIZATION', 'ROLE_UPDATE_ORGANIZATION', 'ROLE_DELETE_ORGANIZATION',
        'ROLE_READ_PRINT_FORM', 'ROLE_ADD_PRINT_FORM', 'ROLE_UPDATE_PRINT_FORM', 'ROLE_DELETE_PRINT_FORM',
        'ROLE_READ_FILE_OWNER', 'ROLE_ADD_FILE_OWNER', 'ROLE_UPDATE_FILE_OWNER', 'ROLE_DELETE_FILE_OWNER',
        'ROLE_READ_CLIENT', 'ROLE_ADD_CLIENT', 'ROLE_UPDATE_CLIENT', 'ROLE_DELETE_CLIENT',
        'ROLE_READ_CLIENT_GROUP', 'ROLE_ADD_CLIENT_GROUP', 'ROLE_UPDATE_CLIENT_GROUP', 'ROLE_DELETE_CLIENT_GROUP',
        'ROLE_READ_GROUP', 'ROLE_ADD_GROUP', 'ROLE_UPDATE_GROUP', 'ROLE_DELETE_GROUP',
        'ROLE_READ_FTP_HISTORY', 'ROLE_ADD_FTP_HISTORY', 'ROLE_UPDATE_FTP_HISTORY', 'ROLE_DELETE_FTP_HISTORY',
        'ROLE_READ_IDENTIFIER_HISTORY', 'ROLE_ADD_IDENTIFIER_HISTORY', 'ROLE_UPDATE_IDENTIFIER_HISTORY', 'ROLE_DELETE_IDENTIFIER_HISTORY',
        'ROLE_READ_MONITORED_OBJECT', 'ROLE_ADD_MONITORED_OBJECT', 'ROLE_UPDATE_MONITORED_OBJECT', 'ROLE_DELETE_MONITORED_OBJECT',
        'ROLE_READ_FILE_MONITORED_OBJECT', 'ROLE_ADD_FILE_MONITORED_OBJECT', 'ROLE_UPDATE_FILE_MONITORED_OBJECT', 'ROLE_DELETE_FILE_MONITORED_OBJECT',
        'ROLE_READ_IDENTIFIER', 'ROLE_ADD_IDENTIFIER', 'ROLE_UPDATE_IDENTIFIER', 'ROLE_DELETE_IDENTIFIER',
        'ROLE_READ_EVENT_TYPE', 'ROLE_ADD_EVENT_TYPE', 'ROLE_UPDATE_EVENT_TYPE', 'ROLE_DELETE_EVENT_TYPE',
        'ROLE_READ_IDENTIFIER_TYPE', 'ROLE_ADD_IDENTIFIER_TYPE', 'ROLE_UPDATE_IDENTIFIER_TYPE', 'ROLE_DELETE_IDENTIFIER_TYPE',
        'ROLE_READ_ROLE', 'ROLE_ADD_ROLE', 'ROLE_UPDATE_ROLE', 'ROLE_DELETE_ROLE',
        'ROLE_READ_TEMPLATE', 'ROLE_ADD_TEMPLATE', 'ROLE_UPDATE_TEMPLATE', 'ROLE_DELETE_TEMPLATE',
        'ROLE_READ_THEME', 'ROLE_ADD_THEME', 'ROLE_UPDATE_THEME', 'ROLE_DELETE_THEME',
        'ROLE_READ_SETTINGS', 'ROLE_ADD_SETTINGS', 'ROLE_UPDATE_SETTINGS',
        'ROLE_READ_CASH_RECEIPT', 'ROLE_ADD_CASH_RECEIPT', 'ROLE_UPDATE_CASH_RECEIPT', 'ROLE_DELETE_CASH_RECEIPT',
        'ROLE_READ_CASH_REGISTER', 'ROLE_ADD_CASH_REGISTER', 'ROLE_UPDATE_CASH_REGISTER', 'ROLE_DELETE_CASH_REGISTER',
        'ROLE_READ_CASH_REGISTER_SERVER', 'ROLE_ADD_CASH_REGISTER_SERVER', 'ROLE_UPDATE_CASH_REGISTER_SERVER', 'ROLE_DELETE_CASH_REGISTER_SERVER',
        'ROLE_READ_SHIFT', 'ROLE_ADD_SHIFT', 'ROLE_UPDATE_SHIFT', 'ROLE_DELETE_SHIFT',
        'ROLE_READ_CASH_FLOW', 'ROLE_ADD_CASH_FLOW', 'ROLE_UPDATE_CASH_FLOW', 'ROLE_DELETE_CASH_FLOW',
        'ROLE_READ_CASHIER_SCHEDULE', 'ROLE_ADD_CASHIER_SCHEDULE', 'ROLE_UPDATE_CASHIER_SCHEDULE', 'ROLE_DELETE_CASHIER_SCHEDULE',
        'ROLE_READ_PET_LEAR', 'ROLE_ADD_PET_LEAR', 'ROLE_UPDATE_PET_LEAR', 'ROLE_DELETE_PET_LEAR',
        'ROLE_READ_APPOINTMENT_TEMPLATE', 'ROLE_ADD_APPOINTMENT_TEMPLATE', 'ROLE_UPDATE_APPOINTMENT_TEMPLATE', 'ROLE_DELETE_APPOINTMENT_TEMPLATE',
        'ROLE_UPDATE_DOCUMENTS_STATE',
        'ROLE_READ_PRODUCT_RECEIPT', 'ROLE_DELETE_PRODUCT_RECEIPT', 'ROLE_ADD_PRODUCT_RECEIPT', 'ROLE_UPDATE_PRODUCT_RECEIPT',
        'ROLE_READ_PRODUCT_TRANSFER', 'ROLE_DELETE_PRODUCT_TRANSFER', 'ROLE_ADD_PRODUCT_TRANSFER', 'ROLE_UPDATE_PRODUCT_TRANSFER',
        'ROLE_READ_PRODUCT_INVENTORY', 'ROLE_DELETE_PRODUCT_INVENTORY', 'ROLE_ADD_PRODUCT_INVENTORY', 'ROLE_UPDATE_PRODUCT_INVENTORY',
        'ROLE_UPDATE_RECEIPT_DOCUMENT_STATE', 'ROLE_UPDATE_TRANSFER_DOCUMENT_STATE', 'ROLE_UPDATE_INVENTORY_DOCUMENT_STATE', 'ROLE_UPDATE_APPOINTMENT_STATE',
        'ROLE_WRITE_PRODUCT_RECEIPT', 'ROLE_READ_PRODUCT_RECEIPT', 'ROLE_DELETE_PRODUCT_RECEIPT', 'ROLE_ADD_PRODUCT_RECEIPT', 'ROLE_UPDATE_PRODUCT_RECEIPT',
        'ROLE_WRITE_PRODUCT_TRANSFER', 'ROLE_READ_PRODUCT_TRANSFER', 'ROLE_DELETE_PRODUCT_TRANSFER', 'ROLE_ADD_PRODUCT_TRANSFER', 'ROLE_UPDATE_PRODUCT_TRANSFER',
        'ROLE_WRITE_PRODUCT_INVENTORY', 'ROLE_READ_PRODUCT_INVENTORY', 'ROLE_DELETE_PRODUCT_INVENTORY', 'ROLE_ADD_PRODUCT_INVENTORY', 'ROLE_UPDATE_PRODUCT_INVENTORY',
        'ROLE_READ_FORM_TEMPLATE', 'ROLE_ADD_FORM_TEMPLATE', 'ROLE_UPDATE_FORM_TEMPLATE', 'ROLE_DELETE_FORM_TEMPLATE',
        'ROLE_READ_FORM_FIELD', 'ROLE_ADD_FORM_FIELD', 'ROLE_UPDATE_FORM_FIELD', 'ROLE_DELETE_FORM_FIELD',
        'ROLE_READ_FORM_FIELD_PROPERTY', 'ROLE_ADD_FORM_FIELD_PROPERTY', 'ROLE_UPDATE_FORM_FIELD_PROPERTY', 'ROLE_DELETE_FORM_FIELD_PROPERTY',
    ];


    protected static $defaultName = 'app:update-group-roles';

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * UpdateGroupRolesCommand constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this
            ->setDescription('Update roles for chosen group')
            ->addArgument('group', InputArgument::OPTIONAL, 'Which group')
            ->addOption('detail', 'd', InputOption::VALUE_OPTIONAL, 'show information');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $groupList = [
            ['name' => 'Суперадминистраторы', 'code' => 'ROOT'],
            ['name' => 'Кассир', 'code' => 'ROLE_CASHIER'],
            ['name' => 'Ветеринарный врач', 'code' => '4'],
            ['name' => 'Бухгалтер', 'code' =>  '8'],
            ['name' => 'Администратор приемов', 'code' =>  'ROLE_APPOINTMENT_ADMIN'],
            ['name' => 'Администратор выездов', 'code' =>  'ROLE_LEAVING_ADMIN'],
            ['name' => 'Врач', 'code' =>  'ROLE_DOCTOR']
        ];
        $baseMenuRoles = ['ROLE_MENU_APPOINTMENT', 'ROLE_MENU_OWNER_CREATE', 'ROLE_MENU_APPOINTMENT_APPOINTMENTS', 'ROLE_MENU_APPOINTMENT_OWNER', 'ROLE_MENU_APPOINTMENT_PET'];
        $doctorMenuRoles = array_merge($baseMenuRoles, ['ROLE_DOCTOR']);
        $cashierMenuRoles = array_merge($baseMenuRoles, ['ROLE_MENU_CASH', 'ROLE_MENU_CASH_CURRENT_SCHEDULE', 'ROLE_MENU_CASH_RECEIPT', 'ROLE_MENU_CASH_SHIFT', 'ROLE_CASHIER', 'ROLE_ADMIN_CASH_REGISTER',
            'ROLE_MENU_CASH_CASHIER_SCHEDULE', 'ROLE_MENU_CASH_REGISTER', 'ROLE_MENU_CASH_REGISTER_SERVER', 'ROLE_MENU_CASH_ORGANIZATION', 'ROLE_MENU_CASH_UNIT', 'ROLE_MENU_CASH_FLOW']);
        $accountantMenuRoles = array_merge($baseMenuRoles, ['ROLE_MENU_STOCK', 'ROLE_MENU_PRODUCT', 'ROLE_MENU_PRODUCT_RECEIPT', 'ROLE_MENU_PRODUCT_TRANSFER', 'ROLE_MENU_PRODUCT_INVENTORY',
            'ROLE_MENU_PRODUCT_REMAINS', 'ROLE_MENU_PRODUCT_HISTORY', 'ROLE_MENU_PRODUCT_FTP_HISTORY']);
        $adminMenuRoles = array_merge($cashierMenuRoles, [
            'ROLE_MENU_STOCK', 'ROLE_MENU_PRODUCT', 'ROLE_MENU_PRODUCT_RECEIPT', 'ROLE_MENU_PRODUCT_TRANSFER', 'ROLE_MENU_PRODUCT_INVENTORY', 'ROLE_MENU_PRODUCT_REMAINS',
            'ROLE_MENU_PRODUCT_HISTORY', 'ROLE_MENU_PRODUCT_FTP_HISTORY', 'ROLE_MENU_ADMIN_APPOINTMENT_TEMPLATE', 'ROLE_MENU_ADMIN_USER_SCHEDULE',
            'ROLE_MENU_ADMIN_SETTINGS', 'ROLE_MENU_ADMIN_ROLE', 'ROLE_MENU_ADMIN_GROUP', 'ROLE_MENU_ADMIN_USER', 'ROLE_MENU_ADMIN_REFERENCES', 'ROLE_MENU_ADMINISTRATION',
            'ROLE_MENU_CULLING', 'ROLE_MENU_REPORT_WAREHOUSE', 'ROLE_MENU_REPORT_REVENUE', 'ROLE_MENU_REPORT_SHIFT', 'ROLE_MENU_REPORT', 'ROLE_MENU_APPOINTMENT_SCHEDULE',
            'ROLE_SENIOR_CASHIER', 'ROLE_DOCTOR', 'ROLE_ROOT'
        ]);
        foreach ($groupList as $group) {
            $this->updateGroupWithRoles($group['name'], $group['code'], self::ROLES_ADMIN, $output);
            $menuRoles = [];
            switch ($group['name']) {
                case 'Врач':
                case 'Ветеринарный врач':
                    $menuRoles = $doctorMenuRoles;
                    break;
                case 'Администратор приемов':
                case 'Администратор выездов':
                case 'Кассир':
                    $menuRoles = $cashierMenuRoles;
                    break;
                case 'Бухгалтер':
                    $menuRoles = $accountantMenuRoles;
                    break;
                case 'Суперадминистраторы':
                    $menuRoles = $adminMenuRoles;
                    break;
            }
            $this->updateGroupWithRoles($group['name'], $group['code'], $menuRoles, $output, false);

        }
//        $this->updateGroupWithRoles('Суперадминистраторы', 'ROOT', self::ROLES_ADMIN, $output);
//        $this->updateGroupWithRoles('Кассир', 'ROLE_CASHIER', self::ROLES_ADMIN, $output);
//        $this->updateGroupWithRoles('Ветеринарный врач', '4', self::ROLES_ADMIN, $output);
//        $this->updateGroupWithRoles('Бухгалтер', '8', self::ROLES_ADMIN, $output);
//        $this->updateGroupWithRoles('Администратор приемов', 'ROLE_APPOINTMENT_ADMIN', self::ROLES_ADMIN, $output);
//        $this->updateGroupWithRoles('Врач', 'ROLE_DOCTOR', self::ROLES_ADMIN, $output);
//        $this->updateGroupWithRoles('Регистратор', 'ROLE_RECEPTIONIST', self::ROLES_RECEPTIONIST, $output);
//        $this->updateGroupWithRoles('Администраторы', 'ADMIN', self::ROLES_ADMIN, $output);
        return Command::SUCCESS;

    }

    /**
     * @param string $name
     * @param string $code
     * @param string[] $rolesArray
     * @param OutputInterface $output
     * @param boolean $clearRoles
     */
    private function updateGroupWithRoles($name, $code, $rolesArray, $output, $clearRoles = true)
    {
        /** @var Group $group */
        $group = $this->entityManager->getRepository(Group::class)->findOneBy(['name' => $name]);
        if (!$group) {
            $group = new Group();
            $group->setName($name);
            $group->setCode($code);
            $this->entityManager->persist($group);
        }

        /**@var Role $roles */
        $roles = $this->entityManager->getRepository(Role::class)->findAll();
        foreach ($roles as $role) {
            $group->removeRole($role);
        }

        foreach ($rolesArray as $item) {
            /** @var Role $role */
            $role = $this->entityManager->getRepository(Role::class)->findOneBy([
                'code' => $item
            ]);
            if (!$role) {
                $output->writeln(sprintf('Role %s does not exist.', $item));
                continue;
            }
            $group->addRole($role);
            $output->writeln(sprintf('Role %s added to group %s.', $role->getName(), $group->getName()));
        }
        $this->entityManager->flush();
    }
}

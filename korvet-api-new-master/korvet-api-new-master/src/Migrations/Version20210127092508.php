<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210127092508 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA IF NOT EXISTS cash');
        $this->addSql('ALTER TABLE cash_flow SET SCHEMA cash;');
        $this->addSql('ALTER TABLE cash_receipt SET SCHEMA cash;');
        $this->addSql('ALTER TABLE cash_register SET SCHEMA cash;');
        $this->addSql('ALTER TABLE cash_register_server SET SCHEMA cash;');
        $this->addSql('COMMENT ON COLUMN cash.cash_flow.type IS \'(DC2Type:App\\DBAL\\Types\\CashFlowTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN cash.cash_flow.fiscal_state IS \'(DC2Type:App\\DBAL\\Types\\FiscalReceiptStateEnum)\'');

        $this->addSql('COMMENT ON COLUMN cash.cash_receipt.type IS \'(DC2Type:App\\DBAL\\Types\\CashReceiptTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN cash.cash_receipt.taxation_type IS \'(DC2Type:App\\DBAL\\Types\\TaxationTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN cash.cash_receipt.delivery_type IS \'(DC2Type:App\\DBAL\\Types\\ReceiptDeliveryTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN cash.cash_receipt.payment_method IS \'(DC2Type:App\\DBAL\\Types\\PaymentMethodTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN cash.cash_receipt.payment_type IS \'(DC2Type:App\\DBAL\\Types\\PaymentTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN cash.cash_receipt.correction_type IS \'(DC2Type:App\\DBAL\\Types\\CorrectionTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN cash.cash_receipt.fiscal_state IS \'(DC2Type:App\\DBAL\\Types\\FiscalReceiptStateEnum)\'');
        $this->addSql('COMMENT ON COLUMN shift.fiscal_open_state IS \'(DC2Type:App\\DBAL\\Types\\FiscalReceiptStateEnum)\'');
        $this->addSql('COMMENT ON COLUMN shift.fiscal_close_state IS \'(DC2Type:App\\DBAL\\Types\\FiscalReceiptStateEnum)\'');
        $this->addSql('COMMENT ON COLUMN receipt_item.payment_object IS \'(DC2Type:App\\DBAL\\Types\\PaymentObjectEnum)\'');
        $this->addSql('COMMENT ON COLUMN receipt_item.vat_rate IS \'(DC2Type:App\\DBAL\\Types\\VatRateEnum)\'');
        $this->addSql('COMMENT ON COLUMN organization.taxation_type IS \'(DC2Type:App\\DBAL\\Types\\TaxationTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN receipt_item.payment_object IS \'(DC2Type:App\\DBAL\\Types\\PaymentObjectEnum)\'');
        $this->addSql('COMMENT ON COLUMN receipt_item.vat_rate IS \'(DC2Type:App\\DBAL\\Types\\VatRateEnum)\'');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE cash.cash_flow SET SCHEMA public;');
        $this->addSql('ALTER TABLE cash.cash_receipt SET SCHEMA public;');
        $this->addSql('ALTER TABLE cash.cash_register SET SCHEMA public;');
        $this->addSql('ALTER TABLE cash.cash_register_server SET SCHEMA public;');
        $this->addSql('DROP SCHEMA IF EXISTS cash');
        $this->addSql('COMMENT ON COLUMN cash_flow.type IS \'(DC2Type:Webslon\\Bundle\\CashierEquipmentBundle\\DBAL\\Types\\CashFlowTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN cash_flow.fiscal_state IS \'(DC2Type:Webslon\\Bundle\\CashierEquipmentBundle\\DBAL\\Types\\FiscalReceiptStateEnum)\'');

        $this->addSql('COMMENT ON COLUMN cash_receipt.type IS \'(DC2Type:Webslon\\Bundle\\CashierEquipmentBundle\\DBAL\\Types\\CashReceiptTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN cash_receipt.taxation_type IS \'(DC2Type:Webslon\\Bundle\\CashierEquipmentBundle\\DBAL\\Types\\TaxationTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN cash_receipt.delivery_type IS \'(DC2Type:Webslon\\Bundle\\CashierEquipmentBundle\\DBAL\\Types\\ReceiptDeliveryTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN cash_receipt.payment_method IS \'(DC2Type:Webslon\\Bundle\\CashierEquipmentBundle\\DBAL\\Types\\PaymentMethodTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN cash_receipt.payment_type IS \'(DC2Type:Webslon\\Bundle\\CashierEquipmentBundle\\DBAL\\Types\\PaymentTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN cash_receipt.correction_type IS \'(DC2Type:Webslon\\Bundle\\CashierEquipmentBundle\\DBAL\\Types\\CorrectionTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN cash_receipt.fiscal_state IS \'(DC2Type:Webslon\\Bundle\\CashierEquipmentBundle\\DBAL\\Types\\FiscalReceiptStateEnum)\'');
        $this->addSql('COMMENT ON COLUMN shift.fiscal_open_state IS \'(DC2Type:Webslon\\Bundle\\CashierEquipmentBundle\\DBAL\\Types\\FiscalReceiptStateEnum)\'');
        $this->addSql('COMMENT ON COLUMN shift.fiscal_close_state IS \'(DC2Type:Webslon\\Bundle\\CashierEquipmentBundle\\DBAL\\Types\\FiscalReceiptStateEnum)\'');
        $this->addSql('COMMENT ON COLUMN receipt_item.payment_object IS \'(DC2Type:Webslon\\Bundle\\CashierEquipmentBundle\\DBAL\\Types\\PaymentObjectEnum)\'');
        $this->addSql('COMMENT ON COLUMN receipt_item.vat_rate IS \'(DC2Type:Webslon\\Bundle\\CashierEquipmentBundle\\DBAL\\Types\\VatRateEnum)\'');
        $this->addSql('COMMENT ON COLUMN receipt_item.address_type IS \'(DC2Type:Webslon\\Bundle\\CashierEquipmentBundle\\DBAL\\Types\\ProductCodeTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN organization.taxation_type IS \'(DC2Type:Webslon\\Bundle\\CashierEquipmentBundle\\DBAL\\Types\\TaxationTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN receipt_item.payment_object IS \'(DC2Type:Webslon\\Bundle\\CashierEquipmentBundle\\DBAL\\Types\\PaymentObjectEnum)\'');
        $this->addSql('COMMENT ON COLUMN receipt_item.vat_rate IS \'(DC2Type:Webslon\\Bundle\\CashierEquipmentBundle\\DBAL\\Types\\VatRateEnum)\'');
        $this->addSql('COMMENT ON COLUMN receipt_item.address_type IS \'(DC2Type:Webslon\\Bundle\\CashierEquipmentBundle\\DBAL\\Types\\ProductCodeTypeEnum)\'');

    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210219984118 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('COMMENT ON COLUMN action.action.type IS \'(DC2Type:App\\Packages\\DBAL\\Types\\ActionTypeEnum)\'');

        $this->addSql('COMMENT ON COLUMN product.product.payment_object IS \'(DC2Type:App\\Packages\\DBAL\\Types\\PaymentObjectEnum)\'');
        $this->addSql('COMMENT ON COLUMN product.product.vat_rate IS \'(DC2Type:App\\Packages\\DBAL\\Types\\VatRateEnum)\'');
        $this->addSql('COMMENT ON COLUMN product.product.product_code_type IS \'(DC2Type:App\\Packages\\DBAL\\Types\\ProductCodeTypeEnum)\'');

        $this->addSql('COMMENT ON COLUMN shift.fiscal_open_state IS \'(DC2Type:App\\Packages\\DBAL\\Types\\FiscalReceiptStateEnum)\'');
        $this->addSql('COMMENT ON COLUMN shift.fiscal_close_state IS \'(DC2Type:App\\Packages\\DBAL\\Types\\FiscalReceiptStateEnum)\'');

        $this->addSql('COMMENT ON COLUMN cash.cash_flow.type IS \'(DC2Type:App\\Packages\\DBAL\\Types\\CashFlowTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN cash.cash_flow.fiscal_state IS \'(DC2Type:App\\Packages\\DBAL\\Types\\FiscalReceiptStateEnum)\'');

        $this->addSql('COMMENT ON COLUMN receipt_item.payment_object IS \'(DC2Type:App\\Packages\\DBAL\\Types\\PaymentObjectEnum)\'');
        $this->addSql('COMMENT ON COLUMN receipt_item.vat_rate IS \'(DC2Type:App\\Packages\\DBAL\\Types\\VatRateEnum)\'');
        $this->addSql('COMMENT ON COLUMN receipt_item.product_code_type IS \'(DC2Type:App\\Packages\\DBAL\\Types\\ProductCodeTypeEnum)\'');

        $this->addSql('COMMENT ON COLUMN cash.cash_receipt.type IS \'(DC2Type:App\\Packages\\DBAL\\Types\\CashReceiptTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN cash.cash_receipt.taxation_type IS \'(DC2Type:App\\Packages\\DBAL\\Types\\TaxationTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN cash.cash_receipt.delivery_type IS \'(DC2Type:App\\Packages\\DBAL\\Types\\ReceiptDeliveryTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN cash.cash_receipt.payment_method IS \'(DC2Type:App\\Packages\\DBAL\\Types\\PaymentMethodEnum)\'');
        $this->addSql('COMMENT ON COLUMN cash.cash_receipt.payment_type IS \'(DC2Type:App\\Packages\\DBAL\\Types\\PaymentTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN cash.cash_receipt.correction_type IS \'(DC2Type:App\\Packages\\DBAL\\Types\\CorrectionTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN cash.cash_receipt.fiscal_state IS \'(DC2Type:App\\Packages\\DBAL\\Types\\FiscalReceiptStateEnum)\'');
        $this->addSql('COMMENT ON COLUMN receipt_item.payment_object IS \'(DC2Type:App\\Packages\\DBAL\\Types\\PaymentObjectEnum)\'');
        $this->addSql('COMMENT ON COLUMN receipt_item.vat_rate IS \'(DC2Type:App\\Packages\\DBAL\\Types\\VatRateEnum)\'');
        $this->addSql('COMMENT ON COLUMN organization.taxation_type IS \'(DC2Type:App\\Packages\\DBAL\\Types\\TaxationTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN receipt_item.payment_object IS \'(DC2Type:App\\Packages\\DBAL\\Types\\PaymentObjectEnum)\'');
        $this->addSql('COMMENT ON COLUMN receipt_item.vat_rate IS \'(DC2Type:App\\Packages\\DBAL\\Types\\VatRateEnum)\'');

        $this->addSql('COMMENT ON COLUMN appointment.appointments.payment_state IS \'(DC2Type:App\\Packages\\DBAL\\Types\\PaymentStateEnum)\'');
        $this->addSql('COMMENT ON COLUMN appointment.appointments.payment_type IS \'(DC2Type:App\\Packages\\DBAL\\Types\\PaymentTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN appointment.appointments.payment_type IS \'(DC2Type:App\\Enum\\DocumentStateEnum)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
    }
}

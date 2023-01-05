<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210127114856 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA IF NOT EXISTS product');
        $this->addSql('ALTER TABLE product SET SCHEMA product;');
        $this->addSql('ALTER TABLE product_expense SET SCHEMA product;');
        $this->addSql('ALTER TABLE product_expense_document_product SET SCHEMA product;');
        $this->addSql('ALTER TABLE product_inventory SET SCHEMA product;');
        $this->addSql('ALTER TABLE product_inventory_inventory_document_product SET SCHEMA product;');
        $this->addSql('ALTER TABLE product_receipt SET SCHEMA product;');
        $this->addSql('ALTER TABLE product_receipt_document_product SET SCHEMA product;');
        $this->addSql('ALTER TABLE product_service SET SCHEMA product;');
        $this->addSql('ALTER TABLE product_stock SET SCHEMA product;');
        $this->addSql('ALTER TABLE IF EXISTS product_stock_logs SET SCHEMA product;');
        $this->addSql('ALTER TABLE product_transfer SET SCHEMA product;');
        $this->addSql('ALTER TABLE product_transfer_document_product SET SCHEMA product;');


        $this->addSql('COMMENT ON COLUMN product.product.payment_object IS \'(DC2Type:App\\DBAL\\Types\\PaymentObjectEnum)\'');
        $this->addSql('COMMENT ON COLUMN product.product.vat_rate IS \'(DC2Type:App\\DBAL\\Types\\VatRateEnum)\'');
        $this->addSql('COMMENT ON COLUMN product.product.product_code_type IS \'(DC2Type:App\\DBAL\\Types\\ProductCodeTypeEnum)\'');

        $this->addSql('COMMENT ON COLUMN shift.fiscal_open_state IS \'(DC2Type:App\\DBAL\\Types\\FiscalReceiptStateEnum)\'');
        $this->addSql('COMMENT ON COLUMN shift.fiscal_close_state IS \'(DC2Type:App\\DBAL\\Types\\FiscalReceiptStateEnum)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE product.product SET SCHEMA public;');
        $this->addSql('ALTER TABLE product.product_expense SET SCHEMA public;');
        $this->addSql('ALTER TABLE product.product_expense_document_product SET SCHEMA public;');
        $this->addSql('ALTER TABLE product.product_inventory SET SCHEMA public;');
        $this->addSql('ALTER TABLE product.product_inventory_inventory_document_product SET SCHEMA public;');
        $this->addSql('ALTER TABLE product.product_receipt SET SCHEMA public;');
        $this->addSql('ALTER TABLE product.product_receipt_document_product SET SCHEMA public;');
        $this->addSql('ALTER TABLE product.product_service SET SCHEMA public;');
        $this->addSql('ALTER TABLE product.product_stock SET SCHEMA public;');
        $this->addSql('ALTER TABLE IF EXISTS product.product_stock_logs SET SCHEMA public;');
        $this->addSql('ALTER TABLE product.product_transfer SET SCHEMA public;');
        $this->addSql('ALTER TABLE product.product_transfer_document_product SET SCHEMA public;');
        $this->addSql('DROP SCHEMA IF EXISTS product');

        $this->addSql('COMMENT ON COLUMN shift.fiscal_open_state IS \'(DC2Type:Webslon\\Bundle\\CashierEquipmentBundle\\DBAL\\Types\\FiscalReceiptStateEnum)\'');
        $this->addSql('COMMENT ON COLUMN shift.fiscal_close_state IS \'(DC2Type:Webslon\\Bundle\\CashierEquipmentBundle\\DBAL\\Types\\FiscalReceiptStateEnum)\'');
    }
}

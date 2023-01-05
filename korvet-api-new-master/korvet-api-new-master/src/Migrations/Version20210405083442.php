<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210405083442 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA shop');
        $this->addSql('CREATE TABLE shop.shop_order (id SERIAL NOT NULL, user_id INT DEFAULT NULL, unit_id INT DEFAULT NULL, cash_receipt_id INT DEFAULT NULL, stock_id INT DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, payment_type VARCHAR(255) DEFAULT NULL, errors TEXT DEFAULT NULL, uuid UUID DEFAULT NULL, state VARCHAR(255) DEFAULT \'DRAFT\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_77E0A9DFD17F50A6 ON shop.shop_order (uuid)');
        $this->addSql('CREATE INDEX IDX_77E0A9DFA76ED395 ON shop.shop_order (user_id)');
        $this->addSql('CREATE INDEX IDX_77E0A9DFF8BD700D ON shop.shop_order (unit_id)');
        $this->addSql('CREATE INDEX IDX_77E0A9DF9FB2A19C ON shop.shop_order (cash_receipt_id)');
        $this->addSql('CREATE INDEX IDX_77E0A9DFDCD6110 ON shop.shop_order (stock_id)');
        $this->addSql('COMMENT ON COLUMN shop.shop_order.payment_type IS \'(DC2Type:App\\Packages\\DBAL\\Types\\PaymentTypeEnum)\'');
        $this->addSql('COMMENT ON COLUMN shop.shop_order.errors IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN shop.shop_order.state IS \'(DC2Type:App\\Enum\\DocumentStateEnum)\'');
        $this->addSql('COMMENT ON COLUMN shop.shop_order.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE shop.shop_product_item (id SERIAL NOT NULL, product_id INT NOT NULL, user_id INT DEFAULT NULL, stock_id INT DEFAULT NULL, shop_order_id INT DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, quantity DOUBLE PRECISION DEFAULT NULL, amount DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_15EB9C994584665A ON shop.shop_product_item (product_id)');
        $this->addSql('CREATE INDEX IDX_15EB9C99A76ED395 ON shop.shop_product_item (user_id)');
        $this->addSql('CREATE INDEX IDX_15EB9C99DCD6110 ON shop.shop_product_item (stock_id)');
        $this->addSql('CREATE INDEX IDX_15EB9C99562797AE ON shop.shop_product_item (shop_order_id)');
        $this->addSql('CREATE TABLE shop.shop_settings (id SERIAL NOT NULL, unit_id INT DEFAULT NULL, data JSON NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_57819F6FF8BD700D ON shop.shop_settings (unit_id)');
        $this->addSql('ALTER TABLE shop.shop_settings ADD CONSTRAINT FK_57819F6FF8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shop.shop_order ADD CONSTRAINT FK_77E0A9DFA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shop.shop_order ADD CONSTRAINT FK_77E0A9DFF8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shop.shop_order ADD CONSTRAINT FK_77E0A9DF9FB2A19C FOREIGN KEY (cash_receipt_id) REFERENCES cash.cash_receipt (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shop.shop_order ADD CONSTRAINT FK_77E0A9DFDCD6110 FOREIGN KEY (stock_id) REFERENCES reference.reference_stock (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shop.shop_product_item ADD CONSTRAINT FK_15EB9C994584665A FOREIGN KEY (product_id) REFERENCES product.product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shop.shop_product_item ADD CONSTRAINT FK_15EB9C99A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shop.shop_product_item ADD CONSTRAINT FK_15EB9C99DCD6110 FOREIGN KEY (stock_id) REFERENCES reference.reference_stock (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shop.shop_product_item ADD CONSTRAINT FK_15EB9C99562797AE FOREIGN KEY (shop_order_id) REFERENCES shop.shop_order (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE shop.shop_settings');
        $this->addSql('DROP TABLE shop.shop_product_item');
        $this->addSql('DROP TABLE shop.shop_order');
        $this->addSql('DROP SCHEMA IF EXISTS shop');
    }
}


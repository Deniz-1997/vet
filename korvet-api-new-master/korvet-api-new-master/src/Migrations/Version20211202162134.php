<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211202162134 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("DROP SEQUENCE IF EXISTS public.product_receipt_number_seq CASCADE;");
        $this->addSql("DROP SEQUENCE IF EXISTS product.product_receipt_number_seq CASCADE;");
        $this->addSql("CREATE SEQUENCE product.product_receipt_number_seq INCREMENT BY 1 MINVALUE 1;");
        $this->addSql("ALTER TABLE product.product_receipt ALTER COLUMN number SET DEFAULT nextval('product.product_receipt_number_seq'::regclass);");
        $this->addSql("select setval('product.product_receipt_number_seq', (select max(number) from product.product_receipt));");

        $this->addSql("DROP SEQUENCE IF EXISTS PUBLIC.product_transfer_number_seq CASCADE;");
        $this->addSql("DROP SEQUENCE IF EXISTS product.product_transfer_number_seq CASCADE;");
        $this->addSql("CREATE SEQUENCE product.product_transfer_number_seq INCREMENT BY 1 MINVALUE 1;");
        $this->addSql("ALTER TABLE product.product_transfer ALTER COLUMN number SET DEFAULT nextval('product.product_transfer_number_seq'::regclass);");
        $this->addSql("select setval('product.product_transfer_number_seq', (select max(number) from product.product_transfer));");

        $this->addSql("DROP SEQUENCE IF EXISTS PUBLIC.product_inventory_number_seq CASCADE;");
        $this->addSql("DROP SEQUENCE IF EXISTS product.product_inventory_number_seq CASCADE;");
        $this->addSql("CREATE SEQUENCE product.product_inventory_number_seq INCREMENT BY 1 MINVALUE 1;");
        $this->addSql("ALTER TABLE product.product_inventory ALTER COLUMN number SET DEFAULT nextval('product.product_inventory_number_seq'::regclass);");
        $this->addSql("select setval('product.product_inventory_number_seq', (select max(number) from product.product_inventory));");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("DROP SEQUENCE IF EXISTS product.product_receipt_number_seq CASCADE;");
        $this->addSql("DROP SEQUENCE IF EXISTS product.product_transfer_number_seq CASCADE;");
        $this->addSql("DROP SEQUENCE IF EXISTS product.product_inventory_number_seq CASCADE;");
    }
}

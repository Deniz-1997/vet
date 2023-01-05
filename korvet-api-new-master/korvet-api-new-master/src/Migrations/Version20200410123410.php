<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200410123410 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE product_expense_number_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE product_expense (number INT NOT NULL, stock_id INT DEFAULT NULL, creator_id INT DEFAULT NULL, editor_id INT DEFAULT NULL, state VARCHAR(255) DEFAULT \'DRAFT\' NOT NULL, id UUID NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, errors TEXT NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(number))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FCE39616BF396750 ON product_expense (id)');
        $this->addSql('CREATE INDEX IDX_FCE39616DCD6110 ON product_expense (stock_id)');
        $this->addSql('CREATE INDEX IDX_FCE3961661220EA6 ON product_expense (creator_id)');
        $this->addSql('CREATE INDEX IDX_FCE396166995AC4C ON product_expense (editor_id)');
        $this->addSql('COMMENT ON COLUMN product_expense.state IS \'(DC2Type:App\Enum\\DocumentStateEnum)\'');
        $this->addSql('COMMENT ON COLUMN product_expense.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN product_expense.errors IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE product_expense_document_product (product_expense_number INT NOT NULL, document_product_id INT NOT NULL, PRIMARY KEY(product_expense_number, document_product_id))');
        $this->addSql('CREATE INDEX IDX_D9E75195356B06D ON product_expense_document_product (product_expense_number)');
        $this->addSql('CREATE INDEX IDX_D9E75195E447CD0C ON product_expense_document_product (document_product_id)');
        $this->addSql('ALTER TABLE product_expense ADD CONSTRAINT FK_FCE39616DCD6110 FOREIGN KEY (stock_id) REFERENCES reference_stock (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_expense ADD CONSTRAINT FK_FCE3961661220EA6 FOREIGN KEY (creator_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_expense ADD CONSTRAINT FK_FCE396166995AC4C FOREIGN KEY (editor_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_expense_document_product ADD CONSTRAINT FK_D9E75195356B06D FOREIGN KEY (product_expense_number) REFERENCES product_expense (number) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_expense_document_product ADD CONSTRAINT FK_D9E75195E447CD0C FOREIGN KEY (document_product_id) REFERENCES document_product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE product_expense_document_product DROP CONSTRAINT FK_D9E75195356B06D');
        $this->addSql('DROP SEQUENCE product_expense_number_seq CASCADE');
        $this->addSql('DROP TABLE product_expense');
        $this->addSql('DROP TABLE product_expense_document_product');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200211105949 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE product_transfer_document_product DROP CONSTRAINT FK_13290300C10847FA');
        $this->addSql('ALTER TABLE product_transfer_document_product DROP CONSTRAINT FK_13290300E447CD0C');
        $this->addSql('ALTER TABLE product_transfer_document_product ADD CONSTRAINT FK_13290300C10847FA FOREIGN KEY (product_transfer_number) REFERENCES product_transfer (number) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_transfer_document_product ADD CONSTRAINT FK_13290300E447CD0C FOREIGN KEY (document_product_id) REFERENCES document_product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_receipt_document_product DROP CONSTRAINT FK_1E1CBA30E3C4C960');
        $this->addSql('ALTER TABLE product_receipt_document_product DROP CONSTRAINT FK_1E1CBA30E447CD0C');
        $this->addSql('ALTER TABLE product_receipt_document_product ADD CONSTRAINT FK_1E1CBA30E3C4C960 FOREIGN KEY (product_receipt_number) REFERENCES product_receipt (number) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_receipt_document_product ADD CONSTRAINT FK_1E1CBA30E447CD0C FOREIGN KEY (document_product_id) REFERENCES document_product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE product_receipt_document_product DROP CONSTRAINT fk_1e1cba30e3c4c960');
        $this->addSql('ALTER TABLE product_receipt_document_product DROP CONSTRAINT fk_1e1cba30e447cd0c');
        $this->addSql('ALTER TABLE product_receipt_document_product ADD CONSTRAINT fk_1e1cba30e3c4c960 FOREIGN KEY (product_receipt_number) REFERENCES product_receipt (number) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_receipt_document_product ADD CONSTRAINT fk_1e1cba30e447cd0c FOREIGN KEY (document_product_id) REFERENCES document_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_transfer_document_product DROP CONSTRAINT fk_13290300c10847fa');
        $this->addSql('ALTER TABLE product_transfer_document_product DROP CONSTRAINT fk_13290300e447cd0c');
        $this->addSql('ALTER TABLE product_transfer_document_product ADD CONSTRAINT fk_13290300c10847fa FOREIGN KEY (product_transfer_number) REFERENCES product_transfer (number) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_transfer_document_product ADD CONSTRAINT fk_13290300e447cd0c FOREIGN KEY (document_product_id) REFERENCES document_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}

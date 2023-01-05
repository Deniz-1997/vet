<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210125062642 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE appointment_logs_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE appointment.appointment_index_search_id_seq CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FD4E2AE5E5B533F9 ON appointment.appointment_index_search (appointment_id)');
        $this->addSql('ALTER TABLE appointment.appointment_index_search ADD CONSTRAINT FK_FD4E2AE5E5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment.appointments (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER INDEX appointment.idx_5812246de5b533f9 RENAME TO IDX_746258FFE5B533F9');
        $this->addSql('ALTER INDEX appointment.idx_5812246df2b19fa9 RENAME TO IDX_746258FFF2B19FA9');
        $this->addSql('ALTER INDEX appointment.uniq_3c5eda529f75d7b0 RENAME TO UNIQ_9B7DCDA29F75D7B0');
        $this->addSql('ALTER INDEX appointment.idx_6b3110b2e5b533f9 RENAME TO IDX_C2D3090CE5B533F9');
        $this->addSql('ALTER INDEX appointment.idx_6b3110b261220ea6 RENAME TO IDX_C2D3090C61220EA6');
        $this->addSql('ALTER INDEX appointment.idx_6b3110b24584665a RENAME TO IDX_C2D3090C4584665A');
        $this->addSql('ALTER INDEX appointment.idx_6b3110b2dcd6110 RENAME TO IDX_C2D3090CDCD6110');
        $this->addSql('ALTER INDEX appointment.idx_6b3110b2727aca70 RENAME TO IDX_C2D3090C727ACA70');
        $this->addSql('ALTER INDEX appointment.idx_e34c0e9da76ed395 RENAME TO IDX_D6F099F1A76ED395');
        $this->addSql('ALTER INDEX appointment.idx_e34c0e9d6bf700bd RENAME TO IDX_D6F099F16BF700BD');
        $this->addSql('ALTER INDEX appointment.idx_e34c0e9de5b533f9 RENAME TO IDX_D6F099F1E5B533F9');
        $this->addSql('ALTER INDEX appointment.uniq_6a41727ad17f50a6 RENAME TO UNIQ_F4E1C076D17F50A6');
        $this->addSql('ALTER INDEX appointment.uniq_6a41727a9f75d7b0 RENAME TO UNIQ_F4E1C0769F75D7B0');
        $this->addSql('ALTER INDEX appointment.idx_6a41727a966f7fb6 RENAME TO IDX_F4E1C076966F7FB6');
        $this->addSql('ALTER INDEX appointment.idx_6a41727a7e3c61f9 RENAME TO IDX_F4E1C0767E3C61F9');
        $this->addSql('ALTER INDEX appointment.idx_6a41727aa76ed395 RENAME TO IDX_F4E1C076A76ED395');
        $this->addSql('ALTER INDEX appointment.idx_6a41727a6bf700bd RENAME TO IDX_F4E1C0766BF700BD');
        $this->addSql('ALTER INDEX appointment.idx_6a41727afdef8996 RENAME TO IDX_F4E1C076FDEF8996');
        $this->addSql('ALTER INDEX appointment.idx_6a41727a2de62210 RENAME TO IDX_F4E1C0762DE62210');
        $this->addSql('ALTER INDEX appointment.idx_6a41727af8bd700d RENAME TO IDX_F4E1C076F8BD700D');
        $this->addSql('ALTER INDEX appointment.idx_6a41727a9fb2a19c RENAME TO IDX_F4E1C0769FB2A19C');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE appointment.appointment_logs_id_seq CASCADE');
        $this->addSql('CREATE TABLE appointment.appointment_index_search (id SERIAL NOT NULL, appointment_id INT DEFAULT NULL, index TSVECTOR DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_54ac335be5b533f9 ON appointment.appointment_index_search (appointment_id)');
        $this->addSql('ALTER TABLE appointment.appointment_index_search ADD CONSTRAINT fk_54ac335be5b533f9 FOREIGN KEY (appointment_id) REFERENCES appointment.appointments (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE appointment_index_search');
        $this->addSql('ALTER INDEX appointment.idx_746258fff2b19fa9 RENAME TO idx_5812246df2b19fa9');
        $this->addSql('ALTER INDEX appointment.idx_746258ffe5b533f9 RENAME TO idx_5812246de5b533f9');
        $this->addSql('ALTER INDEX appointment.idx_d6f099f1e5b533f9 RENAME TO idx_e34c0e9de5b533f9');
        $this->addSql('ALTER INDEX appointment.idx_d6f099f1a76ed395 RENAME TO idx_e34c0e9da76ed395');
        $this->addSql('ALTER INDEX appointment.idx_d6f099f16bf700bd RENAME TO idx_e34c0e9d6bf700bd');
        $this->addSql('ALTER INDEX appointment.idx_c2d3090cdcd6110 RENAME TO idx_6b3110b2dcd6110');
        $this->addSql('ALTER INDEX appointment.idx_c2d3090ce5b533f9 RENAME TO idx_6b3110b2e5b533f9');
        $this->addSql('ALTER INDEX appointment.idx_c2d3090c727aca70 RENAME TO idx_6b3110b2727aca70');
        $this->addSql('ALTER INDEX appointment.idx_c2d3090c61220ea6 RENAME TO idx_6b3110b261220ea6');
        $this->addSql('ALTER INDEX appointment.idx_c2d3090c4584665a RENAME TO idx_6b3110b24584665a');
        $this->addSql('ALTER INDEX appointment.uniq_9b7dcda29f75d7b0 RENAME TO uniq_3c5eda529f75d7b0');
        $this->addSql('ALTER INDEX appointment.idx_f4e1c0767e3c61f9 RENAME TO idx_6a41727a7e3c61f9');
        $this->addSql('ALTER INDEX appointment.idx_f4e1c076fdef8996 RENAME TO idx_6a41727afdef8996');
        $this->addSql('ALTER INDEX appointment.idx_f4e1c076a76ed395 RENAME TO idx_6a41727aa76ed395');
        $this->addSql('ALTER INDEX appointment.idx_f4e1c0762de62210 RENAME TO idx_6a41727a2de62210');
        $this->addSql('ALTER INDEX appointment.idx_f4e1c076966f7fb6 RENAME TO idx_6a41727a966f7fb6');
        $this->addSql('ALTER INDEX appointment.idx_f4e1c076f8bd700d RENAME TO idx_6a41727af8bd700d');
        $this->addSql('ALTER INDEX appointment.uniq_f4e1c0769f75d7b0 RENAME TO uniq_6a41727a9f75d7b0');
        $this->addSql('ALTER INDEX appointment.idx_f4e1c0769fb2a19c RENAME TO idx_6a41727a9fb2a19c');
        $this->addSql('ALTER INDEX appointment.idx_f4e1c0766bf700bd RENAME TO idx_6a41727a6bf700bd');
        $this->addSql('ALTER INDEX appointment.uniq_f4e1c076d17f50a6 RENAME TO uniq_6a41727ad17f50a6');
    }
}

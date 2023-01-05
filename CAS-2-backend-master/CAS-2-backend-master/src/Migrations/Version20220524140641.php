<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220524140641 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql("UPDATE action.action SET name='Масти животных' WHERE id=(select id from action.action where code='colour')");
        $this->addSql("UPDATE action.action SET name='Виды животных' WHERE id=(select id from action.action where code='pet-kind')");
        $this->addSql("UPDATE action.action SET name='Виды меток животных' WHERE id=(select id from action.action where code='pets-stamps')");
        $this->addSql("UPDATE action.action SET name='Подразделения (используется при импорте вакцинаций)' WHERE id=(select id from action.action where code='subdivision')");
    }

    public function down(Schema $schema) : void
    {

        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("UPDATE action.action SET name='Окрас' WHERE id=(select id from action.action where code='colour')");
        $this->addSql("UPDATE action.action SET name='Масть живтоных' WHERE id=(select id from action.action where code='pet-kind')");
        $this->addSql("UPDATE action.action SET name='Чипы' WHERE id=(select id from action.action where code='pets-stamps')");
        $this->addSql("UPDATE action.action SET name='Подразделения' WHERE id=(select id from action.action where code='subdivision')");
    }
}

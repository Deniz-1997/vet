<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210421065122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE wild_animal ADD birthday DATE DEFAULT NULL');
        $this->addSql("UPDATE public.wild_animal as w set birthday = 
                        case 
                            when w.age = 'UNDER_ONE_YEAR' then current_date - INTERVAL '6 month' 
                            when w.age = 'ONE_YEAR' then current_date - INTERVAL '1 year'
                            when w.age = 'TWO_YEARS' then current_date - INTERVAL '2 year'
                            when w.age = 'THREE_YEARS' then current_date - INTERVAL '3 year'
                            when w.age = 'FOUR_YEARS' then current_date - INTERVAL '4 year'
                            when w.age = 'FIVE_YEARS' then current_date - INTERVAL '5 year'
                            when w.age = 'GREATER_THAN_FIVE_YEARS' then current_date - INTERVAL '6 year'
                        end;");
        $this->addSql('ALTER TABLE wild_animal DROP age');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE wild_animal DROP birthday');
        $this->addSql('ALTER TABLE wild_animal ADD age VARCHAR(255) DEFAULT NULL');
    }
}

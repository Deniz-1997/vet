<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210902143329 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');




        $this->addSql('ALTER TABLE reports.reports_data ADD station_id INT NOT NULL');
        $this->addSql('ALTER TABLE reports.reports_data ADD CONSTRAINT FK_F9D12E6121BDB235 FOREIGN KEY (station_id) REFERENCES reference.reference_station (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F9D12E6121BDB235 ON reports.reports_data (station_id)');
        $this->addSql("INSERT INTO reference.reference_station (name) values ('Министерство сельского хозяйства и продовольствия Московской области')");
        $this->addSql("INSERT INTO reference.reference_station (parent_id,  name) values ((select id from reference.reference_station where name = 'Министерство сельского хозяйства и продовольствия Московской области'),  'ГБУВ МО МособлВСС')");
        $this->addSql("INSERT INTO reference.reference_station (parent_id,  name) values ((select id from reference.reference_station where name = 'Министерство сельского хозяйства и продовольствия Московской области'),  'ГБУВ МО Мособлветлаборатория')");
        $this->addSql("INSERT INTO reference.reference_station (parent_id,  name) values ((select id from reference.reference_station where name = 'Министерство сельского хозяйства и продовольствия Московской области'),  'ГБУВ МО Терветуправление № 1')");
        $this->addSql("INSERT INTO reference.reference_station (parent_id,  name) values ((select id from reference.reference_station where name = 'ГБУВ МО Терветуправление № 1'),  'Волоколамская участковая ветеринарная лечебница')");
        $this->addSql("INSERT INTO reference.reference_station (parent_id,  name) values ((select id from reference.reference_station where name = 'ГБУВ МО Терветуправление № 1'),  'Истринская ветеринарная станция')");
        $this->addSql("INSERT INTO reference.reference_station (parent_id,  name) values ((select id from reference.reference_station where name = 'Истринская ветеринарная станция'),  'Истринская участковая ветеринарная лечебница')");
        $this->addSql("INSERT INTO reference.reference_station (parent_id,  name) values ((select id from reference.reference_station where name = 'ГБУВ МО Терветуправление № 1'),  'Красногорская участковая ветеринарная лечебница')");
        $this->addSql("INSERT INTO reference.reference_station (parent_id,  name) values ((select id from reference.reference_station where name = 'ГБУВ МО Терветуправление № 1'),  'Лотошинская участковая ветеринарная лечебница')");
        $this->addSql("INSERT INTO reference.reference_station (parent_id,  name) values ((select id from reference.reference_station where name = 'ГБУВ МО Терветуправление № 1'),  'Можайская ветеринарная станция')");
        $this->addSql("INSERT INTO reference.reference_station (parent_id,  name) values ((select id from reference.reference_station where name = 'Можайская ветеринарная станция'),  'Бородинская участковая ветеринарная лечебница')");
        $this->addSql("INSERT INTO reference.reference_station (parent_id,  name) values ((select id from reference.reference_station where name = 'Можайская ветеринарная станция'),  'Ваулинская участковая ветеринарная лечебница')");
        $this->addSql("INSERT INTO reference.reference_station (parent_id,  name) values ((select id from reference.reference_station where name = 'Можайская ветеринарная станция'),  'Можайская участковая ветеринарная лечебница')");
        $this->addSql("INSERT INTO reference.reference_station (parent_id,  name) values ((select id from reference.reference_station where name = 'Можайская ветеринарная станция'),  'Уваровская участковая ветеринарная лечебница')");
        $this->addSql("INSERT INTO reference.reference_station (parent_id,  name) values ((select id from reference.reference_station where name = 'ГБУВ МО Терветуправление № 1'),  'Наро-Фоминская ветеринарная станция')");
        $this->addSql("INSERT INTO reference.reference_station (parent_id,  name) values ((select id from reference.reference_station where name = 'Наро-Фоминская ветеринарная станция'),  'Апрелевская участковая ветеринарная лечебница')");
        $this->addSql("INSERT INTO reference.reference_station (parent_id,  name) values ((select id from reference.reference_station where name = 'Наро-Фоминская ветеринарная станция'),  'Участковая ветеринарная лечебница Парк Воровского')");
        $this->addSql("INSERT INTO reference.reference_station (parent_id,  name) values ((select id from reference.reference_station where name = 'ГБУВ МО Терветуправление № 1'),  'Одинцовская ветеринарная станция')");
        $this->addSql("INSERT INTO reference.reference_station (parent_id,  name) values ((select id from reference.reference_station where name = 'Одинцовская ветеринарная станция'),  'Голицынская участковая ветеринарная лечебница')");
        $this->addSql("INSERT INTO reference.reference_station (parent_id,  name) values ((select id from reference.reference_station where name = 'Одинцовская ветеринарная станция'),  'Звенигородская участковая ветеринарная лечебница')");
        $this->addSql("INSERT INTO reference.reference_station (parent_id,  name) values ((select id from reference.reference_station where name = 'Одинцовская ветеринарная станция'),  'Краснознаменская участковая ветеринарная лечебница')");
        $this->addSql("INSERT INTO reference.reference_station (parent_id,  name) values ((select id from reference.reference_station where name = 'Одинцовская ветеринарная станция'),  'Кубинская участковая ветеринарная лечебница')");
        $this->addSql("INSERT INTO reference.reference_station (parent_id,  name) values ((select id from reference.reference_station where name = 'Одинцовская ветеринарная станция'),  'Одинцовская участковая ветеринарная лечебница')");
        $this->addSql("INSERT INTO reference.reference_station (parent_id,  name) values ((select id from reference.reference_station where name = 'ГБУВ МО Терветуправление № 1'),  'Рузская ветеринарная станция')");
        $this->addSql("INSERT INTO reference.reference_station (parent_id,  name) values ((select id from reference.reference_station where name = 'Рузская ветеринарная станция'),  'Рузская участковая ветеринарная лечебница')");
        $this->addSql("INSERT INTO reference.reference_station (parent_id,  name) values ((select id from reference.reference_station where name = 'Рузская ветеринарная станция'),  'Тучковская участковая ветеринарная лечебница')");
        $this->addSql("INSERT INTO reference.reference_station (parent_id,  name) values ((select id from reference.reference_station where name = 'ГБУВ МО Терветуправление № 1'),  'Шаховская участковая ветеринарная лечебница')");



    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');


        $this->addSql('DROP INDEX IDX_F9D12E6121BDB235');

    }
}

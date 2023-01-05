<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210831121022 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("insert into reports.reports(name, uuid_tmp) values ('1-Вет', '5ff568c1-b510-40b8-bf6e-b336e3cfcf83')");
        $this->addSql("insert into reports.reports(name, uuid_tmp) values ('1-Вет А', '4b3db081-909e-4fba-a604-06c8bc8934be')");
        $this->addSql("insert into reports.reports(name, uuid_tmp) values ('1-Вет Г: Гиподерматоз КРС и северных оленей', '9209bcc5-1bd0-4a6a-bf4a-ea724b59d3d3')");
        $this->addSql("insert into reports.reports(name, uuid_tmp) values ('2-Вет', 'd9421066-1570-4958-bea5-d89618a8f715')");
        $this->addSql("insert into reports.reports(name, uuid_tmp) values ('3-Вет: Болезни рыб', '2809d176-4440-4de5-bae9-a43855bb8d4e')");
        $this->addSql("insert into reports.reports(name, uuid_tmp) values ('5-Вет Сведения о ветеринарно-санитарной экспертизе сырья и продуктов животного происхождения', 'e9fce7b1-2697-4f24-b0c2-2b7603ea6f6c')");
        $this->addSql("insert into reports.reports(name, uuid_tmp) values ('Дез. средства', '0e40b044-dc3c-426e-b0e2-42b519a852c6')");
        $this->addSql("insert into reports.reports(name, uuid_tmp) values ('План-график отбора проб государственного эпизоотического мониторинга', 'ad660fa0-c65a-4764-af1a-4f10d7d368a1')");
        $this->addSql("insert into reports.reports(name, uuid_tmp) values ('Поголовье животных', '9f68818e-2eb6-41f9-927a-965feeefe78d')");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

    }
}

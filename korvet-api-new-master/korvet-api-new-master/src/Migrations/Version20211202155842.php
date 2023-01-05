<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211202155842 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("UPDATE action.action SET sort=0 WHERE id =59;");
        $this->addSql("UPDATE action.action SET sort=1 WHERE id =60;");
        $this->addSql("UPDATE action.action SET sort=2 WHERE id =61;");
        $this->addSql("UPDATE action.action SET sort=3 WHERE id =62;");
        $this->addSql("UPDATE action.action SET sort=4 WHERE id =63;");
        $this->addSql("UPDATE action.action SET sort=5 WHERE id =64;");
        $this->addSql("UPDATE action.action SET sort=6 WHERE id =65;");
        $this->addSql("UPDATE action.action SET sort=7 WHERE id =66;");
        $this->addSql("UPDATE action.action SET sort=8 WHERE id =67;");
        $this->addSql("UPDATE action.action SET sort=9 WHERE id =68;");
        $this->addSql("UPDATE action.action SET sort=10 WHERE id =69;");
        $this->addSql("UPDATE action.action SET sort=11 WHERE id =70;");
        $this->addSql("UPDATE action.action SET sort=12 WHERE id =71;");
        $this->addSql("UPDATE action.action SET sort=13 WHERE id =72;");
        $this->addSql("UPDATE action.action SET sort=14 WHERE id =73;");
        $this->addSql("UPDATE action.action SET sort=15 WHERE id =74;");
        $this->addSql("UPDATE action.action SET sort=16 WHERE id =75;");
        $this->addSql("UPDATE action.action SET sort=17 WHERE id =77;");
        $this->addSql("UPDATE action.action SET sort=18 WHERE id =78;");
        $this->addSql("UPDATE action.action SET sort=19 WHERE id =79;");
        $this->addSql("UPDATE action.action SET sort=20 WHERE id =80;");
        $this->addSql("UPDATE action.action SET sort=21 WHERE id =81;");
        $this->addSql("UPDATE action.action SET sort=22 WHERE id =82;");
        $this->addSql("UPDATE action.action SET sort=23 WHERE id =86;");
        $this->addSql("UPDATE action.action SET sort=24 WHERE id =112;");
        $this->addSql("UPDATE action.action SET sort=25 WHERE id =113;");
        $this->addSql("UPDATE action.action SET sort=26 WHERE id =115;");
        $this->addSql("UPDATE action.action SET sort=27 WHERE id =116;");
        $this->addSql("UPDATE action.action SET sort=28 WHERE id =117;");
        $this->addSql("UPDATE action.action SET sort=29 WHERE id =118;");
        $this->addSql("UPDATE action.action SET sort=30 WHERE id =119;");
        $this->addSql("UPDATE action.action SET sort=31 WHERE id =120;");
        $this->addSql("UPDATE action.action SET sort=32 WHERE id =121;");
        $this->addSql("UPDATE action.action SET sort=33 WHERE id =132;");
        $this->addSql("UPDATE action.action SET sort=34 WHERE id =144;");
        $this->addSql("UPDATE action.action SET sort=35 WHERE id =145;");
        $this->addSql("UPDATE action.action SET sort=36 WHERE id =148;");
        $this->addSql("UPDATE action.action SET sort=37 WHERE id =156;");
        $this->addSql("UPDATE action.action SET sort=38 WHERE id =164;");
        $this->addSql("UPDATE action.action SET sort=39 WHERE id =165;");




    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');


    }
}

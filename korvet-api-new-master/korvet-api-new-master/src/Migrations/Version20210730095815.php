<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210730095815 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');


        $this->addSql('ALTER TABLE icon ADD subclass VARCHAR(10) DEFAULT \'\' NOT NULL');
        $this->addSql("SELECT SETVAL('icon_id_seq', (select max(id) from icon));");
        $this->addSql('ALTER TABLE reference.reference_pet_types ADD icon_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reference.reference_pet_types ADD CONSTRAINT FK_F27373A654B9D732 FOREIGN KEY (icon_id) REFERENCES icon (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F27373A654B9D732 ON reference.reference_pet_types (icon_id)');
        $this->addSql("INSERT INTO icon (class, name, code, subclass) VALUES ('icon-bee', 'Насекомые', 'bee.svg', 'ANIMAL')");
        $this->addSql("INSERT INTO icon (class, name, code, subclass) VALUES ('icon-cat', 'Кошки', 'cat.svg', 'ANIMAL')");
        $this->addSql("INSERT INTO icon (class, name, code, subclass) VALUES ('icon-cow', 'Домашний скот', 'cow.svg', 'ANIMAL')");
        $this->addSql("INSERT INTO icon (class, name, code, subclass) VALUES ('icon-dog', 'Собаки', 'dog.svg', 'ANIMAL')");
        $this->addSql("INSERT INTO icon (class, name, code, subclass) VALUES ('icon-footprint', 'Животные', 'footprint.svg', 'ANIMAL')");
        $this->addSql("INSERT INTO icon (class, name, code, subclass) VALUES ('icon-hen', 'Птицы', 'hen.svg', 'ANIMAL')");
        $this->addSql("INSERT INTO icon (class, name, code, subclass) VALUES ('icon-horse', 'Лошадь', 'horse.svg', 'ANIMAL')");
        $this->addSql("INSERT INTO icon (class, name, code, subclass) VALUES ('icon-peacock', 'Павлин', 'peacock.svg', 'ANIMAL')");
        $this->addSql("INSERT INTO icon (class, name, code, subclass) VALUES ('icon-pig', 'Свиньи', 'pig.svg', 'ANIMAL')");
        $this->addSql("INSERT INTO icon (class, name, code, subclass) VALUES ('icon-rabbit', 'Кролики', 'rabbit.svg', 'ANIMAL')");
        $this->addSql("INSERT INTO icon (class, name, code, subclass) VALUES ('icon-rat', 'Грызуны', 'rat.svg', 'ANIMAL')");
        $this->addSql("INSERT INTO icon (class, name, code, subclass) VALUES ('icon-turtle', 'Черепахи', 'turtle.svg', 'ANIMAL')");
        $this->addSql("INSERT INTO icon (class, name, code, subclass) VALUES ('icon-deer', 'Олени', 'deer.svg', 'ANIMAL')");
        $this->addSql("UPDATE icon SET subclass='NOANIMAL' WHERE code='leaving'");
        $this->addSql("UPDATE icon SET subclass='NOANIMAL' WHERE code='cashbox'");
        $this->addSql("UPDATE icon SET subclass='NOANIMAL' WHERE code='shop'");
        $this->addSql("UPDATE icon SET subclass='NOANIMAL' WHERE code='culling'");
        $this->addSql("UPDATE icon SET subclass='NOANIMAL' WHERE code='storage'");
        $this->addSql("UPDATE icon SET subclass='NOANIMAL' WHERE code='reception'");
        $this->addSql("UPDATE icon SET subclass='NOANIMAL' WHERE code='administration'");
        $this->addSql("UPDATE icon SET subclass='NOANIMAL' WHERE code='laboratory'");
        $this->addSql("UPDATE icon SET subclass='NOANIMAL' WHERE code='description'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-bee') WHERE name='Пчелы (медоносные)'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-bee') WHERE name='Тутовый шелкопряд'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-cat') WHERE name='Кошка'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-cat') WHERE name='Песец'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-cat') WHERE name='Лисица'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-cow') WHERE name='Бизон'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-cow') WHERE name='Буйвол'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-cow') WHERE name='Верблюды'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-cow') WHERE name='Домашняя коза'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-cow') WHERE name='Домашняя овца'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-cow') WHERE name='Зубр'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-cow') WHERE name='Корова'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-cow') WHERE name='Як'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-cow') WHERE name='Осел'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-dog') WHERE name='Собака'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-hen') WHERE name='Гуси'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-hen') WHERE name='Индейки'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-hen') WHERE name='Куры'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-hen') WHERE name='Соболь'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-hen') WHERE name='Перепел'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-hen') WHERE name='Утки'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-hen') WHERE name='Фазан'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-hen') WHERE name='Цесарки'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-hen') WHERE name='Голуби'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-hen') WHERE name='Попугай'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-horse') WHERE name='Лошадь'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-horse') WHERE name='Пони'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-deer') WHERE name='олень'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-deer') WHERE name='Северный олень'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-peacock') WHERE name='Павлины'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-pig') WHERE name='Свиньи'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-rabbit') WHERE name='Кролики'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-rat') WHERE name='Крыса'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-rat') WHERE name='Норка'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-rat') WHERE name='морская свинка'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-rat') WHERE name='Нутрии'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-rat') WHERE name='Хомяк'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-rat') WHERE name='Хорьки'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-rat') WHERE name='Шиншилла'");
        $this->addSql("UPDATE reference.reference_pet_types SET icon_id=(select id from icon where class='icon-turtle') WHERE name='Черепаха'");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE icon DROP subclass');
        $this->addSql('DROP INDEX IDX_F27373A654B9D732');
        $this->addSql('ALTER TABLE reference.reference_pet_types DROP icon_id');

    }
}

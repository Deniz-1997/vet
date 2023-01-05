<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210917095251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE reference.reference_disinfectant (id SERIAL NOT NULL, measurement_units_id INT DEFAULT NULL, kind VARCHAR(50) DEFAULT NULL, mult INT NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, sort INT DEFAULT 0, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8D3BD90AAA756149 ON reference.reference_disinfectant (measurement_units_id)');
        $this->addSql('ALTER TABLE reference.reference_disinfectant ADD CONSTRAINT FK_8D3BD90AAA756149 FOREIGN KEY (measurement_units_id) REFERENCES reference.reference_measurement_units (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (1,	'пакет', 0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (2,	'пар', 0, false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (3,	'амп', 0, false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (4,	'тест',	0, false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (5,	'набор', 0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (6,	'таб.', 0, false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (7,	'бан.',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (8,	'куб'	,0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (9,	'одна голова',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (10,	'л',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (11,	'усл. банк',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (12,	'один зуб',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (13,	'один час',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (14,	'одна проба',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (15,	'один килограмм',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (16,	'одна голова до 24 часов',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (17,	'один анализ',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (18,	'одна голова 12 часов',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (19,	'одна процедура',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (20,	'одна область',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (21,	'пипет',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (22,	'блистер',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (23,	'м',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (24,	'Сутки',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (25,	'кг',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (26,	'шт',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (27,	'один носитель',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (28,	'одна голова до 12 часов',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (29,	'туба',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (30,	'упак',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (31,	'доз.',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (32,	'мл',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (33,	'г',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (34,	'флак',	0,	false)");
        $this->addSql("INSERT INTO reference.reference_measurement_units (id, name, sort, deleted) VALUES (35,	'один образец',	0,	false)");


        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('1', 'Кальцинированная сода',(select id from reference.reference_measurement_units where name = 'кг' limit 1),'сухой','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('2', 'Биодез экстра',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('3', 'Дезконтен',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('4', 'Диабак - вет',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('5', 'Хлорная известь',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('6', 'Бицин',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('7', 'Вироцид',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('8', 'Демос',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('9', 'Натр едкий (каустическая сода)',(select id from reference.reference_measurement_units where name = 'кг' limit 1),'сухой','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('10', 'Формалин',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('11', 'Хлормисепт Люкс (таблетки)',(select id from reference.reference_measurement_units where name = 'шт' limit 1),'сухой','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('12', 'Аламинол',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('13', 'Самаровка',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('14', 'Хлорамин - Б',(select id from reference.reference_measurement_units where name = 'кг' limit 1),'сухой','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('15', 'Экоцид',(select id from reference.reference_measurement_units where name = 'кг' limit 1),'сухой','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('16', 'Жавилар эффект',(select id from reference.reference_measurement_units where name = 'шт' limit 1),'сухой','1','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('17', 'Делеголь',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('18', 'Глютекс',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('19', 'Велтосепт (спрей)',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('20', 'Дезэфект',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('21', 'Велтосепт',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('22', 'Дискон',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('23', 'Неостомазан',(select id from reference.reference_measurement_units where name = 'кг' limit 1),'сухой','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('24', 'Дезинфексан',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('25', 'Дезолайн-Ф',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('26', 'Санит - дезэффект',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('27', 'Ди-хлор таблетки',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('28', 'Вирошелд',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('29', 'Эком 50 М',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('30', 'Гипохлорид натрия/кальция',(select id from reference.reference_measurement_units where name = 'кг' limit 1),'сухой','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
                VALUES ('31', 'Вируцел',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");

        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('32', 'Цирадон 11%',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('33', 'Лигроцид',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('34', 'Дезэфект-Санит',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('35', 'Укорсан 414',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('36', 'Рус - Дез - Униерсал',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('37', 'Ациплюсфоам',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('38', 'Дивосан актив',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('39', 'Дивосан форте',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('40', 'Иоклар',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('41', 'Эндоро плюс',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('42', 'ГАН',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('43', 'ТРИОСЕПТ-ВЕТ',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('44', 'Бромосепт 50 П',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('45', 'Креолин',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('46', 'Медный купорос',(select id from reference.reference_measurement_units where name = 'кг' limit 1),'сухой','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('47', 'Дермодез',(select id from reference.reference_measurement_units where name = 'кг' limit 1),'сухой','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('48', 'Ника-2',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");

        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('49', 'Полидез',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('50', 'Жимитек универсал-дез',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('51', 'Гипохлорид натрия/кальция',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('52', 'Йод однхлористый',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('53', 'Мерафоам',(select id from reference.reference_measurement_units where name = 'кг' limit 1),'сухой','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('54', 'Пергидроль 30%',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('55', 'Эффект форте',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('56', 'Мистраль',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('57', 'Асептодин',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('58', 'Прогресс',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('59', 'Термит АВ',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('60', 'Экодез',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('61', 'Дезавид',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('62', 'Виркон',(select id from reference.reference_measurement_units where name = 'кг' limit 1),'сухой','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('63', 'Алкалин Ф Дез',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('64', 'Жавель Солид',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
        $this->addSql("INSERT INTO reference.reference_disinfectant (id,name, measurement_units_id, kind, mult, created_at, deleted, sort)
            VALUES ('65', 'Триосепт-микс',(select id from reference.reference_measurement_units where name = 'л' limit 1),'жидкий','1000','2021-09-17 10:48:33', false, 0)");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE reference.reference_disinfectant');

        $this->addSql('DELETE FROM reference.reference_measurement_units');
    }
}

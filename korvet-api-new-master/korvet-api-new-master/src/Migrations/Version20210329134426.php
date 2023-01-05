<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210329134426 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE reference.reference_countries (id SERIAL NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, sort INT DEFAULT 0, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE reference.reference_manufacturer ADD countries_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reference.reference_manufacturer ADD CONSTRAINT FK_8C6532E5AEBAE514 FOREIGN KEY (countries_id) REFERENCES reference.reference_countries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product.product ADD countries_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product.product ADD CONSTRAINT FK_62698E13AEBAE514 FOREIGN KEY (countries_id) REFERENCES reference.reference_countries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'ENTITY_LIST_URL', '/admin/references/reference-countries', true, 0, false, false, false, 'Страны', 'reference-countries', false, 'Страны', NULL, 700, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 'App\Entity\Reference\Countries', 'Страны');");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Reference\Countries'), 3);");
        $this->addSql("CREATE TRIGGER reference_countries_items_count AFTER INSERT OR DELETE OR UPDATE ON reference.reference_countries FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/reference-countries');");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (1, 'Австралия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (2, 'Австрия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (3, 'Азербайджан')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (4, 'Аландские о-ва')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (5, 'Албания')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (6, 'Алжир')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (7, 'Американское Самоа')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (8, 'Ангилья')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (9, 'Ангола')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (10, 'Андорра')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (11, 'Антарктида')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (12, 'Антигуа и Барбуда')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (13, 'Аргентина')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (14, 'Армения')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (15, 'Аруба')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (16, 'Афганистан')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (17, 'Багамы')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (18, 'Бангладеш')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (19, 'Барбадос')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (20, 'Бахрейн')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (21, 'Беларусь')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (22, 'Белиз')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (23, 'Бельгия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (24, 'Бенин')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (25, 'Бермудские о-ва')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (26, 'Болгария')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (27, 'Боливия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (28, 'Бонэйр, Синт-Эстатиус и Саба')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (29, 'Босния и Герцеговина')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (30, 'Ботсвана')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (31, 'Бразилия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (32, 'Британская территория в Индийском океане')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (33, 'Бруней-Даруссалам')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (34, 'Буркина-Фасо')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (35, 'Бурунди')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (36, 'Бутан')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (37, 'Вануату')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (38, 'Ватикан')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (39, 'Великобритания')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (40, 'Венгрия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (41, 'Венесуэла')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (42, 'Виргинские о-ва (Великобритания)')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (43, 'Виргинские о-ва (США)')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (44, 'Внешние малые о-ва (США)')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (45, 'Восточный Тимор')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (46, 'Вьетнам')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (47, 'Габон')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (48, 'Гаити')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (49, 'Гайана')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (50, 'Гамбия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (51, 'Гана')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (52, 'Гваделупа')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (53, 'Гватемала')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (54, 'Гвинея')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (55, 'Гвинея-Бисау')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (56, 'Германия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (57, 'Гернси')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (58, 'Гибралтар')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (59, 'Гондурас')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (60, 'Гонконг (САР)')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (61, 'Гренада')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (62, 'Гренландия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (63, 'Греция')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (64, 'Грузия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (65, 'Гуам')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (66, 'Дания')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (67, 'Джерси')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (68, 'Джибути')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (69, 'Доминика')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (70, 'Доминиканская Республика')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (71, 'Египет')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (72, 'Замбия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (73, 'Западная Сахара')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (74, 'Зимбабве')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (75, 'Израиль')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (76, 'Индия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (77, 'Индонезия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (78, 'Иордания')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (79, 'Ирак')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (80, 'Иран')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (81, 'Ирландия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (82, 'Исландия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (83, 'Испания')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (84, 'Италия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (85, 'Йемен')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (86, 'Кабо-Верде')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (87, 'Казахстан')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (88, 'Камбоджа')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (89, 'Камерун')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (90, 'Канада')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (91, 'Катар')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (92, 'Кения')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (93, 'Кипр')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (94, 'Киргизия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (95, 'Кирибати')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (96, 'Китай')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (97, 'КНДР')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (98, 'Кокосовые о-ва')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (99, 'Колумбия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (100, 'Коморы')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (101, 'Конго - Браззавиль')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (102, 'Конго - Киншаса')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (103, 'Коста-Рика')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (104, 'Кот-д’Ивуар')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (105, 'Куба')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (106, 'Кувейт')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (107, 'Кюрасао')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (108, 'Лаос')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (109, 'Латвия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (110, 'Лесото')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (111, 'Либерия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (112, 'Ливан')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (113, 'Ливия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (114, 'Литва')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (115, 'Лихтенштейн')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (116, 'Люксембург')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (117, 'Маврикий')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (118, 'Мавритания')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (119, 'Мадагаскар')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (120, 'Майотта')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (121, 'Макао (САР)')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (123, 'Малави')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (124, 'Малайзия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (125, 'Мали')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (126, 'Мальдивы')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (127, 'Мальта')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (128, 'Марокко')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (129, 'Мартиника')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (130, 'Маршалловы Острова')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (131, 'Мексика')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (132, 'Мозамбик')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (133, 'Молдова')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (134, 'Монако')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (135, 'Монголия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (136, 'Монтсеррат')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (137, 'Мьянма (Бирма)')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (138, 'Намибия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (139, 'Науру')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (140, 'Непал')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (141, 'Нигер')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (250, 'Нигерия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (142, 'Нидерланды')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (143, 'Никарагуа')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (144, 'Ниуэ')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (145, 'Новая Зеландия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (146, 'Новая Каледония')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (147, 'Норвегия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (148, 'о-в Буве')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (149, 'о-в Мэн')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (150, 'о-в Норфолк')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (151, 'о-в Рождества')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (152, 'о-в Св. Елены')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (153, 'о-ва Питкэрн')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (154, 'о-ва Тёркс и Кайкос')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (155, 'о-ва Херд и Макдональд')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (156, 'ОАЭ')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (157, 'Оман')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (158, 'Острова Кайман')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (159, 'Острова Кука')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (160, 'Пакистан')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (161, 'Палау')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (162, 'Палестинские территории')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (163, 'Панама')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (164, 'Папуа — Новая Гвинея')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (165, 'Парагвай')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (166, 'Перу')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (167, 'Польша')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (168, 'Португалия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (169, 'Пуэрто-Рико')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (170, 'Республика Корея')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (171, 'Реюньон')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (172, 'Россия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (173, 'Руанда')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (174, 'Румыния')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (175, 'Сальвадор')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (176, 'Самоа')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (177, 'Сан-Марино')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (178, 'Сан-Томе и Принсипи')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (179, 'Саудовская Аравия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (180, 'Северная Македония')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (181, 'Северные Марианские о-ва')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (182, 'Сейшельские Острова')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (183, 'Сен-Бартелеми')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (184, 'Сен-Мартен')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (185, 'Сен-Пьер и Микелон')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (186, 'Сенегал')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (187, 'Сент-Винсент и Гренадины')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (188, 'Сент-Китс и Невис')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (189, 'Сент-Люсия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (190, 'Сербия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (191, 'Сингапур')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (192, 'Синт-Мартен')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (193, 'Сирия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (194, 'Словакия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (195, 'Словения')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (196, 'Соединенные Штаты')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (197, 'Соломоновы Острова')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (198, 'Сомали')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (199, 'Судан')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (200, 'Суринам')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (201, 'Сьерра-Леоне')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (202, 'Таджикистан')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (203, 'Таиланд')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (204, 'Тайвань')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (205, 'Танзания')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (206, 'Того')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (207, 'Токелау')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (208, 'Тонга')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (209, 'Тринидад и Тобаго')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (210, 'Тувалу')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (211, 'Тунис')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (212, 'Туркменистан')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (213, 'Турция')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (214, 'Уганда')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (215, 'Узбекистан')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (216, 'Украина')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (217, 'Уоллис и Футуна')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (218, 'Уругвай')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (219, 'Фарерские о-ва')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (220, 'Федеративные Штаты Микронезии')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (221, 'Фиджи')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (222, 'Филиппины')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (223, 'Финляндия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (224, 'Фолклендские о-ва')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (225, 'Франция')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (226, 'Французская Гвиана')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (227, 'Французская Полинезия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (228, 'Французские Южные территории')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (229, 'Хорватия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (230, 'Центрально-Африканская Республика')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (231, 'Чад')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (232, 'Черногория')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (233, 'Чехия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (234, 'Чили')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (235, 'Швейцария')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (236, 'Швеция')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (237, 'Шпицберген и Ян-Майен')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (238, 'Шри-Ланка')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (239, 'Эквадор')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (240, 'Экваториальная Гвинеяа')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (241, 'Эритрея')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (242, 'Эсватин')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (243, 'Эстония')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (244, 'Эфиопия')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (245, 'Южная Георгия и Южные Сандвичевы о-ва')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (246, 'Южно-Африканская Республика')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (247, 'Южный Судан')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (248, 'Ямайка')");
        $this->addSql("INSERT INTO reference.reference_countries (id , name ) VALUES (249, 'Япония')");
        $this->addSql("SELECT SETVAL('reference.reference_countries_id_seq', (select max(id) from reference.reference_countries));");
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');


    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE reference.reference_manufacturer DROP CONSTRAINT FK_8C6532E5AEBAE514');
        $this->addSql('DROP TABLE reference.reference_countries');
        $this->addSql('ALTER TABLE reference.reference_manufacturer DROP countries_id');
        $this->addSql("DROP TRIGGER IF EXISTS reference_countries_items_count on reference.reference_countries ");
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Reference\Countries');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Reference\Countries');");
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

    }
}
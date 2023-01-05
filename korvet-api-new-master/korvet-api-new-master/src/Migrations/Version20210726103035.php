<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210726103035 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');


        $this->addSql('CREATE TABLE reference.reference_disease (id SERIAL NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, sort INT DEFAULT 0, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE product_disease (product_id INT NOT NULL, disease_id INT NOT NULL, PRIMARY KEY(product_id, disease_id))');
        $this->addSql('CREATE INDEX IDX_DEE271714584665A ON product_disease (product_id)');
        $this->addSql('CREATE INDEX IDX_DEE27171D8355341 ON product_disease (disease_id)');
        $this->addSql('ALTER TABLE product_disease ADD CONSTRAINT FK_DEE271714584665A FOREIGN KEY (product_id) REFERENCES product.product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_disease ADD CONSTRAINT FK_DEE27171D8355341 FOREIGN KEY (disease_id) REFERENCES reference.reference_disease (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql("INSERT INTO action.action (id, type, url, items_count_enabled, items_count, get_list_enabled, view_item_enabled, get_item_enabled, name, code, deleted, description, parent_id, sort, button_settings_color, button_settings_background_color, confirmation_title, confirmation_description, confirmation_cancel_button_color, confirmation_cancel_button_background_color, button_settings_icon_id, confirmation_icon_id, confirmation_confirm_button_color, confirmation_confirm_button_background_color, confirmation_confirm_button_icon_id, confirmation_cancel_button_icon_id, entity_class_name, entity_name) VALUES ((select max(id)+1 from action.action), 'ENTITY_LIST_URL', '/admin/references/reference-disease', true, 0, false, false, false, 'Заболевания', 'reference-disease', false, 'Заболевания', NULL, 700, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 'App\Entity\Reference\Disease', 'Заболевания');");
        $this->addSql("INSERT INTO action_action_group (action_id, action_group_id) VALUES ((SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Reference\Disease'), 3);");
        $this->addSql("CREATE TRIGGER reference_disease_items_count AFTER INSERT OR DELETE OR UPDATE ON reference.reference_disease FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/reference-countries');");
        $this->addSql("SELECT SETVAL('reference.reference_disease_id_seq', (select max(id) from reference.reference_disease));");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('аденовирусная инфекция')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('акарапидоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('актиномикоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('алеутская болезнь')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('амебиаз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('американский гнилец')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('анаплазмоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('анаплоцефалидозы')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('арахно-энтомозы')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('аскосфероз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('аспергиллез')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('африканская чума лошадей')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('африканская чума свиней')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('АЧС')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('аэромоноз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Аэромоноз карповых')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Аэромоноз лососевых')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('бабезиоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('балантидиоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Б. Ауески')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('безноитиоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Бешенство')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Болезнь Ауески')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('болезнь Гамборо')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Болезнь загруженной вакци')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('болезнь Марека')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Болезнь Ньюкасла')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Болезнь Тешена')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Болезнь Шмаленберга')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Бордетеллез')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('ботриомикоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Ботриоцефалёз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Ботриоцефалёз карповых')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Ботулизм')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('брадзот')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Бранхиомикоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Бранхионекроз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Бранхионекроз карповых')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('браулез')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Бруцеллез')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Варроатоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('ВГБК')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('везикулярная болезнь свиней')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('везикулярная экзантема')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('везикулярный стоматит')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('весенняя виремия карпов')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Вирусная виремия карпов')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('вирусная геморрагическая болезнь')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('вирусная геморрагическая септицемия')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('вирусная диарея')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('вирусный артериит')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('вирусный гепатит уток')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('вирусный гидроперикардит')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('вирусный паралич')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('вирусный энтерит')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Висна-Маеди')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Воспаление плавательного пузыря карпов')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('гафниоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('гельминтозы')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('гемофилезная плевропневмония свиней')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Гемофилезный полисерозит свиней')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Герпесвирусная инфекция')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('гиподерматоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('гиродактилез')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('грипп')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('грипп лошадей')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Грипп птиц')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('губкообразная энцефалопатия')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Дактилогидроз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('демодекоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('дерматомикоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('дизентерия')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('диктиокаулез')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('диплококковая инфекция')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('европейский гнилец')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('заразный узелковый дерматит крупного рогатого скота')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('злокаческтвенный отек')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('инфекционная агалактия')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Инфекционная анемия')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('инфекционная анемия лошадей')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('инфекционная анемия цыплят')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('инфекционная плевропневмония коз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('инфекционный артрит-энцефалит коз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('инфекционный атрофический ринит')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('инфекционный бронхит')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('инфекционный гепатит')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('инфекционный ларинготрахеит')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('инфекционный мастит')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Инфекционный ринотрахеит')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('инфекционный энцефаломиелит')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Инфекционный эпидидимит баранов')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Калицивирусная инфекция')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('кампилобактериоз (вибриоз)')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Катаральная лихорадка (Блютанг)')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Классическая чума свиней')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('клещевой паралич')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('кокцидиоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('колибактериоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('контагиозная плевропневмония крупного рогатого скота')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('контагиозный пустулезный дерматит')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('копытная гниль')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Коронавирусная инфекция')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('коронавирусный энтерит')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Ку-лихорадка')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Лейкоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Лептоспироз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Листериоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('лихорадка долины Рифт')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('медленные инфекции')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('мешотчатый расплод')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('микоплазмоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Микроспория')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Миксобактериоз лососевых')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Миксобактериоз осетровых')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('миксоматоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Мониезиоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('мыт')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('некробактериоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('некроз гемопоэтической ткани')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('некроз поджелудочной железы лососевых')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('нематодозы')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Нет болезни')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('нозематоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Нутталиоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Описторхоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('орнитоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('оспа')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Оспа-дифтерит')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('оспа овец и коз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('отечная болезнь поросят')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('отодектоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('панлейкопения')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Парагрипп-3')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('паратуберкулез')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Парвовирусная инфекция')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Парвовирусный энтерит')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('пастереллез')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('пироплазмидозы')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Пироплазмоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Псевдономоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('псороптозы')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Реовирусная болезнь птиц')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Репродуктивно-респираторный синдром свиней')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Респираторно-синтициальная инфекция КРС')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('респираторный вироз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Респираторный микоплазмоз птиц')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('ринопневмония')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Рожа')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('рожа свиней')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('ротавирусная инфекция')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('РРСС')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('сальмонеллез')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('сальмонеллезный аборт')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Сап')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Саркоптоидозы')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Сибирская язва')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Синдром снижения яйценоскости-76')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('скрепи')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Случная болезнь')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('спирохетоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('ССЯ-76')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('столбняк')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('стрептококкоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('стригущий лишай')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('стронгилоидозы')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('стронгилятозы')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('су-ауру (трипанозомоз)')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('тейлязиоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('тиф-пуллороз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('токсоплазмоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('трансмиссивный гастроэнтерит')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('трематодозы')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('трихинеллёз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('трихомоноз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Трихофития')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Туберкулез')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('туляремия')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('фасциолез')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Филометроидоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('финноз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Хламидиоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('хламидиозный аборт')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('ценуроз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Цестодозы')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Цирковирусная инфекция')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('чума')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('чума крупного рогатого скота')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('чума мелких жвачных')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Чума плотоядных')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Эдемагеноз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('эмфизематозный карбункул')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('энзоотический энцефаломиелит')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('энтерит гусей')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Энтерококковая инфекция')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Энтеротоксемия')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Энтомозы')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('энцефалопатия норок')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('эперитрозооноз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('эпизоотический лимфангоит')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('эстроз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('эфемерная лихорадка')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('эхинококкоз')");
        $this->addSql("INSERT INTO reference.reference_disease (name) VALUES ('Ящур')");
        $this->addSql("UPDATE reference.reference_nomenclature SET url='/store/reference-disease' WHERE url like '%reference-disease';");
        $this->addSql("INSERT INTO reference.reference_nomenclature (name, url, description ) VALUES ('Заболевания', '/admin/references/reference-disease', 'Справочник заболеваний')");
        $this->addSql("UPDATE action_action_group SET action_group_id=(select id from action.action_group where code='nomenclature') WHERE action_id=(select id from action.action where name='Заболевания')");



    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE product.product DROP CONSTRAINT FK_62698E13D8355341');
        $this->addSql('DROP TABLE reference.reference_disase');
        $this->addSql('DROP INDEX IDX_62698E13D8355341');
        $this->addSql('DROP TABLE product_disease');
        $this->addSql("DROP TRIGGER IF EXISTS reference_disease_items_count on reference.reference_disease ");
        $this->addSql("DELETE FROM action_action_group WHERE action_id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Reference\Disease');");
        $this->addSql("DELETE FROM action.action WHERE id = (SELECT id FROM action.action WHERE entity_class_name = 'App\Entity\Reference\Disease');");
    }
}

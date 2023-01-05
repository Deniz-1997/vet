<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210211175359 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE product_expense_number_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_inventory_number_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_receipt_number_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_transfer_number_seq CASCADE');
        $this->addSql('DROP SEQUENCE reference_pet_aggressive_types_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE pet.pet_index_search_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE appointment.appointment_logs_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product.product_expense_number_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product.product_inventory_number_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product.product_receipt_number_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product.product_transfer_number_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE reference.reference_pet_aggressive_types_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE pet_index_search (id SERIAL NOT NULL, pet_id INT DEFAULT NULL, index TSVECTOR DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_49E63F07966F7FB6 ON pet_index_search (pet_id)');
        $this->addSql('ALTER TABLE pet_index_search ADD CONSTRAINT FK_49E63F07966F7FB6 FOREIGN KEY (pet_id) REFERENCES pet.pets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER INDEX action.uniq_47cc8c9277153098 RENAME TO UNIQ_CFE3666677153098');
        $this->addSql('ALTER INDEX action.idx_47cc8c92727aca70 RENAME TO IDX_CFE36666727ACA70');
        $this->addSql('ALTER INDEX action.uniq_8a0e887977153098 RENAME TO UNIQ_711EF95877153098');
        $this->addSql('ALTER INDEX action.idx_8a0e8879727aca70 RENAME TO IDX_711EF958727ACA70');
        $this->addSql('ALTER TABLE appointment.appointment_index_search DROP CONSTRAINT fk_fd4e2ae5e5b533f9');
        $this->addSql('CREATE SEQUENCE appointment.appointment_index_search_id_seq');
        $this->addSql('SELECT setval(\'appointment.appointment_index_search_id_seq\', (SELECT MAX(id) FROM appointment.appointment_index_search))');
        $this->addSql('ALTER TABLE appointment.appointment_index_search ALTER id SET DEFAULT nextval(\'appointment.appointment_index_search_id_seq\')');
        $this->addSql('ALTER INDEX cash.idx_6f461f1da917cc69 RENAME TO IDX_937ACC47A917CC69');
        $this->addSql('ALTER INDEX cash.idx_6f461f1dbb70bc0e RENAME TO IDX_937ACC47BB70BC0E');
        $this->addSql('ALTER TABLE cash.cash_receipt DROP uuid_receipt_mobile');
        $this->addSql('ALTER INDEX cash.idx_d75a419a917cc69 RENAME TO IDX_E924E5D8A917CC69');
        $this->addSql('ALTER INDEX cash.uniq_d75a419ca1a8ecf RENAME TO UNIQ_E924E5D8CA1A8ECF');
        $this->addSql('ALTER INDEX cash.idx_d75a419bb70bc0e RENAME TO IDX_E924E5D8BB70BC0E');
        $this->addSql('ALTER INDEX cash.idx_d75a41961220ea6 RENAME TO IDX_E924E5D861220EA6');
        $this->addSql('ALTER INDEX cash.idx_d75a4192edb0489 RENAME TO IDX_E924E5D82EDB0489');
        $this->addSql('ALTER INDEX cash.idx_3d7ab1d95de77e3d RENAME TO IDX_D1FD12BE5DE77E3D');
        $this->addSql('ALTER INDEX cash.idx_3d7ab1d932c8a3de RENAME TO IDX_D1FD12BE32C8A3DE');
        $this->addSql('ALTER INDEX cash.idx_fdb06c21f8bd700d RENAME TO IDX_B346EF08F8BD700D');
        $this->addSql('ALTER INDEX form.idx_d8b2e19bc06dbc6f RENAME TO IDX_EED9203CC06DBC6F');
        $this->addSql('ALTER INDEX form.idx_d8b2e19bf2b19fa9 RENAME TO IDX_EED9203CF2B19FA9');
        $this->addSql('ALTER INDEX form.uniq_eb0f606977153098 RENAME TO UNIQ_8654344A77153098');
        $this->addSql('ALTER INDEX form.idx_dc52b260d4054342 RENAME TO IDX_CCFA7C23D4054342');
        $this->addSql('ALTER INDEX form.idx_dc52b260f50d82f4 RENAME TO IDX_CCFA7C23F50D82F4');
        $this->addSql('ALTER INDEX form.uniq_96d5720777153098 RENAME TO UNIQ_57BD0D5677153098');
        $this->addSql('ALTER INDEX form.idx_6127d99e443707b0 RENAME TO IDX_1393C004443707B0');
        $this->addSql('ALTER INDEX form.idx_6127d99e549213ec RENAME TO IDX_1393C004549213EC');
        $this->addSql('ALTER INDEX form.idx_c1bb5ede241f0df5 RENAME TO IDX_DD1657C3241F0DF5');
        $this->addSql('ALTER INDEX form.idx_c1bb5edef50d82f4 RENAME TO IDX_DD1657C3F50D82F4');
        $this->addSql('ALTER TABLE form.form_template ALTER active SET DEFAULT \'true\'');
        $this->addSql('ALTER INDEX form.uniq_265a9ac777153098 RENAME TO UNIQ_F2EC8FCB77153098');
        $this->addSql('ALTER INDEX form.idx_dee66dedf2b19fa9 RENAME TO IDX_B3BD39CEF2B19FA9');
        $this->addSql('ALTER INDEX form.idx_dee66ded2b68a933 RENAME TO IDX_B3BD39CE2B68A933');
        $this->addSql('ALTER INDEX pet.idx_8d2bde817e3c61f9 RENAME TO IDX_EC160D17E3C61F9');
        $this->addSql('ALTER INDEX pet.idx_8d2bde81966f7fb6 RENAME TO IDX_EC160D1966F7FB6');
        $this->addSql('ALTER INDEX pet.uniq_8638ea3f9f75d7b0 RENAME TO UNIQ_59C2C62A9F75D7B0');
        $this->addSql('ALTER INDEX pet.idx_8638ea3fa8b4a30f RENAME TO IDX_59C2C62AA8B4A30F');
        $this->addSql('ALTER INDEX pet.idx_8638ea3f75274dce RENAME TO IDX_59C2C62A75274DCE');
        $this->addSql('ALTER INDEX pet.idx_8638ea3f181f0d72 RENAME TO IDX_59C2C62A181F0D72');
        $this->addSql('ALTER INDEX pet.idx_8638ea3f86e64d89 RENAME TO IDX_59C2C62A86E64D89');
        $this->addSql('ALTER INDEX pet.idx_8638ea3f6d4b3c40 RENAME TO IDX_59C2C62A6D4B3C40');
        $this->addSql('ALTER INDEX pet.idx_25ef326f966f7fb6 RENAME TO IDX_F102272B966F7FB6');
        $this->addSql('ALTER INDEX pet.idx_25ef326fc54c8c93 RENAME TO IDX_F102272BC54C8C93');
        $this->addSql('ALTER INDEX pet.idx_8bf69e79966f7fb6 RENAME TO IDX_C56830C2966F7FB6');
        $this->addSql('ALTER INDEX pet.idx_8bf69e793f85796b RENAME TO IDX_C56830C23F85796B');
        $this->addSql('ALTER INDEX pet.idx_fef6ce77966f7fb6 RENAME TO IDX_8F93A6EB966F7FB6');
        $this->addSql('ALTER INDEX pet.idx_4328ea5f966f7fb6 RENAME TO IDX_C0C2540F966F7FB6');
        $this->addSql('ALTER INDEX product.idx_d34a04adf8bd700d RENAME TO IDX_62698E13F8BD700D');
        $this->addSql('ALTER INDEX product.uniq_fce39616bf396750 RENAME TO UNIQ_BF66B103BF396750');
        $this->addSql('ALTER INDEX product.idx_fce39616dcd6110 RENAME TO IDX_BF66B103DCD6110');
        $this->addSql('ALTER INDEX product.idx_fce3961661220ea6 RENAME TO IDX_BF66B10361220EA6');
        $this->addSql('ALTER INDEX product.idx_fce396166995ac4c RENAME TO IDX_BF66B1036995AC4C');
        $this->addSql('ALTER INDEX product.idx_d9e75195356b06d RENAME TO IDX_90063B76356B06D');
        $this->addSql('ALTER INDEX product.idx_d9e75195e447cd0c RENAME TO IDX_90063B76E447CD0C');
        $this->addSql('ALTER INDEX product.uniq_df8dfcbbbf396750 RENAME TO UNIQ_4D32EC41BF396750');
        $this->addSql('ALTER INDEX product.idx_df8dfcbbdcd6110 RENAME TO IDX_4D32EC41DCD6110');
        $this->addSql('ALTER INDEX product.idx_df8dfcbb61220ea6 RENAME TO IDX_4D32EC4161220EA6');
        $this->addSql('ALTER INDEX product.idx_df8dfcbb6995ac4c RENAME TO IDX_4D32EC416995AC4C');
        $this->addSql('ALTER INDEX product.idx_eb0c576b18c0e703 RENAME TO IDX_CC6D9BDC18C0E703');
        $this->addSql('ALTER INDEX product.idx_eb0c576be447cd0c RENAME TO IDX_CC6D9BDCE447CD0C');
        $this->addSql('ALTER INDEX product.uniq_8240adf5bf396750 RENAME TO UNIQ_C1C58AE0BF396750');
        $this->addSql('ALTER INDEX product.idx_8240adf5dcd6110 RENAME TO IDX_C1C58AE0DCD6110');
        $this->addSql('ALTER INDEX product.idx_8240adf561220ea6 RENAME TO IDX_C1C58AE061220EA6');
        $this->addSql('ALTER INDEX product.idx_8240adf56995ac4c RENAME TO IDX_C1C58AE06995AC4C');
        $this->addSql('ALTER INDEX product.idx_1e1cba30e3c4c960 RENAME TO IDX_57FDD0D3E3C4C960');
        $this->addSql('ALTER INDEX product.idx_1e1cba30e447cd0c RENAME TO IDX_57FDD0D3E447CD0C');
        $this->addSql('ALTER INDEX product.idx_30448162ed5ca9e6 RENAME TO IDX_73C1A677ED5CA9E6');
        $this->addSql('ALTER INDEX product.idx_304481624584665a RENAME TO IDX_73C1A6774584665A');
        $this->addSql('ALTER INDEX product.idx_ea6a2d3c4584665a RENAME TO IDX_47E763FD4584665A');
        $this->addSql('ALTER INDEX product.idx_ea6a2d3cdcd6110 RENAME TO IDX_47E763FDDCD6110');
        $this->addSql('ALTER INDEX product.uniq_8b84c957bf396750 RENAME TO UNIQ_E61AA89BBF396750');
        $this->addSql('ALTER INDEX product.idx_8b84c9576168c9a8 RENAME TO IDX_E61AA89B6168C9A8');
        $this->addSql('ALTER INDEX product.idx_8b84c957ac255694 RENAME TO IDX_E61AA89BAC255694');
        $this->addSql('ALTER INDEX product.idx_8b84c95761220ea6 RENAME TO IDX_E61AA89B61220EA6');
        $this->addSql('ALTER INDEX product.idx_8b84c9576995ac4c RENAME TO IDX_E61AA89B6995AC4C');
        $this->addSql('ALTER INDEX product.idx_13290300c10847fa RENAME TO IDX_2A6351A8C10847FA');
        $this->addSql('ALTER INDEX product.idx_13290300e447cd0c RENAME TO IDX_2A6351A8E447CD0C');
        $this->addSql('ALTER INDEX reference.uniq_e0f48d869f75d7b0 RENAME TO UNIQ_4F5AA1399F75D7B0');
        $this->addSql('ALTER INDEX reference.idx_e0f48d86c54c8c93 RENAME TO IDX_4F5AA139C54C8C93');
        $this->addSql('ALTER INDEX reference.uniq_fe1c7a289f75d7b0 RENAME TO UNIQ_7C1625279F75D7B0');
        $this->addSql('ALTER INDEX reference.idx_109b374ac54c8c93 RENAME TO IDX_92916845C54C8C93');
        $this->addSql('ALTER INDEX reference.idx_109b374a276973a0 RENAME TO IDX_92916845276973A0');
        $this->addSql('ALTER INDEX reference.idx_109b374a7e3c61f9 RENAME TO IDX_929168457E3C61F9');
        $this->addSql('ALTER INDEX reference.idx_109b374ae5b533f9 RENAME TO IDX_92916845E5B533F9');
        $this->addSql('ALTER TABLE reference.reference_pet_aggressive_types ALTER deleted SET DEFAULT \'false\'');
        $this->addSql('ALTER INDEX reference.idx_1f076a8fa8b4a30f RENAME TO IDX_4E821BB5A8B4A30F');
        $this->addSql('ALTER INDEX reference.uniq_342e2f659f75d7b0 RENAME TO UNIQ_F27373A69F75D7B0');
        $this->addSql('ALTER INDEX reference.uniq_9d9736709f75d7b0 RENAME TO UNIQ_39A0C5D99F75D7B0');
        $this->addSql('ALTER INDEX reference.idx_9d973670f8bd700d RENAME TO IDX_39A0C5D9F8BD700D');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE appointment.appointment_logs_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product.product_expense_number_seq CASCADE');
        $this->addSql('DROP SEQUENCE product.product_inventory_number_seq CASCADE');
        $this->addSql('DROP SEQUENCE product.product_receipt_number_seq CASCADE');
        $this->addSql('DROP SEQUENCE product.product_transfer_number_seq CASCADE');
        $this->addSql('DROP SEQUENCE reference.reference_pet_aggressive_types_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE product_expense_number_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_inventory_number_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_receipt_number_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_transfer_number_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE reference_pet_aggressive_types_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE pet.pet_index_search_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE pet.pet_index_search (id SERIAL NOT NULL, pet_id INT DEFAULT NULL, index TSVECTOR DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_49e63f07966f7fb6 ON pet.pet_index_search (pet_id)');
        $this->addSql('CREATE TABLE product.product_stock_logs (product_id INT DEFAULT NULL, quantity INT DEFAULT NULL, stock_id INT DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'now()\')');
        $this->addSql('ALTER TABLE pet.pet_index_search ADD CONSTRAINT fk_49e63f07966f7fb6 FOREIGN KEY (pet_id) REFERENCES pet.pets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE pet_index_search');
        $this->addSql('ALTER INDEX action.uniq_cfe3666677153098 RENAME TO uniq_47cc8c9277153098');
        $this->addSql('ALTER INDEX action.idx_cfe36666727aca70 RENAME TO idx_47cc8c92727aca70');
        $this->addSql('ALTER INDEX action.uniq_711ef95877153098 RENAME TO uniq_8a0e887977153098');
        $this->addSql('ALTER INDEX action.idx_711ef958727aca70 RENAME TO idx_8a0e8879727aca70');
        $this->addSql('ALTER INDEX cash.idx_937acc47a917cc69 RENAME TO idx_6f461f1da917cc69');
        $this->addSql('ALTER INDEX cash.idx_937acc47bb70bc0e RENAME TO idx_6f461f1dbb70bc0e');
        $this->addSql('ALTER TABLE cash.cash_receipt ADD uuid_receipt_mobile TEXT DEFAULT NULL');
        $this->addSql('ALTER INDEX cash.idx_e924e5d8a917cc69 RENAME TO idx_d75a419a917cc69');
        $this->addSql('ALTER INDEX cash.idx_e924e5d8bb70bc0e RENAME TO idx_d75a419bb70bc0e');
        $this->addSql('ALTER INDEX cash.uniq_e924e5d8ca1a8ecf RENAME TO uniq_d75a419ca1a8ecf');
        $this->addSql('ALTER INDEX cash.idx_e924e5d82edb0489 RENAME TO idx_d75a4192edb0489');
        $this->addSql('ALTER INDEX cash.idx_e924e5d861220ea6 RENAME TO idx_d75a41961220ea6');
        $this->addSql('ALTER INDEX form.idx_eed9203cf2b19fa9 RENAME TO idx_d8b2e19bf2b19fa9');
        $this->addSql('ALTER INDEX form.idx_eed9203cc06dbc6f RENAME TO idx_d8b2e19bc06dbc6f');
        $this->addSql('ALTER INDEX product.idx_62698e13f8bd700d RENAME TO idx_d34a04adf8bd700d');
        $this->addSql('ALTER INDEX product.idx_bf66b10361220ea6 RENAME TO idx_fce3961661220ea6');
        $this->addSql('ALTER INDEX product.uniq_bf66b103bf396750 RENAME TO uniq_fce39616bf396750');
        $this->addSql('ALTER INDEX product.idx_bf66b103dcd6110 RENAME TO idx_fce39616dcd6110');
        $this->addSql('ALTER INDEX product.idx_bf66b1036995ac4c RENAME TO idx_fce396166995ac4c');
        $this->addSql('ALTER INDEX product.idx_90063b76e447cd0c RENAME TO idx_d9e75195e447cd0c');
        $this->addSql('ALTER INDEX product.idx_90063b76356b06d RENAME TO idx_d9e75195356b06d');
        $this->addSql('ALTER INDEX product.idx_4d32ec41dcd6110 RENAME TO idx_df8dfcbbdcd6110');
        $this->addSql('ALTER INDEX product.idx_4d32ec4161220ea6 RENAME TO idx_df8dfcbb61220ea6');
        $this->addSql('ALTER INDEX product.idx_4d32ec416995ac4c RENAME TO idx_df8dfcbb6995ac4c');
        $this->addSql('ALTER INDEX product.uniq_4d32ec41bf396750 RENAME TO uniq_df8dfcbbbf396750');
        $this->addSql('ALTER INDEX pet.idx_59c2c62a86e64d89 RENAME TO idx_8638ea3f86e64d89');
        $this->addSql('ALTER INDEX pet.idx_59c2c62aa8b4a30f RENAME TO idx_8638ea3fa8b4a30f');
        $this->addSql('ALTER INDEX pet.idx_59c2c62a6d4b3c40 RENAME TO idx_8638ea3f6d4b3c40');
        $this->addSql('ALTER INDEX pet.idx_59c2c62a75274dce RENAME TO idx_8638ea3f75274dce');
        $this->addSql('ALTER INDEX pet.uniq_59c2c62a9f75d7b0 RENAME TO uniq_8638ea3f9f75d7b0');
        $this->addSql('ALTER INDEX pet.idx_59c2c62a181f0d72 RENAME TO idx_8638ea3f181f0d72');
        $this->addSql('ALTER INDEX product.idx_cc6d9bdce447cd0c RENAME TO idx_eb0c576be447cd0c');
        $this->addSql('ALTER INDEX product.idx_cc6d9bdc18c0e703 RENAME TO idx_eb0c576b18c0e703');
        $this->addSql('ALTER INDEX product.uniq_c1c58ae0bf396750 RENAME TO uniq_8240adf5bf396750');
        $this->addSql('ALTER INDEX product.idx_c1c58ae06995ac4c RENAME TO idx_8240adf56995ac4c');
        $this->addSql('ALTER INDEX product.idx_c1c58ae0dcd6110 RENAME TO idx_8240adf5dcd6110');
        $this->addSql('ALTER INDEX product.idx_c1c58ae061220ea6 RENAME TO idx_8240adf561220ea6');
        $this->addSql('ALTER INDEX product.idx_57fdd0d3e447cd0c RENAME TO idx_1e1cba30e447cd0c');
        $this->addSql('ALTER INDEX product.idx_57fdd0d3e3c4c960 RENAME TO idx_1e1cba30e3c4c960');
        $this->addSql('ALTER INDEX product.idx_73c1a677ed5ca9e6 RENAME TO idx_30448162ed5ca9e6');
        $this->addSql('ALTER INDEX product.idx_73c1a6774584665a RENAME TO idx_304481624584665a');
        $this->addSql('ALTER TABLE reference.reference_pet_aggressive_types ALTER deleted SET DEFAULT \'false\'');
        $this->addSql('ALTER INDEX reference.uniq_39a0c5d99f75d7b0 RENAME TO uniq_9d9736709f75d7b0');
        $this->addSql('ALTER INDEX reference.idx_39a0c5d9f8bd700d RENAME TO idx_9d973670f8bd700d');
        $this->addSql('ALTER TABLE appointment.appointment_index_search ALTER id DROP DEFAULT');
        $this->addSql('ALTER INDEX form.idx_1393c004549213ec RENAME TO idx_6127d99e549213ec');
        $this->addSql('ALTER INDEX form.idx_1393c004443707b0 RENAME TO idx_6127d99e443707b0');
        $this->addSql('ALTER INDEX pet.idx_ec160d1966f7fb6 RENAME TO idx_8d2bde81966f7fb6');
        $this->addSql('ALTER INDEX pet.idx_ec160d17e3c61f9 RENAME TO idx_8d2bde817e3c61f9');
        $this->addSql('ALTER INDEX pet.idx_f102272b966f7fb6 RENAME TO idx_25ef326f966f7fb6');
        $this->addSql('ALTER INDEX pet.idx_f102272bc54c8c93 RENAME TO idx_25ef326fc54c8c93');
        $this->addSql('ALTER INDEX pet.idx_c56830c2966f7fb6 RENAME TO idx_8bf69e79966f7fb6');
        $this->addSql('ALTER INDEX pet.idx_c56830c23f85796b RENAME TO idx_8bf69e793f85796b');
        $this->addSql('ALTER INDEX pet.idx_8f93a6eb966f7fb6 RENAME TO idx_fef6ce77966f7fb6');
        $this->addSql('ALTER INDEX pet.idx_c0c2540f966f7fb6 RENAME TO idx_4328ea5f966f7fb6');
        $this->addSql('ALTER INDEX cash.idx_d1fd12be5de77e3d RENAME TO idx_3d7ab1d95de77e3d');
        $this->addSql('ALTER INDEX cash.idx_d1fd12be32c8a3de RENAME TO idx_3d7ab1d932c8a3de');
        $this->addSql('ALTER INDEX cash.idx_b346ef08f8bd700d RENAME TO idx_fdb06c21f8bd700d');
        $this->addSql('ALTER INDEX form.uniq_8654344a77153098 RENAME TO uniq_eb0f606977153098');
        $this->addSql('ALTER INDEX form.idx_ccfa7c23f50d82f4 RENAME TO idx_dc52b260f50d82f4');
        $this->addSql('ALTER INDEX form.idx_ccfa7c23d4054342 RENAME TO idx_dc52b260d4054342');
        $this->addSql('ALTER INDEX form.uniq_57bd0d5677153098 RENAME TO uniq_96d5720777153098');
        $this->addSql('ALTER INDEX form.idx_dd1657c3f50d82f4 RENAME TO idx_c1bb5edef50d82f4');
        $this->addSql('ALTER INDEX form.idx_dd1657c3241f0df5 RENAME TO idx_c1bb5ede241f0df5');
        $this->addSql('ALTER TABLE form.form_template ALTER active SET DEFAULT \'false\'');
        $this->addSql('ALTER INDEX form.uniq_f2ec8fcb77153098 RENAME TO uniq_265a9ac777153098');
        $this->addSql('ALTER INDEX form.idx_b3bd39cef2b19fa9 RENAME TO idx_dee66dedf2b19fa9');
        $this->addSql('ALTER INDEX form.idx_b3bd39ce2b68a933 RENAME TO idx_dee66ded2b68a933');
        $this->addSql('ALTER INDEX product.idx_47e763fd4584665a RENAME TO idx_ea6a2d3c4584665a');
        $this->addSql('ALTER INDEX product.idx_47e763fddcd6110 RENAME TO idx_ea6a2d3cdcd6110');
        $this->addSql('ALTER INDEX product.idx_e61aa89bac255694 RENAME TO idx_8b84c957ac255694');
        $this->addSql('ALTER INDEX product.idx_e61aa89b6168c9a8 RENAME TO idx_8b84c9576168c9a8');
        $this->addSql('ALTER INDEX product.idx_e61aa89b61220ea6 RENAME TO idx_8b84c95761220ea6');
        $this->addSql('ALTER INDEX product.uniq_e61aa89bbf396750 RENAME TO uniq_8b84c957bf396750');
        $this->addSql('ALTER INDEX product.idx_e61aa89b6995ac4c RENAME TO idx_8b84c9576995ac4c');
        $this->addSql('ALTER INDEX product.idx_2a6351a8c10847fa RENAME TO idx_13290300c10847fa');
        $this->addSql('ALTER INDEX product.idx_2a6351a8e447cd0c RENAME TO idx_13290300e447cd0c');
        $this->addSql('ALTER INDEX reference.uniq_4f5aa1399f75d7b0 RENAME TO uniq_e0f48d869f75d7b0');
        $this->addSql('ALTER INDEX reference.idx_4f5aa139c54c8c93 RENAME TO idx_e0f48d86c54c8c93');
        $this->addSql('ALTER INDEX reference.uniq_7c1625279f75d7b0 RENAME TO uniq_fe1c7a289f75d7b0');
        $this->addSql('ALTER INDEX reference.idx_92916845c54c8c93 RENAME TO idx_109b374ac54c8c93');
        $this->addSql('ALTER INDEX reference.idx_92916845276973a0 RENAME TO idx_109b374a276973a0');
        $this->addSql('ALTER INDEX reference.idx_929168457e3c61f9 RENAME TO idx_109b374a7e3c61f9');
        $this->addSql('ALTER INDEX reference.idx_92916845e5b533f9 RENAME TO idx_109b374ae5b533f9');
        $this->addSql('ALTER INDEX reference.idx_4e821bb5a8b4a30f RENAME TO idx_1f076a8fa8b4a30f');
        $this->addSql('ALTER INDEX reference.uniq_f27373a69f75d7b0 RENAME TO uniq_342e2f659f75d7b0');
    }
}

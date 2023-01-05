<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211002100340 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("DROP TRIGGER IF EXISTS action_group_items_count on action.action_group");
        $this->addSql("DROP TRIGGER IF EXISTS icon_items_count on icon");
        $this->addSql("DROP TRIGGER IF EXISTS reference_owner_activities_items_count on reference.reference_owner_activities");
        $this->addSql("DROP TRIGGER IF EXISTS reference_breeds_items_count on reference.reference_breeds");
        $this->addSql("DROP TRIGGER IF EXISTS reference_owner_legal_forms_items_count on reference.reference_owner_legal_forms");
        $this->addSql("DROP TRIGGER IF EXISTS event_status_items_count on event_status");
        $this->addSql("DROP TRIGGER IF EXISTS appointment_status_items_count on appointment.appointment_status");
        $this->addSql("DROP TRIGGER IF EXISTS reference_pet_types_items_count on reference.reference_pet_types");
        $this->addSql("DROP TRIGGER IF EXISTS reference_pet_identifier_types_items_count on reference.reference_pet_identifier_types");
        $this->addSql("DROP TRIGGER IF EXISTS reference_event_types_items_count on reference.reference_event_types");
        $this->addSql("DROP TRIGGER IF EXISTS reference_file_types_items_count on reference.reference_file_types");
        $this->addSql("DROP TRIGGER IF EXISTS groups_items_count on groups");
        $this->addSql("DROP TRIGGER IF EXISTS print_form_items_count on print_form");
        $this->addSql("DROP TRIGGER IF EXISTS reference_animal_death_items_count on reference.reference_animal_death");
        $this->addSql("DROP TRIGGER IF EXISTS reference_profession_items_count on reference.reference_profession");
        $this->addSql("DROP TRIGGER IF EXISTS unit_items_count on unit");
        $this->addSql("DROP TRIGGER IF EXISTS contractor_items_count on contractor");
        $this->addSql("DROP TRIGGER IF EXISTS reference_sterilization_type_items_count on reference.reference_sterilization_type");
        $this->addSql("DROP TRIGGER IF EXISTS reference_vaccination_type_items_count on reference.reference_vaccination_type");
        $this->addSql("DROP TRIGGER IF EXISTS reference_shelter_items_count on reference.reference_shelter");
        $this->addSql("DROP TRIGGER IF EXISTS reference_tag_colors_items_count on reference.reference_tag_colors");
        $this->addSql("DROP TRIGGER IF EXISTS reference_tag_forms_items_count on reference.reference_tag_forms");
        $this->addSql("DROP TRIGGER IF EXISTS reference_stock_items_count on reference.reference_stock");
        $this->addSql("DROP TRIGGER IF EXISTS reference_pet_lear_items_count on reference.reference_pet_lear");
        $this->addSql("DROP TRIGGER IF EXISTS template_reference_items_count on template_reference");
        $this->addSql("DROP TRIGGER IF EXISTS template_reference_value_items_count on template_reference_value");
        $this->addSql("DROP TRIGGER IF EXISTS reference_veterinary_passport_type_items_count on reference.reference_veterinary_passport_type");
        $this->addSql("DROP TRIGGER IF EXISTS reference_pet_aggressive_types_items_count on reference.reference_pet_aggressive_types");
        $this->addSql("DROP TRIGGER IF EXISTS reference_manufacturer_items_count on reference.reference_manufacturer");
        $this->addSql("DROP TRIGGER IF EXISTS reference_category_nomenclature_items_count on reference.reference_category_nomenclature");
        $this->addSql("DROP TRIGGER IF EXISTS reference_release_form_items_count on reference.reference_release_form");
        $this->addSql("DROP TRIGGER IF EXISTS reference_reason_retiring_items_count on reference.reference_reason_retiring");
        $this->addSql("drop FUNCTION update_items_count()");

        $this->addSql("CREATE FUNCTION public.update_items_count() RETURNS trigger LANGUAGE 'plpgsql' COST 100 VOLATILE NOT LEAKPROOF AS \$BODY\$
                         BEGIN
                             EXECUTE 'update action.action set items_count = (select count(*) from '||TG_TABLE_SCHEMA||'.'||TG_TABLE_NAME||' where deleted=false) where deleted=false and url=$1 and type=$2' USING TG_ARGV[0], 'ENTIT
                 Y_LIST_URL';
                             RETURN NULL;
                         END; \$BODY\$");

        $this->addSql('ALTER FUNCTION public.update_items_count() OWNER TO korvet;');
        $this->addSql("CREATE TRIGGER action_group_items_count AFTER INSERT OR DELETE OR UPDATE ON action.action_group FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/action-group');");
        $this->addSql("CREATE TRIGGER icon_items_count AFTER INSERT OR DELETE OR UPDATE ON icon FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/icon');");
        $this->addSql("CREATE TRIGGER reference_owner_activities_items_count AFTER INSERT OR DELETE OR UPDATE ON reference.reference_owner_activities FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/owner-activity');");
        $this->addSql("CREATE TRIGGER reference_breeds_items_count AFTER INSERT OR DELETE OR UPDATE ON reference.reference_breeds FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/breed');");
        $this->addSql("CREATE TRIGGER reference_owner_legal_forms_items_count AFTER INSERT OR DELETE OR UPDATE ON reference.reference_owner_legal_forms FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/owner-legal-form');");
        $this->addSql("CREATE TRIGGER event_status_items_count AFTER INSERT OR DELETE OR UPDATE ON event_status FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/event-status');");
        $this->addSql("CREATE TRIGGER appointment_status_items_count AFTER INSERT OR DELETE OR UPDATE ON appointment.appointment_status FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/appointment-status');");
        $this->addSql("CREATE TRIGGER reference_pet_types_items_count AFTER INSERT OR DELETE OR UPDATE ON reference.reference_pet_types FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/pets-type');");
        $this->addSql("CREATE TRIGGER reference_pet_identifier_types_items_count AFTER INSERT OR DELETE OR UPDATE ON reference.reference_pet_identifier_types FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/pet-identifier-type');");
        $this->addSql("CREATE TRIGGER reference_event_types_items_count AFTER INSERT OR DELETE OR UPDATE ON reference.reference_event_types FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/event-type');");
        $this->addSql("CREATE TRIGGER reference_file_types_items_count AFTER INSERT OR DELETE OR UPDATE ON reference.reference_file_types FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/file-type');");
        $this->addSql("CREATE TRIGGER groups_items_count AFTER INSERT OR DELETE OR UPDATE ON groups FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/group');");
        $this->addSql("CREATE TRIGGER print_form_items_count AFTER INSERT OR DELETE OR UPDATE ON print_form FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/print-forms');");
        $this->addSql("CREATE TRIGGER reference_animal_death_items_count AFTER INSERT OR DELETE OR UPDATE ON reference.reference_animal_death FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/animal-death');");
        $this->addSql("CREATE TRIGGER reference_profession_items_count AFTER INSERT OR DELETE OR UPDATE ON reference.reference_profession FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/profession');");
        $this->addSql("CREATE TRIGGER unit_items_count AFTER INSERT OR DELETE OR UPDATE ON unit FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/cash/unit');");
        $this->addSql("CREATE TRIGGER contractor_items_count AFTER INSERT OR DELETE OR UPDATE ON contractor FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/contractor');");
        $this->addSql("CREATE TRIGGER reference_sterilization_type_items_count AFTER INSERT OR DELETE OR UPDATE ON reference.reference_sterilization_type FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/sterilization-type');");
        $this->addSql("CREATE TRIGGER reference_vaccination_type_items_count AFTER INSERT OR DELETE OR UPDATE ON reference.reference_vaccination_type FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/vaccination-type');");
        $this->addSql("CREATE TRIGGER reference_shelter_items_count AFTER INSERT OR DELETE OR UPDATE ON reference.reference_shelter FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/shelter');");
        $this->addSql("CREATE TRIGGER reference_tag_colors_items_count AFTER INSERT OR DELETE OR UPDATE ON reference.reference_tag_colors FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/tag-color');");
        $this->addSql("CREATE TRIGGER reference_tag_forms_items_count AFTER INSERT OR DELETE OR UPDATE ON reference.reference_tag_forms FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/tag-form');");
        $this->addSql("CREATE TRIGGER reference_stock_items_count AFTER INSERT OR DELETE OR UPDATE ON reference.reference_stock FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/stock');");
        $this->addSql("CREATE TRIGGER reference_pet_lear_items_count AFTER INSERT OR DELETE OR UPDATE ON reference.reference_pet_lear FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/pet-lear');");
        $this->addSql("CREATE TRIGGER template_reference_items_count AFTER INSERT OR DELETE OR UPDATE ON template_reference FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/template-reference');");
        $this->addSql("CREATE TRIGGER template_reference_value_items_count AFTER INSERT OR DELETE OR UPDATE ON template_reference_value FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/template-reference-values');");
        $this->addSql("CREATE TRIGGER reference_veterinary_passport_type_items_count AFTER INSERT OR DELETE OR UPDATE ON reference.reference_veterinary_passport_type FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/type-vet-passport');");
        $this->addSql("CREATE TRIGGER reference_pet_aggressive_types_items_count AFTER INSERT OR DELETE OR UPDATE ON reference.reference_pet_aggressive_types FOR EACH STATEMENT EXECUTE PROCEDURE update_items_count('/admin/references/pet-aggressive-type');");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("DROP TRIGGER IF EXISTS action_group_items_count on action.action_group");
        $this->addSql("DROP TRIGGER IF EXISTS icon_items_count on icon");
        $this->addSql("DROP TRIGGER IF EXISTS reference_owner_activities_items_count on reference.reference_owner_activities");
        $this->addSql("DROP TRIGGER IF EXISTS reference_breeds_items_count on reference.reference_breeds");
        $this->addSql("DROP TRIGGER IF EXISTS reference_owner_legal_forms_items_count on reference.reference_owner_legal_forms");
        $this->addSql("DROP TRIGGER IF EXISTS event_status_items_count on event_status");
        $this->addSql("DROP TRIGGER IF EXISTS appointment_status_items_count on appointment.appointment_status");
        $this->addSql("DROP TRIGGER IF EXISTS reference_pet_types_items_count on reference.reference_pet_types");
        $this->addSql("DROP TRIGGER IF EXISTS reference_pet_identifier_types_items_count on reference.reference_pet_identifier_types");
        $this->addSql("DROP TRIGGER IF EXISTS reference_event_types_items_count on reference.reference_event_types");
        $this->addSql("DROP TRIGGER IF EXISTS reference_file_types_items_count on reference.reference_file_types");
        $this->addSql("DROP TRIGGER IF EXISTS groups_items_count on groups");
        $this->addSql("DROP TRIGGER IF EXISTS print_form_items_count on print_form");
        $this->addSql("DROP TRIGGER IF EXISTS reference_animal_death_items_count on reference.reference_animal_death");
        $this->addSql("DROP TRIGGER IF EXISTS reference_profession_items_count on reference.reference_profession");
        $this->addSql("DROP TRIGGER IF EXISTS unit_items_count on unit");
        $this->addSql("DROP TRIGGER IF EXISTS contractor_items_count on contractor");
        $this->addSql("DROP TRIGGER IF EXISTS reference_sterilization_type_items_count on reference.reference_sterilization_type");
        $this->addSql("DROP TRIGGER IF EXISTS reference_vaccination_type_items_count on reference.reference_vaccination_type");
        $this->addSql("DROP TRIGGER IF EXISTS reference_shelter_items_count on reference.reference_shelter");
        $this->addSql("DROP TRIGGER IF EXISTS reference_tag_colors_items_count on reference.reference_tag_colors");
        $this->addSql("DROP TRIGGER IF EXISTS reference_tag_forms_items_count on reference.reference_tag_forms");
        $this->addSql("DROP TRIGGER IF EXISTS reference_stock_items_count on reference.reference_stock");
        $this->addSql("DROP TRIGGER IF EXISTS reference_pet_lear_items_count on reference.reference_pet_lear");
        $this->addSql("DROP TRIGGER IF EXISTS template_reference_items_count on template_reference");
        $this->addSql("DROP TRIGGER IF EXISTS template_reference_value_items_count on template_reference_value");
        $this->addSql("DROP TRIGGER IF EXISTS reference_veterinary_passport_type_items_count on reference.reference_veterinary_passport_type");
        $this->addSql("DROP TRIGGER IF EXISTS reference_pet_aggressive_types_items_count on reference.reference_pet_aggressive_types");
        $this->addSql("DROP TRIGGER IF EXISTS reference_manufacturer_items_count on reference.reference_manufacturer");
        $this->addSql("DROP TRIGGER IF EXISTS reference_category_nomenclature_items_count on reference.reference_category_nomenclature");
        $this->addSql("DROP TRIGGER IF EXISTS reference_release_form_items_count on reference.reference_release_form");
        $this->addSql("DROP TRIGGER IF EXISTS reference_reason_retiring_items_count on reference.reference_reason_retiring");
        $this->addSql("drop FUNCTION update_items_count()");
    }
}

<template>
  <div>
    <v-row>
      <v-col cols="12">
        <div class="title-h2">Объекты недвижимости для хранения зерна</div>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" md="6" lg="3" xl="3">
        <div class="elementsInput">
          <InputComponent
            v-model="totalCapacity"
            v-bind="getProps('capacity_tons')"
            label="Общая мощность хранения зерна в зернохранилищах, тонны"
            disabled
            :maxlength="12"
            name="totalCapacity"
          />

          <CardOldValue v-if="isEditCode('capacity_tons', 'NONE')" :data="elevatorInfoChanges" prop="capacity_tons" />
        </div>
      </v-col>
    </v-row>
    <v-row>
      <v-col xs="12" md="10" xl="8">
        <EditableTable
          v-model="form.elevator_site"
          :options="headers"
          :max="999"
          :is-can-edit="!isShowcase"
          is-show-card-button
          :is-showcase="isShowcase"
          edit-strategy="outer"
          @edit="showLocationCard"
        />
      </v-col>
    </v-row>
    <StorageLocationsForm
      v-if="!!storage"
      :is-view-type="isShowcase"
      :storage="storage"
      :changes-data="changesData"
      @click-close="handleCloseForm"
      @submit="(v) => handleSubmit(v, storage.index)"
    />
  </div>
</template>

<script lang="ts">
import get from 'lodash/get';
import { Component, Mixins, Watch, Prop } from 'vue-property-decorator';
import EditableTable from '@/components/common/Table/index.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import StorageLocationsForm from '@/views/Requests/components/StorageLocationsForm/StorageLocationsForm.vue';
import FormMixin from '@/views/RegisterOrganizations/components/CardOrganization/components/LocationInfo/FormMixin.vue';
import { address } from '@/utils/global/filters';
import { ESubjectType } from '@/services/enums/subject';

@Component({
  name: 'StorageBuildingsForm',
  components: { EditableTable, StorageLocationsForm, InputComponent },
})
export default class extends Mixins(FormMixin) {
  @Prop({ type: Number, default: 0 }) readonly totalCapacity!: number;
  locationType: any[] = [];
  storage: any = null;
  editMode = true;
  dirtyCheck = false;

  get headers() {
    return [
      {
        label: 'Кадастровый номер',
        name: 'cadastral_number',
        width: 300,
      },
      {
        label: 'Адрес',
        name: 'address_string',
        width: 350,
      },
      {
        label: 'Вид зернохранилища',
        controlType: 'select',
        name: 'granary_type',
        restrictions: this.locationType,
        width: 200,
      },
      {
        label: 'Вместимость (тонн)',
        name: 'capacity_tons',
        width: 200,
      },
    ];
  }

  created() {
    this.fetchLocationType();
  }

  async fetchLocationType() {
    const { content } = await this.$store.dispatch('elevator/getListGranaryType', { actual: true });

    this.locationType = content.map(({ name, code }) => ({
      label: name,
      value: code,
    }));
  }

  showLocationCard(index: number) {
    this.editMode = true;

    this.storage = {
      data: (this.form.elevator_site || []).find((_, i) => i === index),
      subjectName: this.form.subject.name,
      form: this.form,
      index,
    };
  }

  handleSubmit(form, index) {
    this.handleCloseForm();
    if (typeof index !== 'undefined') {
      this.form.elevator_site = (this.form.elevator_site || []).map((item, i) => {
        if (i === index) {
          return form;
        }

        return item;
      });
    } else {
      this.form.elevator_site = [...(this.form.elevator_site || []), form];
    }
  }

  handleCloseForm() {
    this.storage = null;
  }

  @Watch('form.elevator_site', { deep: true, immediate: true })
  mapForm() {
    if (!this.dirtyCheck && this.form.elevator_site && this.form.elevator_site.length !== 0) {
      this.dirtyCheck = true;
      this.form.elevator_site = this.form.elevator_site.map((element) => {
        const result = {
          ...element,
          address_string: element.address
            ? `${address(element.address)} ${
                element.address.additional_info ? `(${element.address.additional_info})` : ''
              }`
            : '',
          granary_type: {
            ...element.granary_type,
            label: element.granary_type?.name,
            value: element.granary_type?.code,
          },
          elevator_site_storage: {
            ...(element.elevator_site_storage || {}),
            label: element.elevator_site_storage?.name,
            value: element.elevator_site_storage?.code,
          },
          capacity_tons: element.capacity_tons || 0,
        };

        if (element.own_rent) {
          result.own_rent = { ...element.own_rent, label: element.own_rent.name, value: element.own_rent.code };
        }

        return result;
      });
    } else {
      this.dirtyCheck = false;
    }
  }

  @Watch('form.subject.subject_type')
  _onChangeSubjectType(value) {
    this.form.elevator_site = this.form.elevator_site.map((element) => {
      const addressCode = get(element, 'address.country.code_alpha2') === 'RU' ? 1 : 0;
      const companyCode = !value || [ESubjectType.IR, ESubjectType.IF].includes(value) ? 0 : 1;

      if (addressCode !== companyCode) {
        return {
          ...element,
          address: {},
        };
      }

      return element;
    });
  }
}
</script>

<style lang="scss" scoped>
.elementsInput {
  position: relative;
}
</style>

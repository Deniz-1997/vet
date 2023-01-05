<template>
  <div>
    <v-row>
      <v-col cols="12">
        <div class="title-h2">Сведения о консервации</div>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="6" lg="4" xl="4">
        <div class="elementsInput">
          <UiControl name="capacity_mothballed" :value="form.elevator_info.capacity_mothballed">
            <InputComponent
              v-model="innerCapacity"
              v-bind="getProps('capacity_mothballed')"
              label="Мощность законсервированных зернохранилищ, тонны"
              :mask="fractionalNumberMask"
              :disabled="isShowcase"
              name="elevator_info.capacity_mothballed"
            />
          </UiControl>

          <CardOldValue
            v-if="isEditCode('capacity_mothballed', 'NONE')"
            :data="elevatorInfoChanges"
            prop="capacity_mothballed"
          />
        </div>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <div class="title-h2" style="padding-top: 20px">Года консервации</div>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="6" md="6" lg="3" xl="3">
        <EditableTable
          v-model="form.elevator_info.elevator_info_mothballed_year"
          :options="headers"
          :is-can-edit="!isShowcase"
          :max="999"
          :is-showcase="isShowcase"
          hide-header
          id-table="elevator_info_mothballed_year"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <div :class="getClass('has_testing_laboratory')">
          <UiCheckbox
            id="has_testing_laboratory"
            class="checkbox-block"
            name="has_testing_laboratory"
            label="Наличие испытательной лаборатории, аккредитованной в национальной системе аккредитации"
            :value="has_testing_laboratory"
            :checked="form.elevator_info.testing_laboratory === 'NATIONAL'"
            :disabled="isShowcase"
            @input="(v) => onChange('has_testing_laboratory', v)"
          />
        </div>
        <div :class="getClass('has_registry_laboratory')">
          <UiCheckbox
            id="has_registry_laboratory"
            class="checkbox-block"
            name="has_registry_laboratory"
            label="Наличие испытательной лаборатории, включенной в единый реестр органов по оценке соответствия Евразийского экономического союза"
            :value="has_registry_laboratory"
            :checked="form.elevator_info.testing_laboratory === 'EURASIA'"
            :disabled="isShowcase"
            @input="(v) => onChange('has_registry_laboratory', v)"
          />
        </div>
        <div>
          <UiCheckbox
            id="no_laboratory"
            name="no_laboratory"
            class="checkbox-block"
            label="Отсутствие испытательной лаборатории"
            :checked="!form.elevator_info.testing_laboratory || form.elevator_info.testing_laboratory === 'NONE'"
            :disabled="
              isShowcase || !form.elevator_info.testing_laboratory || form.elevator_info.testing_laboratory === 'NONE'
            "
            @input="(v) => onChange('no_laboratory', v)"
          />
        </div>
      </v-col>
    </v-row>
  </div>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import { fractionalNumberMask } from '@/components/common/inputs/mask/fractionalNumber';
import EditableTable from '@/components/common/Table/index.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import CardOldValue from '@/views/RegisterOrganizations/components/CardOrganization/components/CardOldValue.vue';
import FormMixin from '@/views/RegisterOrganizations/components/CardOrganization/components/LocationInfo/FormMixin.vue';

@Component({
  name: 'ConservationDataForm',
  components: { EditableTable, CardOldValue, InputComponent },
})
export default class extends Mixins(FormMixin) {
  fractionalNumberMask = fractionalNumberMask;

  has_testing_laboratory = false;
  has_registry_laboratory = false;

  get headers() {
    return [
      {
        text: 'Год',
        name: 'year_val',
        controlType: 'year',
        width: 300,
      },
    ];
  }

  get innerCapacity() {
    if (!this.form.elevator_info.capacity_mothballed) {
      return '';
    }

    return String(this.form.elevator_info.capacity_mothballed).trim();
  }

  set innerCapacity(v) {
    this.set({ capacity_mothballed: v });
  }

  set(data) {
    Object.assign(this.form, {
      ...this.form,
      elevator_info: {
        ...this.form.elevator_info,
        ...data,
      },
    });
  }

  onChange(type, v) {
    if ((v && type === 'has_testing_laboratory')) {
      this.set({ testing_laboratory: 'NATIONAL' });
    }

    if (v && type === 'has_registry_laboratory') {
      this.set({ testing_laboratory: 'EURASIA' });
    }

    if (!v && type === 'no_laboratory') {
      this.set({ testing_laboratory: 'NONE' });
    }
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.edit {
  background: rgba($gold-light-color, 0.3);
  color: $black-color;

  .label {
    color: $black-color;
  }
}

.delete {
  background: rgba($del-color, 0.3);
  color: $black-color;

  .label {
    color: $black-color;
  }
}

.add {
  background: rgba($added-color, 0.3);
  color: $black-color;

  .label {
    color: $black-color;
  }
}

.elementsInput {
  position: relative;
}
</style>

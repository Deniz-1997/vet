<template>
  <v-expansion-panel class="elementsForm">
    <v-expansion-panel-header>Автомобильный транспорт</v-expansion-panel-header>
    <v-expansion-panel-content eager>
      <UiForm :rules="rules" @validation="(v) => $emit('validate', v)">
        <v-row class="end">
          <v-col cols="12">
            <div :class="getClass('auto_in')">
              <UiCheckbox
                id="auto_in"
                v-model="form.elevator_info.auto_in"
                class="checkbox-block"
                name="auto_in"
                :disabled="isShowcase"
                label="Приёмка автомобильным транспортом"
                @input="changeCheckbox()"
              />
            </div>
            <div :class="getClass('auto_out')">
              <UiCheckbox
                id="auto_out"
                v-model="form.elevator_info.auto_out"
                class="checkbox-block"
                name="auto_out"
                :disabled="isShowcase"
                label="Отгрузка автомобильным транспортом"
                @input="changeCheckbox()"
              />
            </div>
          </v-col>
        </v-row>
        <v-row class="end">
          <v-col cols="12" lg="5" xl="4" class="logistic__input-group">
            <div class="elementsInput">
              <UiControl name="loading_capacity_auto_tons" :value="form.elevator_info.loading_capacity_auto_tons">
                <InputComponent
                  v-model="form.elevator_info.loading_capacity_auto_tons"
                  :disabled="isShowcase || !(form.elevator_info.auto_out || form.elevator_info.auto_in)"
                  :mask="form.elevator_info.loading_capacity_auto_tons ? mask : null"
                  label="Мощность погрузки автомобильным транспортом (тонн в сутки)"
                  name="elevator_info.loading_capacity_auto_tons"
                />
              </UiControl>
              <CardOldValue
                v-if="isEditCode('loading_capacity_auto_tons', 'NONE')"
                :data="elevatorInfoChanges"
                prop="loading_capacity_auto_tons"
              />
            </div>
          </v-col>
          <v-col cols="12" lg="5" xl="4">
            <div class="elementsInput">
              <UiControl name="loading_capacity_auto" :value="form.elevator_info.loading_capacity_auto">
                <InputComponent
                  v-model="form.elevator_info.loading_capacity_auto"
                  label="Мощность погрузки автомобильным транспортом (кол-во автомашин в сутки)"
                  :disabled="isShowcase || !(form.elevator_info.auto_in || form.elevator_info.auto_out)"
                  :mask="form.elevator_info.loading_capacity_auto ? mask : null"
                  name="elevator_info.loading_capacity_auto"
                />
              </UiControl>
              <CardOldValue
                v-if="isEditCode('loading_capacity_auto', 'NONE')"
                :data="elevatorInfoChanges"
                prop="loading_capacity_auto"
              />
            </div>
          </v-col>
        </v-row>
      </UiForm>
    </v-expansion-panel-content>
  </v-expansion-panel>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import { numberMask } from '@/components/common/inputs/mask/number';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import CardOldValue from '@/views/RegisterOrganizations/components/CardOrganization/components/CardOldValue.vue';
import FormMixin from '@/views/RegisterOrganizations/components/CardOrganization/components/LocationInfo/FormMixin.vue';

@Component({
  name: 'CarLogisticForm',
  components: { CardOldValue, InputComponent },
})
export default class extends Mixins(FormMixin) {
  mask = numberMask;

  get rules() {
    const result: any = {};

    if (this.form.elevator_info.auto_in || this.form.elevator_info.auto_out) {
      result.loading_capacity_auto_tons = 'required';
      result.loading_capacity_auto = 'required';
    }

    return result;
  }

  mounted() {
    const values = [
      this.form.elevator_info.auto_in,
      this.form.elevator_info.auto_out,
      this.form.elevator_info.loading_capacity_auto_tons,
      this.form.elevator_info.loading_capacity_auto,
    ].filter((v) => v);

    if (values.length) {
      this.$emit('open');
    }
  }

  changeCheckbox() {
    if (!this.form.elevator_info.auto_in && !this.form.elevator_info.auto_out) {
      this.form.elevator_info.loading_capacity_auto_tons = null;
      this.form.elevator_info.loading_capacity_auto = null;
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
  width: 100%;
}

.end {
  display: flex;
  align-items: flex-end;

  .checkbox-block {
    height: 40px;
    margin-bottom: 0;
  }
}

.logistic__input-group {
  display: flex;
  align-items: flex-end;
  width: 100%;
}
</style>

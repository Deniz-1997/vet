<template>
  <v-expansion-panel class="elementsForm">
    <v-expansion-panel-header>Водный транспорт</v-expansion-panel-header>
    <v-expansion-panel-content eager>
      <UiForm :rules="rules" @validation="(v) => $emit('validate', v)">
        <v-row class="end">
          <v-col cols="12">
            <div :class="getClass('water_in')">
              <UiCheckbox
                id="water_in"
                v-model="form.elevator_info.water_in"
                class="checkbox-block"
                name="water_in"
                :disabled="isShowcase"
                label="Приёмка водным транспортом"
                @input="changeCheckbox()"
              />
            </div>
            <div :class="getClass('water_out')">
              <UiCheckbox
                id="water_out"
                v-model="form.elevator_info.water_out"
                class="checkbox-block"
                name="water_out"
                :disabled="isShowcase"
                label="Отгрузка водным транспортом"
                @input="changeCheckbox()"
              />
            </div>
          </v-col>
        </v-row>
        <v-row class="end">
          <v-col cols="12" lg="5" xl="4" class="logistic__input-group">
            <div class="elementsInput">
              <UiControl name="loading_capacity_water" :value="form.elevator_info.loading_capacity_water">
                <InputComponent
                  v-model="form.elevator_info.loading_capacity_water"
                  :disabled="isShowcase || !(form.elevator_info.water_out || form.elevator_info.water_in)"
                  :mask="form.elevator_info.loading_capacity_water ? mask : null"
                  label="Мощность погрузки водным транспортом (тонн в сутки)"
                  name="elevator_info.loading_capacity_water"
                />
              </UiControl>
              <CardOldValue
                v-if="isEditCode('loading_capacity_water', 'NONE')"
                :data="elevatorInfoChanges"
                prop="loading_capacity_water"
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
  name: 'BoatLogisticForm',
  components: { CardOldValue, InputComponent },
})
export default class extends Mixins(FormMixin) {
  mask = numberMask;

  get rules() {
    const result: any = {
      loading_capacity_water: (this.form.elevator_info.water_in || this.form.elevator_info.water_out) && 'required',
    };

    return result;
  }

  mounted() {
    const values = [
      this.form.elevator_info.water_in,
      this.form.elevator_info.water_out,
      this.form.elevator_info.loading_capacity_water,
    ].filter((v) => v);

    if (values.length) {
      this.$emit('open');
    }
  }

  changeCheckbox() {
    if (!this.form.elevator_info.water_in && !this.form.elevator_info.water_out) {
      this.form.elevator_info.loading_capacity_water = null;
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

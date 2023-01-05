<template>
  <v-expansion-panel class="elementsForm">
    <v-expansion-panel-header>Ж\Д транспорт</v-expansion-panel-header>
    <v-expansion-panel-content eager>
      <UiForm :rules="rules" @validation="(v) => $emit('validate', v)">
        <v-row class="end">
          <v-col cols="12">
            <div :class="getClass('railway_in')">
              <UiCheckbox
                id="railway_in"
                v-model="form.elevator_info.railway_in"
                class="checkbox-block"
                name="railway_in"
                :disabled="isShowcase"
                label="Приёмка ж/д транспортом"
                @input="changeCheckbox()"
              />
            </div>
            <div :class="getClass('railway_out')">
              <UiCheckbox
                id="railway_out"
                v-model="form.elevator_info.railway_out"
                class="checkbox-block"
                name="railway_out"
                :disabled="isShowcase"
                label="Отгрузка ж/д транспортом"
                @input="changeCheckbox()"
              />
            </div>
          </v-col>
        </v-row>
        <v-row class="end facility-block">
          <v-col cols="12" lg="5" xl="4" class="logistic__input-group">
            <div class="elementsInput">
              <UiControl name="loading_capacity_train_tons" :value="form.elevator_info.loading_capacity_train_tons">
                <InputComponent
                  v-model="form.elevator_info.loading_capacity_train_tons"
                  label="Мощность погрузки ж/д транспортом (тонн в сутки)"
                  :disabled="isShowcase || !(form.elevator_info.railway_out || form.elevator_info.railway_in)"
                  :mask="form.elevator_info.loading_capacity_train_tons ? mask : null"
                  name="elevator_info.loading_capacity_train_tons"
                />
              </UiControl>
              <CardOldValue
                v-if="isEditCode('loading_capacity_train_tons', 'NONE')"
                :data="elevatorInfoChanges"
                prop="loading_capacity_train_tons"
              />
            </div>
          </v-col>
          <v-col cols="12" lg="5" xl="4" class="logistic__input-group">
            <div class="elementsInput">
              <UiControl name="loading_capacity_wagons" :value="form.elevator_info.loading_capacity_wagons">
                <InputComponent
                  v-model="form.elevator_info.loading_capacity_wagons"
                  label="Мощность погрузки ж/д транспортом (вагонов в сутки)"
                  :disabled="isShowcase || !(form.elevator_info.railway_out || form.elevator_info.railway_in)"
                  :mask="form.elevator_info.loading_capacity_wagons ? mask : null"
                  name="elevator_info.loading_capacity_wagons"
                />
              </UiControl>
              <CardOldValue
                v-if="isEditCode('loading_capacity_wagons', 'NONE')"
                :data="elevatorInfoChanges"
                prop="loading_capacity_wagons"
              />
            </div>
          </v-col>
        </v-row>

        <v-row>
          <v-col cols="12" lg="5" xl="4" class="logistic__input-group">
            <div class="elementsInput">
              <UiControl name="railway_length" :value="form.elevator_info.railway_length">
                <InputComponent
                  v-model="form.elevator_info.railway_length"
                  label="Протяженность ж/д путей, м (собственных)"
                  :disabled="isShowcase || !(form.elevator_info.railway_out || form.elevator_info.railway_in)"
                  :mask="form.elevator_info.railway_length ? mask : null"
                  name="elevator_info.railway_length"
                />
              </UiControl>
              <CardOldValue
                v-if="isEditCode('railway_length', 'NONE')"
                :data="elevatorInfoChanges"
                prop="railway_length"
              />
            </div>
          </v-col>
          <v-col cols="12" lg="5" xl="4" class="logistic__input-group">
            <div class="elementsInput">
              <UiControl name="railway_capacity_wagons" :value="form.elevator_info.railway_capacity_wagons">
                <InputComponent
                  v-model="form.elevator_info.railway_capacity_wagons"
                  label="Вместимость ж/д путей в вагонах (собственных)"
                  :disabled="isShowcase || !(form.elevator_info.railway_out || form.elevator_info.railway_in)"
                  :mask="form.elevator_info.railway_capacity_wagons ? mask : null"
                  name="elevator_info.railway_capacity_wagons"
                />
              </UiControl>
              <CardOldValue
                v-if="isEditCode('railway_capacity_wagons', 'NONE')"
                :data="elevatorInfoChanges"
                prop="railway_capacity_wagons"
              />
            </div>
          </v-col>
          <v-col cols="12" :class="getClass('has_locomotive')">
            <UiCheckbox
              id="has_locomotive"
              v-model="form.elevator_info.has_locomotive"
              class="checkbox-block"
              name="has_locomotive"
              :disabled="isShowcase || !(form.elevator_info.railway_out || form.elevator_info.railway_in)"
              label="Наличие собственного маневрового локомотива"
            />
          </v-col>
        </v-row>

        <v-row>
          <v-col cols="12" lg="5" xl="4" class="logistic__input-group">
            <div class="elementsInput">
              <UiControl name="railway_length_rent" :value="form.elevator_info.railway_length_rent">
                <InputComponent
                  v-model="form.elevator_info.railway_length_rent"
                  label="Протяженность ж/д путей, м (аренда)"
                  :disabled="isShowcase || !(form.elevator_info.railway_out || form.elevator_info.railway_in)"
                  :mask="form.elevator_info.railway_length_rent ? mask : null"
                  name="elevator_info.railway_length_rent"
                />
              </UiControl>
              <CardOldValue
                v-if="isEditCode('railway_length_rent', 'NONE')"
                :data="elevatorInfoChanges"
                prop="railway_length_rent"
              />
            </div>
          </v-col>
          <v-col cols="12" lg="5" xl="4" class="logistic__input-group">
            <div class="elementsInput">
              <UiControl name="railway_capacity_wagons_rent" :value="form.elevator_info.railway_capacity_wagons_rent">
                <InputComponent
                  v-model="form.elevator_info.railway_capacity_wagons_rent"
                  label="Вместимость ж/д путей в вагонах (аренда)"
                  :disabled="isShowcase || !(form.elevator_info.railway_out || form.elevator_info.railway_in)"
                  :mask="form.elevator_info.railway_capacity_wagons_rent ? mask : null"
                  name="elevator_info.railway_capacity_wagons_rent"
                />
              </UiControl>
              <CardOldValue
                v-if="isEditCode('railway_capacity_wagons_rent', 'NONE')"
                :data="elevatorInfoChanges"
                prop="railway_capacity_wagons_rent"
              />
            </div>
          </v-col>
          <v-col cols="12" :class="getClass('has_locomotive_rent')">
            <UiCheckbox
              id="has_locomotive_rent"
              v-model="form.elevator_info.has_locomotive_rent"
              class="checkbox-block"
              name="has_locomotive_rent"
              :disabled="isShowcase || !(form.elevator_info.railway_out || form.elevator_info.railway_in)"
              label="Наличие маневрового локомотива (аренда)"
            />
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" lg="5" xl="4" class="logistic__input-group">
            <div class="elementsInput">
              <span v-if="isShowcase">
                <InputComponent
                  :value="form.elevator_info.station && form.elevator_info.station.name"
                  v-bind="getProps('station')"
                  disabled
                  label="Ближайшая ж/д станция"
                  name="elevator_info.station.input"
                />
              </span>
              <span v-else>
                <railway-station-dictionary
                  v-model="form.elevator_info.station"
                  label="Ближайшая ж/д станция"
                  placeholder="Укажите ж/д станцию"
                  :is-disabled="!(form.elevator_info.railway_out || form.elevator_info.railway_in)"
                  name="elevator_info.station"
                  clereables
                />
              </span>
              <CardOldValue v-if="isEditCode('station', 'NONE')" :data="elevatorInfoChanges" prop="station" />
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
import RailwayStationDictionary from '@/components/common/Dictionary/RailwayStation/RailwayStation.vue';
import CardOldValue from '@/views/RegisterOrganizations/components/CardOrganization/components/CardOldValue.vue';
import FormMixin from '@/views/RegisterOrganizations/components/CardOrganization/components/LocationInfo/FormMixin.vue';

@Component({
  name: 'TrainLogisticForm',
  components: { CardOldValue, InputComponent, RailwayStationDictionary },
})
export default class extends Mixins(FormMixin) {
  mask = numberMask;
  isValid = true;

  get rules() {
    const result: any = {
      railway_capacity_wagons: this.form.elevator_info.railway_length && 'required',
      railway_capacity_wagons_rent: this.form.elevator_info.railway_length_rent && 'required',
    };

    if (this.form.elevator_info.railway_in || this.form.elevator_info.railway_out) {
      result.loading_capacity_train_tons = 'required';
      result.loading_capacity_wagons = 'required';
    }

    return result;
  }

  mounted() {
    const values = [
      this.form.elevator_info.railway_in,
      this.form.elevator_info.railway_out,
      this.form.elevator_info.loading_capacity_train_tons,
      this.form.elevator_info.loading_capacity_wagons,
      this.form.elevator_info.railway_length,
      this.form.elevator_info.railway_length_rent,
      this.form.elevator_info.railway_capacity_wagons,
      this.form.elevator_info.railway_capacity_wagons_rent,
      this.form.elevator_info.has_locomotive,
      this.form.elevator_info.has_locomotive_rent,
      this.form.elevator_info.station,
    ].filter((v) => v);

    if (values.length) {
      this.$emit('open');
    }
  }

  changeCheckbox() {
    if (!this.form.elevator_info.railway_in && !this.form.elevator_info.railway_out) {
      this.form.elevator_info.loading_capacity_train_tons = null;
      this.form.elevator_info.loading_capacity_wagons = null;
      this.form.elevator_info.railway_length = null;
      this.form.elevator_info.railway_length_rent = null;
      this.form.elevator_info.railway_capacity_wagons = null;
      this.form.elevator_info.railway_capacity_wagons_rent = null;
      this.form.elevator_info.has_locomotive = false;
      this.form.elevator_info.has_locomotive_rent = false;
      this.form.elevator_info.station = null;
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

.facility-block {
  margin-bottom: 20px;
}

.logistic__input-group {
  display: flex;
  align-items: flex-end;
  width: 100%;
}
</style>

<template>
  <div>
    <div class="location-information">
      <CultureTypeTable v-model="form.elevator_info.elevator_info_product" :is-showcase="isShowcase" />
      <StorageBuildingsForm
        :form="form"
        :total-capacity="maxCapacity"
        :changes-data="changesData"
        :is-showcase="isShowcase"
      />
      <ConservationDataForm :form="form" :changes-data="changesData" :is-showcase="isShowcase" />
      <ServicesDataForm :form="form" :changes-data="changesData" :is-showcase="isShowcase" />

      <v-row>
        <v-col cols="12">
          <div class="title-h2">Приемка и отгрузка</div>
        </v-col>
      </v-row>

      <v-row>
        <v-col xs="12" lg="10" xl="8">
          <v-expansion-panels v-model="innerPanel" multiple>
            <CarLogisticForm
              :form="form"
              :changes-data="changesData"
              :is-showcase="isShowcase"
              @open="() => panelPush(0)"
              @validate="(v) => (isValid.car = v.isValid)"
            />
            <BoatLogisticForm
              :form="form"
              :changes-data="changesData"
              :is-showcase="isShowcase"
              @open="() => panelPush(1)"
              @validate="(v) => (isValid.boat = v.isValid)"
            />
            <TrainLogisticForm
              :form="form"
              :changes-data="changesData"
              :is-showcase="isShowcase"
              @open="() => panelPush(2)"
              @validate="(v) => (isValid.train = v.isValid)"
            />
          </v-expansion-panels>
        </v-col>
      </v-row>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import CultureTypeTable from '@/views/RegisterOrganizations/components/CardOrganization/components/LocationInfo/CultureTypeTable.vue';
import StorageBuildingsForm from '@/views/RegisterOrganizations/components/CardOrganization/components/LocationInfo/StorageBuildingsForm.vue';
import ConservationDataForm from '@/views/RegisterOrganizations/components/CardOrganization/components/LocationInfo/ConservationDataForm.vue';
import ServicesDataForm from '@/views/RegisterOrganizations/components/CardOrganization/components/LocationInfo/ServicesDataForm.vue';
import CarLogisticForm from '@/views/RegisterOrganizations/components/CardOrganization/components/LocationInfo/CarLogisticForm.vue';
import BoatLogisticForm from '@/views/RegisterOrganizations/components/CardOrganization/components/LocationInfo/BoatLogisticForm.vue';
import TrainLogisticForm from '@/views/RegisterOrganizations/components/CardOrganization/components/LocationInfo/TrainLogisticForm.vue';

type Props = {
  form: any;
  isShowcase: boolean;
  changesData: any;
};

@Component({
  name: 'card-location-information',
  components: {
    CultureTypeTable,
    StorageBuildingsForm,
    ConservationDataForm,
    ServicesDataForm,
    CarLogisticForm,
    BoatLogisticForm,
    TrainLogisticForm,
  },
})
export default class CardLocationInformation extends Vue {
  @Prop({ type: Object, default: () => ({}) }) readonly form!: Props['form'];
  @Prop({ type: Object, default: () => ({}) }) readonly changesData!: Props['changesData'];
  @Prop({ type: Boolean, default: () => ({}) }) readonly isShowcase!: Props['isShowcase'];
  @Prop({ type: Number, default: 0 }) readonly maxCapacity!: number;

  panel: number[] = [];
  isValid = {
    car: true,
    boat: true,
    train: true,
  };

  get innerPanel() {
    const result = [...(this.panel || [])];

    Object.values(this.isValid).forEach((v, index) => {
      if (!v && !result.includes(index)) {
        result.push(index);
      }
    });

    return result;
  }

  set innerPanel(v: number[]) {
    this.panel = v || [];
  }

  panelPush(index: number) {
    if (!this.panel.includes(index)) {
      this.panel.push(index);
    }
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.containerTabs {
  margin-top: 30px;
}

.tabs {
  border-bottom: 1px solid $light-grey-color;
  width: 100%;
  display: flex;
  flex-direction: row;
  text-transform: uppercase;
}

.tab {
  display: flex;
  font-weight: bold;
  font-size: 13px;
  color: $footer-color;
  cursor: pointer;
  padding-bottom: 8px;
  margin-right: 18px;

  &.active {
    color: $gold-light-color;
    border-bottom: 1px solid $gold-light-color;
  }
}

.input {
  &--disabled {
    background-color: $input-disable-background;
    color: $input-disabled-color;
  }

  &--small {
    flex: 1 1 150px;
    margin-right: 15px;
    max-width: 150px;
  }

  &--large {
    flex: 1 1 100%;
  }
}

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

.end {
  display: flex;
  align-items: flex-end;

  .checkbox-block {
    height: 40px;
    margin-bottom: 0;
  }
}
</style>

<template>
  <div class="type">
    <v-row>
      <v-col cols="12">
        <div class="title">Выбор вида организации</div>
      </v-col>
    </v-row>

    <div class="list">
      <v-row>
        <v-col cols="12">
          <v-radio-group v-model="form.subjectType">
            <v-row>
              <v-col cols="12">
                <v-radio label="Российское юридическое лицо" value="UL" :disabled="isActive" />
              </v-col>
              <v-col cols="12">
                <v-radio label="Юридическое лицо, являющееся иностранным лицом" value="IR" :disabled="isActive" />
              </v-col>
              <v-col cols="12">
                <v-radio label="Индивидуальный предприниматель" value="IP" :disabled="isActive" />
              </v-col>
              <v-col cols="12">
                <v-radio
                  label="Аккредитованный филиал представительства иностранного юр. лица"
                  value="IF"
                  :disabled="isActive"
                />
              </v-col>
            </v-row>
          </v-radio-group>
        </v-col>
      </v-row>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Mixins } from 'vue-property-decorator';
import RadioGroupComponent from '@/components/common/inputs/RadioGroupComponent.vue';
import Form from '@/utils/global/mixins/form';

@Component({
  name: 'organization-type',
  components: {
    RadioGroupComponent,
  },
})
export default class OrganizationType extends Mixins(Form) {
  @Prop({ type: Boolean, default: false }) readonly isActive;
}
</script>

<style lang="scss" scoped>
.type {
  .title {
    font-weight: 500 !important;
    font-size: 16px !important;
    line-height: 24px !important;
  }
}
</style>

<template>
  <Dialog-component
    v-model="innerValue"
    :prompt="false"
    cancel-title=""
    confirm-title=""
    width="800"
    with-close-icon
    controls-justify="justify-end"
  >
    <template #title>
      <span>Фильтр</span>
    </template>

    <template #content>
      <div>
        <v-row>
          <v-col cols="8">
            <select-component v-model="form" return-object label="Статус рассмотрения" :items="list" />
          </v-col>
        </v-row>

        <v-row justify="end">
          <v-col cols="12" class="col-exclude">
            <DefaultButton title="Сбросить" @click="resetSettings" />
            <DefaultButton variant="primary" title="Найти" @click="applyFilter" />
          </v-col>
        </v-row>
      </div>
    </template>
  </Dialog-component>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';

export type TFilter = {
  code?: string;
};

@Component({
  name: 'application-approval-filter',
  components: {
    DefaultButton,
    DialogComponent,
    SelectComponent,
  },
})
export default class ViewSettingsModal extends Vue {
  /** Уникальный идентификатор реестра. */
  @Prop({ type: String }) readonly id;
  /** Флаг открытия модального окна. */
  @Prop({ type: Boolean }) readonly value;
  /** Флаг открытия модального окна. */
  @Prop({ type: Object }) readonly selectStatus;

  list = [];
  form: TFilter = {};

  get innerValue() {
    return this.value;
  }

  set innerValue(value) {
    this.$emit('input', value);
  }

  get baseValue() {
    return this.selectStatus ? this.selectStatus : this.form;
  }

  set baseValue(value) {
    this.$emit('input', value);
  }

  async mounted() {
    const data = await this.$store.dispatch('approvalTask/getListStatuses');
    this.list = data
      .map((item) => ({
        text: item.name,
        value: item.id,
      }))
      .filter((item: any) => item.value !== 1)
      .filter((item) => item.value !== 4);

    this.form = { ...this.selectStatus };
  }

  resetSettings() {
    this.form = {};

    this.$emit('apply-filters', this.form);
  }

  applyFilter() {
    this.$emit('apply-filters', this.form);
  }
}
</script>

<style lang="scss" scoped>
.select-row {
  align-items: flex-end;
}
</style>

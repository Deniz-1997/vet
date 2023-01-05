<template>
  <div>
    <Dialog-component
      v-model="isShow"
      :prompt="false"
      cancel-title=""
      confirm-title=""
      width="420"
      with-close-icon
      title-align="justify-end"
      controls-justify="justify-end"
      add-class="dialog_filter"
    >
      <template #content>
        <UiForm
          id="filters"
          @submit="applyFilter"
        >
          <div v-for="(filter, index) in value" :key="index">
            <v-row
              v-if="filter.columns && filter.columns.length"
                :class="`row_columns ${filter.range && 'range'}`"
            >
              <v-row class="row_label" v-if="filter.title">
                <LabelComponent :label="filter.title" />
              </v-row>
              <v-row class="row_label">
                <LabelComponent v-if="filter.label" :label="filter.label" />
              </v-row>
              <v-col
                v-for="(column, i) in filter.columns"
                :cols="12/filter.columns.length"
                :key="i"
              >
                <span v-if="column.type === 'text'">
                  <InputComponent
                    v-model="form[column.value]"
                    :id="column.value"
                    :label="column.text"
                    :placeholder="column.placeholder"
                  />
                </span>
                <span v-else-if="column.type === 'datepicker'">
                  <UiDateInput
                    v-model="form[column.value]"
                    :name="column.value"
                    :label="column.text"
                    :limit-to="(column.limitTo && form[column.limitTo]) ? toDate(form[column.limitTo]) : ''"
                    :limit-from="(column.limitFrom && form[column.limitFrom]) ? fromDate(form[column.limitFrom]) : ''"
                    :placeholder="column.placeholder"
                    class="datePicker"
                    isAppendIcon
                  />
                </span>
              </v-col>
            </v-row>

            <v-row v-else>
              <v-col v-if="filter.type === 'text'">
                <InputComponent
                  v-model="form[filter.value]"
                  :id="filter.value"
                  :label="filter.text"
                  :placeholder="filter.placeholder"
                />
              </v-col>
              <v-col v-else-if="filter.type === 'select'">
                <select-component
                  v-model="form[filter.value]"
                  :label="filter.text"
                  :items="filter.list"
                  :placeholder="filter.placeholder"
                />
              </v-col>
              <v-col v-else-if="filter.type === 'autocomplete'">
                <label-component :label="filter.text" />
                <autocomplete-component
                  v-model="form[filter.value]"
                  :items="filter.list"
                  :placeholder="filter.placeholder"
                  item-value="name"
                  item-text="name"
                  return-object
                  @searchInputUpdate="value => searchAutocomplete(value, filter.list, filter.searchCallback)"
                />
              </v-col>
              <v-col v-else-if="filter.type === 'checkbox'">
                <UiCheckbox
                  v-model="form[filter.value]"
                  :id="filter.value"
                  :name="filter.value"
                  :label="filter.text"
                />
              </v-col>
              <v-col v-else-if="filter.type === 'radio'">
                <LabelComponent :label="filter.text" />
                <RadioGroupComponent
                  v-model="form[filter.value]"
                  labelYes="Да"
                  labelNo="Нет"
                  :label="filter.text"
                />
              </v-col>
              <v-col v-else-if="filter.type === 'datepicker'">
                <UiDateInput
                  v-model="form[filter.value]"
                  :name="filter.value"
                  :label="filter.text"
                  :placeholder="filter.placeholder"
                  class="datePicker"
                />
              </v-col>
            </v-row>
          </div>

          <v-row justify="end">
            <v-col cols="12" class="col-exclude">
              <DefaultButton title="Сбросить" @click="resetSettings" />
              <DefaultButton type="submit" variant="primary" title="Найти" />
            </v-col>
          </v-row>
        </UiForm>
      </template>
    </Dialog-component>

    <DefaultButton variant="primary" title="Фильтры" @click="isShow = true" />
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import RadioGroupComponent from '@/components/common/inputs/RadioGroupComponent.vue';
import LabelComponent from '@/components/common/Label/Label.vue';

import cloneDeep from 'lodash/cloneDeep';
import { dateFrom } from '@/utils/date';

@Component({
  name: 'filters',
  components: {
    LabelComponent,
    DefaultButton,
    DialogComponent,
    InputComponent,
    SelectComponent,
    RadioGroupComponent,
    AutocompleteComponent,
  },
})
export default class Filters extends Vue {
  /** Уникальный идентификатор реестра. */
  @Prop({ type: String }) readonly id;

  @Prop({ type: Array, required: true }) readonly value;

  isShow = false;

  list = [];
  form = {};
  carrentDay = new Date();

  get innerValue() {
    return this.value;
  }

  set innerValue(value) {
    this.$emit('input', value);
  }

  toDate(date) {
    return dateFrom(date, 1);
  }

  fromDate(date) {
    return dateFrom(date, -1);
  }

  resetSettings() {
    this.form = {};
  }

  applyFilter() {
    let form = cloneDeep(this.form);

    this.value.forEach(item => {
      if (form[item.value]) {
        form[item.value] = form[item.value]?.id ? form[item.value]?.id : form[item.value];
      }
    });

    this.isShow = false;
    this.$emit('apply-filters', form);
  }

  async searchAutocomplete(value, list, searchCallback) {
    this.$emit('search-autocomplete', value);

    if (value === null) {
      return;
    }
    const itemIndex = list.findIndex((item: any) => item.name === value);
    if (itemIndex === -1) {
      searchCallback();
    }
  }
}
</script>

<style lang="scss" scoped>
.dialog_filter {
  .row,
  .col {
    margin: 0;
    padding-left: 0;
    padding-right: 0;
    padding-bottom: 0;
  }

  .row_columns {
    .col {
      padding-right: 12px;
    }

    & > .col:last-child {
      padding-right: 0;
    }
  }

  .row_label {
    margin-top: 12px;

    & > div.mb-2 {
      margin-bottom: 0 !important;
    }
  }

  .range {
    & > .col {
      position: relative;

      &::after {
        content: "-";
        display: inline-block;
        position: absolute;
        top: 46%;
        right: 4px;
      }
    }
  }
}

.select-row {
  align-items: flex-end;
}
</style>

<style lang="scss">
.dialog_filter {
  .v-card__title {
    position: relative;
    padding-bottom: 0;
    margin-bottom: -30px;
    z-index: 10;
  }
}
</style>
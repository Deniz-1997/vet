<template>
  <div class="filter">
    <v-row>
      <v-col cols="6">
        <quallity-dictionary
          v-model="form.quality_indicators_id"
          :items-list="itemsQuallityDictionary"
          item-value="id"
          item-text="name"
          label="Наименование потребительского свойства"
          clereables
        />
      </v-col>
      <v-col cols="6">
        <autocomplete-tooltip-component
          v-model="form.purpose"
          :items="indicatorPurposeList"
          :item-disabled="indicatorPurposeList"
          item-value="id"
          item-text="name"
          placeholder="Выберите назначение потребительского свойства"
          label="Назначение потребительского свойства"
          clereables
        />
      </v-col>
      <v-col cols="6">
        <okpd-autocomplete
          v-model="form.okpd2"
          :items-list="itemsOkpdAutocomplete"
          return-object
          item-value="id"
          item-text="name"
          label="Вид с/х культуры"
          small-chips
          clereables
        />
      </v-col>
      <v-col cols="6">
        <country-dictionary
          v-model="form.country"
          :items-list="itemsCountryDictionary"
          label="Страна"
          clereables
          default-value="Российская Федерация"
          @setDefault="submit"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="6"> </v-col>
      <v-col cols="3">
        <DefaultButton class="button" title="Сбросить" @click="reset" />
      </v-col>
      <v-col cols="3">
        <DefaultButton class="button" variant="primary" title="Применить" @click="submit" />
      </v-col>
    </v-row>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import AutocompleteTooltipComponent from '@/components/common/inputs/AutocompleteTooltipComponent.vue';
import CountryDictionary from '@/components/common/Dictionary/Country/Country.vue';
import QuallityDictionary from '@/components/common/Dictionary/QuallityIndicators/QuallityIndicators.vue';
import OkpdAutocomplete from '@/components/common/Dictionary/OkpdAutocomplete/OkpdAutocomplete.vue';
import { TAdditionalDataItem } from '../NsiCard/NsiCard.types';
@Component({
  name: 'nsi-filter',
  components: {
    DefaultButton,
    AutocompleteTooltipComponent,
    CountryDictionary,
    QuallityDictionary,
    OkpdAutocomplete,
  },
})
export default class NsiFilter extends Vue {
  form: any = {};
  isLoading = false;
  indicatorPurposeList: TAdditionalDataItem[] = [];
  itemsQuallityDictionary: any[] = [];
  itemsOkpdAutocomplete: any[] = [];
  itemsCountryDictionary: any[] = [];

  async created() {
    const { data } = await this.$axios.post('/api/nci/indicatorPurpose', { actual: true });
    this.indicatorPurposeList = data.content.filter((item) => {
      return !this.form.purpose?.some((item2) => item2.id === item.id);
    });
    this.indicatorPurposeList.push(...(this.form.purpose || []));
  }

  submit() {
    const data = {
      okpd2: this.form?.okpd2,
      country: this.form?.country,
      quality_indicators_id: this.form?.quality_indicators_id,
      purpose: this.form?.purpose,
    };
    this.$emit('filter', data);
  }

  reset() {
    this.form = {};
    this.itemsQuallityDictionary = [];
    this.itemsOkpdAutocomplete = [];
    this.itemsCountryDictionary = [];
    this.$emit('filter', {});
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';
.button {
  width: 100%;
}
</style>

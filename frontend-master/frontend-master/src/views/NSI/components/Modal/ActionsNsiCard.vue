<template>
  <div class="nsi-form">
    <v-row>
      <v-col v-if="fields.includes('grainGroupName')" cols="12">
        <autocomplete-component
          v-model="form.okpd2"
          return-object
          :items="grainData"
          label="Наименование зерна / продуктов переработки зерна"
          item-value="name"
          item-text="name"
          @searchInputUpdate="searchGrain"
        />
      </v-col>

      <v-col v-if="fields.includes('purpose')" cols="12">
        <autocomplete-component
          v-model="form.purpose"
          return-object
          :items="additionalDataSecond"
          item-value="id"
          item-text="name"
          label="Назначение потребительского свойства"
          chips
          is-multiple
          deletable-chips
          :item-disabled="additionalDataSecond"
          small-chips
        />
      </v-col>

      <v-col v-if="fields.includes('grainGroupName')" cols="12">
        <Input-component
          key="min_value"
          v-model="form.min_value"
          placeholder="Введите значение"
          label="Диапазон допустимых значений с"
          mask="#########################################################"
        />
      </v-col>
      <!-- ToTo: Исправить на нормальную маску! -->
      <v-col v-if="fields.includes('grainGroupName')" cols="12">
        <Input-component
          key="max_value"
          v-model="form.max_value"
          placeholder="Введите значение"
          label="Диапазон допустимых значений по"
          mask="#########################################################"
        />
      </v-col>

      <v-col v-if="fields.includes('okpd2')" cols="12">
        <autocomplete-component
          v-model="form.okpd2"
          return-object
          :items="okpdData"
          label="Код ОКПД 2"
          item-value="id"
          item-text="name"
          @searchInputUpdate="searchOkpd2"
        />
      </v-col>

      <v-col v-if="fields.includes('typeProductCode')" cols="12">
        <autocomplete-component
          v-model="form.tnved"
          return-object
          :items="tnvedData"
          item-value="code"
          label="Код ТН ВЭД"
          item-text="full_name"
          is-multiple
          chips
          @searchInputUpdate="searchTnved"
        />
      </v-col>
    </v-row>

    <v-row justify="end">
      <v-col cols="12" class="col-exclude">
        <DefaultButton title="Отменить" @click="$emit('close')" />
        <DefaultButton variant="primary" title="Сохранить" @click="addTable" />
      </v-col>
    </v-row>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import Datepicker from '@/components/common/Datepicker/Datepicker.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import nsiList from '@/views/NSI/config';
import { TAdditionalDataItem } from '../NsiCard/NsiCard.types';

@Component({
  name: 'modal-action-nsi-card',
  components: {
    Datepicker,
    DefaultButton,
    InputComponent,
    SelectComponent,
    LabelComponent,
    AutocompleteComponent
  },
})
export default class ActionsNsiCard extends Vue {
  @Prop(Array) readonly fields!: any;
  @Prop(String) readonly okpdApiUrl!: string;
  @Prop(String) readonly tnvedApiUrl!: string;
  @Prop(String) readonly grainUrl!: string;
  @Prop(String) readonly qualityIndicatorsApiUrl!: string;

  form: any = {
    tnved: [],
  };
  okpdData = [];
  tnvedData = [];
  grainData = [];
  startDate = new Date();
  additionalDataSecond: TAdditionalDataItem[] = [];
  additionalApiUrlSecond = '';
  activeNsi = '';
  mounted() {
    this.activeNsi = this.$route.params.mask;
    this.additionalApiUrlSecond = nsiList[this.activeNsi].additionalApiUrlSecond;
    this.searchGrain();
    if (this.additionalApiUrlSecond) {
      this.getNsiList();
    }
  }

  async searchOkpd2(value) {
    if (value === null) {
      return;
    }
    const itemIndex = this.okpdData.findIndex((item: any) => item.full_name === value);
    if (itemIndex === -1) {
      const data = await this.$store.dispatch('nsi/search', {
        url: this.okpdApiUrl,
        data: { filter: value, actual: true },
      });
      this.okpdData = data.content;
    }
  }

  async searchGrain(value?) {
    if (value === null) {
      return;
    }
    const itemIndex = this.grainData.findIndex((item: any) => item.full_name === value);
    if (itemIndex === -1) {
      let { content } = await this.$store.dispatch('nsi/getList', {
        url: '/api/nci/okpd2',
        data: { filter: value, actual: true },
      });
      this.grainData = content;
    }
  }

  async searchTnved(value) {
    if (value === null) {
      return;
    }
    const itemIndex = this.tnvedData.findIndex((item: any) => item.full_name === value);
    if (itemIndex === -1) {
      let filterData;
      if (this.form.okpd2) {
        filterData = {
          tnved: value,
          okpd2: this.form.okpd2.code,
        };
      } else {
        filterData = {
          tnved: value,
        };
      }
      let data = await this.$store.dispatch('nsi/search', {
        url: this.tnvedApiUrl,
        data: filterData,
      });
      this.tnvedData = data;
    }
  }

  async findOkpd() {
    const data = await this.$store.dispatch('nsi/search', {
      url: this.okpdApiUrl,
      data: { filter: this.form.tnved[0].okpd2 },
    });
    this.okpdData = data;
    this.form.okpd2 = this.okpdData[0];
    this.tnvedData = this.tnvedData.filter((item: any) => item.okpd2 === this.form.tnved[0].okpd2);
  }

  async getNsiList() {
    const { content: additionalList } = await this.$store.dispatch('nsi/getList', {
      url: this.additionalApiUrlSecond,
      params: { actual: true },
    });
    this.additionalDataSecond = additionalList.filter((item) => {
      return !this.form.purposes?.some((item2) => item2.id === item.id);
    });

    this.additionalDataSecond.push(...(this.form.purposes || []));
  }

  addTable(): void {
    if (this.fields.includes('grainGroupName')) {
      this.$emit('save', this.form);
    }

    if (this.fields.includes('okpd2')) {
      this.$emit('save', {
        code: this.form.okpd2.code,
        name: this.form.okpd2.name,
        tnved: this.form.tnved,
        purpose: this.form.purpose,
        min_value: this.form.min_value,
        max_value: this.form.max_value,
      });
    }

    this.$emit('close');
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.label {
  color: $input-border-color;
  margin-bottom: 5px;
  font-size: 13px;
  line-height: 16px;
}

.lineChoose {
  margin-top: 20px;
}

.spanChoose {
  text-decoration: underline;
  margin-right: 15px;
  font-size: 16px;
  color: $medium-grey-color;
  cursor: pointer;
}

.baseInformation {
  margin-top: 25px;

  @include respond-to('medium') {
    margin-top: 15px;
  }

  @include respond-to('small') {
    margin-top: 10px;
  }
}

.description {
  color: $black-color;
  padding: 20px 0;
  text-align: center;
  font-size: 16px;
  font-weight: 700;
}

.reason,
.period {
  margin-bottom: 18px;
}

.form-input {
  border: 1px solid $input-border-color;
  border-radius: 3px;
  outline: none;
  height: 40px;
  color: $black-color;
  font-size: 14px;
  line-height: 16px;
  margin: 0;
  padding: 0 10px;
  width: 100%;
}

.buttons {
  display: flex;
  justify-content: flex-end;
}

.checkbox {
  cursor: pointer;
  width: 16px;
  height: 16px;
  position: relative;

  [type='checkbox'] {
    position: absolute;
    opacity: 0;
  }

  &__icon {
    align-items: center;
    justify-content: center;
    background: $check-bg;
    display: flex;
    height: 16px;
    width: 16px;
    border: 1px solid $input-border-color;
    border-radius: 4px;

    img {
      width: 9px;
      display: block;
      opacity: 0;
    }
  }

  [type='checkbox']:checked {
    & + .checkbox__icon {
      background: $gold-light-color;
      border-color: $gold-light-color;

      img {
        opacity: 1;
      }
    }
  }
}

.col-exclude {
  display: flex;
  justify-content: flex-end;
}
</style>

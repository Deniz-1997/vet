<template>
  <div>
    <div class="settings">
      <span class="settingsSpans" @click="onOpenSettings = true">
        <img src="/icons/settings.svg" class="iconSettings" />
        &nbsp;Настроить вид
      </span>
      <span>
        <download-excel
          v-if="viewExcel"
          :meta="getMeta"
          :data="excelData"
          :fields="excelFields"
          :name="getExcelName"
          class="settingsSpan mb-5 mt-5"
        >
          <img class="iconSettings mr-2" src="/icons/export.svg" />
          Экспорт списка
        </download-excel>
      </span>
    </div>
    <view-settings-modal
      :id="id"
      v-model="onOpenSettings"
      :headers="headers"
      :sort-map="sortMap"
      :default-sorting="defaultSorting"
      :additional-system-headers="additionalSystemHeaders"
      :max-sort-length="maxSortLength"
      @close="closeSettingsModal"
      @apply-settings="onApplySettings"
    />
  </div>
</template>

<script lang="ts">
import { Component, Model, Prop, Vue } from 'vue-property-decorator';
import ViewSettingsModal, { TSort } from '@/components/common/ViewSettings/ViewSettingsModal.vue';
import mapValues from 'lodash/mapValues';
import moment from 'moment';
import { SdizGpbVueModel } from '@/models/Sdiz/Data/SdizGpb.vue';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import { PropType } from 'vue';
import { HeaderItem } from '@/views/Sdiz/components/Subcomponents/Table/SdiztListTables.vue';
import SORT_MAP from '@/utils/sortMap';

@Component({
  name: 'header-settings',
  components: { ViewSettingsModal },
})
export default class SettingTableHeader extends Vue {
  @Model('change') readonly applyHeaders!: unknown;
  @Prop({ type: Array as PropType<HeaderItem[]>, default: () => [] }) readonly headers!: HeaderItem[];
  @Prop({ type: Array, required: true }) valueRows!: Array<SdizGpbVueModel | SdizVueModel>;
  @Prop({ type: Boolean, default: true }) viewExcel!: Array<SdizGpbVueModel | SdizVueModel>;
  @Prop({ type: String, default: '' }) id!: string;
  @Prop({ type: Object, default: () => ({ property: 'id', direction: 'DESC' }) }) readonly defaultSorting!: TSort;
  @Prop({ type: Array, default: () => [] }) readonly additionalSystemHeaders!: string[];

  onOpenSettings = false;
  maxSortLength = 8;

  get sortMap() {
    return Object.keys(SORT_MAP).includes(this.id) ? SORT_MAP[this.id] : {};
  }

  get excelFields() {
    let fields = {};
    this.headers
      .filter((v) => v.value !== 'actions')
      .forEach((v) => (fields[`"${v.text}"`] = v.value.replace(/\./g, '__')));
    return fields;
  }

  get excelData() {
    let data: object[] = [];
    const fields = this.excelFields;
    this.valueRows.forEach((v) => {
      let obj = {};
      mapValues(fields, (value: string) => {
        let val = v;
        value.split('__').forEach((key) => (val = val[key]));
        obj[value] = val;
      });
      data.push(obj);
    });
    return data;
  }

  get getExcelName() {
    return `export_list_${moment().unix()}.xls`;
  }

  get getMeta() {
    return [
      [
        {
          key: 'charset',
          value: 'utf-8',
        },
      ],
    ];
  }

  closeSettingsModal() {
    this.onOpenSettings = !this.onOpenSettings;
  }

  onApplySettings(data) {
    this.$emit('change', data);
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';

.settings {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
}

.settingsSpans {
  background: none;
  border: none;
  display: flex;
  align-items: center;
  font-size: 14px;
  line-height: 16px;
  color: $medium-grey-color !important;
  cursor: pointer;
  text-align: left;
}
</style>

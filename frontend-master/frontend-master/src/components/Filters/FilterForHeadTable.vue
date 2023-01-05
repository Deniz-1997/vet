<template>
  <div>
    <div class="filter-button" @click="stateFilterModalHeaders = !stateFilterModalHeaders">
      <fa-icon class="icon" name="filter"></fa-icon>
    </div>
    <dialog-component :key="stateFilterModalHeaders"
                      v-model="stateFilterModalHeaders"
                      :persistent="false" cancel-title=""
                      confirm-title="Закрыть"
                      controlsJustify="justify-end"
                      width="700"
                      @change="onSuccessCloseModal">
      <template #title>Укажите поля который нужно скрыть в таблице</template>
      <template #content>
        <v-row align-content="center" class="d-flex flex-wrap pb-5 pt-5 mr-0 ml-0">
          <v-col v-for="(header, index) in headersList" v-show="isShowHeaderItem(header)" :key="index"
                 cols="6">
            <checkbox-component :hide-details="true"
                                :label="header.text" :value="excludeHeader.findIndex(v => v === header.value) !== -1"
                                class="checkbox-filter" @change="onChangeCheckbox($event, header)"/>
          </v-col>

          <v-col class="mt-5 text-center" cols="12">
            <text-component class="text-caption" variant="span">Настройки будут сохранены после закрытия модального окна
              и будут действовать до закрытия браузера
            </text-component>
          </v-col>
        </v-row>
      </template>
    </dialog-component>
  </div>
</template>

<script lang="ts">
import {Component, Model, Prop, Vue} from 'vue-property-decorator'
import DialogComponent from "@/components/common/Dialog/Dialog.vue";
import CheckboxComponent from "@/components/common/inputs/CheckboxComponent.vue";
import TextComponent from "@/components/common/Text/Text.vue";

export type HeaderSdizItem = {
  text: string,
  value: string,
  notExclude: boolean | undefined
};

@Component({
  name: 'filter-for-headers-table',
  components: {DialogComponent, CheckboxComponent, TextComponent}
})
export default class FilterForHeadTable extends Vue {
  @Model('change', {type: Array, required: true}) headers!: string[];

  @Prop({}) readonly headersList!: HeaderSdizItem[];

  @Prop({type: String, default: 'sdiz'}) readonly groupNotice!: string;

  stateFilterModalHeaders: boolean = false;

  excludeHeader: string[] = [];

  nameLocalStorage: string = this.getName;

  startCounter: number = 0;

  get getName(): string {
    return this.$route.name?.toString() || 'unknown';
  }

  onFilterHeaders() {
    return this.headersList.filter((item: HeaderSdizItem) => {
      return !this.excludeHeader.includes(item.value);
    });
  }

  isShowHeaderItem(header) {
    return header.value !== "actions" && typeof header.notExclude === "undefined";
  }

  mounted() {
    this.nameLocalStorage += '_excludeHeader';

    if (localStorage.getItem(this.nameLocalStorage)) {
      try {
        const exclude: string | null = localStorage.getItem(this.nameLocalStorage);

        if (exclude !== null) {
          this.excludeHeader = JSON.parse(exclude);

          this.startCounter = this.excludeHeader.length;
        }
      } catch (e) {
        localStorage.removeItem(this.nameLocalStorage);
      }


    }

    this.$emit('change', this.onFilterHeaders());
  }

  onChangeCheckbox(model: boolean, header: { value: string, text: string }) {
    const {value} = header;
    if (model) {
      this.excludeHeader.push(value);
    } else {
      this.excludeHeader.splice(
          this.excludeHeader.indexOf(value), 1)
    }

    this.$emit('change', this.onFilterHeaders());
  }

  onSuccessCloseModal(value: boolean): void {
    if (!value) {

      if (this.startCounter !== this.excludeHeader.length) {
        this.$notify({group: this.groupNotice, type: 'success', title: 'Настройки сохранены', text: ''});
      }

      localStorage.setItem(this.nameLocalStorage, JSON.stringify(this.excludeHeader));
    }

    this.$emit('change', this.onFilterHeaders());
  }
}
</script>


<style lang="scss">
@import "@/assets/styles/_variables.scss";
@import "@/assets/styles/_mixins.scss";
.filter-button {
  position: absolute;
  opacity: 0.8;
  top: 10px;
  right: 5px;
  z-index: 5;
  width: 30px;
  height: 30px;
  background-color: white;
  cursor: pointer;
  -webkit-box-shadow: 0 4px 5px 0 rgb(0 0 0 / 14%), 0 1px 10px 0 rgb(0 0 0 / 12%), 0 2px 4px -1px rgb(0 0 0 / 30%);
  box-shadow: 0 4px 5px 0 rgb(0 0 0 / 14%), 0 1px 10px 0 rgb(0 0 0 / 12%), 0 2px 4px -1px rgb(0 0 0 / 30%);
  transition: box-shadow 0.3s ease-in-out;
  border-radius: 5px;
  display: inline-block;
  font-size: 25px;
  line-height: 25px;
  text-align: center;
  vertical-align: bottom;

  &:hover {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    opacity: 1;
  }

  .icon {
    color: $gold-light-color;
    width: 15px;
    height: 15px;
  }
}

</style>

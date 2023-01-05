<template>
  <div>
    <v-menu
      ref="menu"
      v-model="isDialogOpened"
      :disabled="disabled"
      :close-on-content-click="false"
      :return-value.sync="outputDate"
      content-class="content"
      bottom
      offset-y
    >
      <template #activator="{ on, attrs }">
        <div v-bind="attrs" v-on="on">
          <input-component
            v-model="viewDate"
            :disabled="disabled"
            :label="label"
            :required="isRequired"
            :placeholder="placeholder"
            class="input"
            :class="{
              'edit-elem': edit,
              'delete-elem': del,
              'add-elem': add,
            }"
            color="#d19b3f"
            variant="micro"
            hide-details
            outlined
            readonly
            :mask="mask"
          >
            <template #append>
              <icon-component icon-color="transparent">
                <calendar-icon />
              </icon-component>
            </template>
          </input-component>
        </div>
      </template>
      <calendar
        v-model="outputDate"
        :format="outputFormat"
        :limit-from="limitFrom"
        :limit-to="limitTo"
        :localization="localization"
        :month-select="monthSelect"
        :year-select="yearSelect"
        width="100%"
        hide-header
        @change="handleChange"
      />
    </v-menu>
  </div>
</template>

<script lang="ts">
import moment from 'moment';
import { Component, Model, Prop, Vue } from 'vue-property-decorator';
import Calendar from '@/components/common/Datepicker/Calendar.vue';
import CalendarIcon from '@/components/common/IconComponent/icons/CalendarIcon.vue';
import IconComponent from '@/components/common/IconComponent/IconComponent.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import LabelComponent from '@/components/common/Label/Label.vue';

interface MenuRef extends Element {
  save: (value: string) => void;
}

/**
 * @param {string} outputFormat new Date().toISOString().substr(0, 10).
 */
@Component({
  name: 'datepicker',
  components: {
    Calendar,
    CalendarIcon,
    IconComponent,
    InputComponent,
    LabelComponent,
  },
})
export default class Datepicker extends Vue {
  @Model('change', { type: String }) readonly value!: string;

  @Prop({ type: String, default: '' }) readonly placeholder!: string;
  @Prop({ type: String, default: 'ru' }) readonly localization!: string;
  @Prop({ type: String, default: 'DD.MM.YYYY' }) readonly outputFormat!: string;
  @Prop({ type: String, default: 'DD.MM.YYYY' }) readonly viewFormat!: string;
  @Prop({ type: Boolean, default: true }) readonly yearSelect!: boolean;
  @Prop({ type: Boolean, default: true }) readonly monthSelect!: boolean;
  @Prop({ type: Boolean, default: true }) readonly clearable!: boolean;
  @Prop({ type: [String, Date], default: '' }) readonly limitFrom!: string | Date;
  @Prop({ type: [String, Date], default: '' }) readonly limitTo!: string | Date;
  @Prop({ type: Boolean, default: false }) readonly disabled!: boolean;
  @Prop(String) readonly label!: string;
  @Prop(Boolean) readonly isRequired!: boolean;
  @Prop({ type: Boolean, default: false }) edit!: boolean;
  @Prop({ type: Boolean, default: false }) del!: boolean;
  @Prop({ type: Boolean, default: false }) add!: boolean;
  @Prop({ type: String, default: '##.##.####' }) readonly mask!: string;

  isDialogOpened = false;
  isDatePicked = false;

  get outputDate(): string {
    if (this.isDatePicked) {
      return this.value;
    }
    return '';
  }

  set outputDate(outputDate: string) {
    this.isDatePicked = true;
    this.$emit('change', outputDate);
  }

  get viewDate(): string {
    const { outputFormat, value, viewFormat } = this;
    const outputDate = moment(value, outputFormat);

    return outputDate.isValid() ? outputDate.format(viewFormat) : '';
  }

  set viewDate(viewDate: string) {
    this.$emit('change', viewDate);
  }

  handleChange(outputDate: string): void {
    if (outputDate) {
      (this.$refs.menu as MenuRef).save(outputDate);
    }
  }
}
</script>

<style scoped lang="scss">
@import '@/assets/styles/_variables.scss';

.input::v-deep .v-input__slot {
  box-shadow: none !important;
  color: $black-color;
  font-size: 14px;
  line-height: 16px;
  padding: 0 14px !important;
}

.input.edit-elem::v-deep {
  .v-input__control .v-input__slot {
    background: rgba($gold-light-color, 0.3) !important;
    input {
      color: $medium-grey-color !important;
    }
  }
}

.input.delete-elem {
  .v-input__slot {
    background: rgba($del-color, 0.3) !important;
    color: $medium-grey-color !important;
  }
}

.input.add-elem {
  .v-input__slot {
    background: rgba($added-color, 0.3) !important;
    color: $medium-grey-color !important;
  }
}

// .input::v-deep .v-label {

//   &--active {
//     display: none;
//   }
// }

// .input::v-deep fieldset legend {
//   width: 0 !important;
// }

.v-input__append-inner .svg:first-child {
  margin-right: 5px !important;
}
</style>

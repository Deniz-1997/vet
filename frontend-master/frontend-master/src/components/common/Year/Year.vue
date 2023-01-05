<template>
  <div class="calendar">
    <section class="navigation-container">
      <div v-if="label">
        <label-component :label="label" />
      </div>
      <div class="selects">
        <autocomplete-component
          v-model="innerValue"
          :items="numberOfYears"
          class="select"
          hide-details
          :is-disabled="disabled"
          :name="name"
        />
      </div>
    </section>
  </div>
</template>

<script lang="ts">
import moment, { Moment } from 'moment';
import { Component, Model, Prop, Vue } from 'vue-property-decorator';
import LabelComponent from '@/components/common/Label/Label.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';

type DatepickerValue = string | number;

@Component({
  name: 'year',
  components: {
    AutocompleteComponent,
    LabelComponent,
  },
})
export default class Year extends Vue {
  @Model('change', { type: [String, Number] }) readonly value!: DatepickerValue;
  @Prop({ type: String, default: 'YYYY-MM-DD' }) readonly format!: string;
  @Prop({ type: String, default: '' }) readonly label!: string;
  @Prop({ type: [String, Date], default: '' }) readonly limitFrom!: string | Date;
  @Prop({ type: [String, Date], default: '' }) readonly limitTo!: string | Date;
  @Prop({ type: Number, default: 1940 }) readonly startingYear!: number;
  @Prop({ type: Boolean, default: false }) readonly disabled!: boolean;
  @Prop({ type: String, default: '' }) name!: string;
  formattedValue = this.value;
  // startingYear = 1940;
  startMonthValue = null;
  monthValue: number = this.value ? moment(this.value).month() : this.defaultDate.month;

  get innerValue() {
    return Number(this.value);
  }

  set innerValue(value) {
    this.$emit('change', value ? String(value) : '');
  }

  get date() {
    return moment(this.value || undefined, this.format);
  }

  get numberOfYears(): number[] {
    const startingYear = this.momentLimitFrom.year();
    const endingYear = this.momentLimitTo.year() + 1;
    const countYear = endingYear - startingYear;
    return [...new Array(countYear)]
      .fill(null)
      .map((_, index) => startingYear + index)
      .reverse();
  }

  get defaultDate() {
    const currentDate = moment();
    if (currentDate.isBetween(this.momentLimitFrom, this.momentLimitTo)) {
      return {
        year: currentDate.year(),
        month: currentDate.month(),
      };
    }

    return {
      year: this.momentLimitTo.year(),
      month: this.momentLimitTo.month(),
    };
  }

  get momentLimitFrom(): Moment {
    return this.limitFrom ? moment(this.limitFrom) : moment(String(this.startingYear), 'YYYY');
  }

  get momentLimitTo(): Moment {
    return this.limitTo ? moment(this.limitTo) : moment().add(2, 'y');
  }

  get year(): number {
    return this.date.year();
  }
}
</script>

<style scoped lang="scss">
@import '@/assets/styles/_variables';

.calendar {
  background: $white-color;
  position: relative;
}

/* common begin */
%text {
  font-size: 13px;
  font-weight: normal;
  line-height: 16px;
}

.disabled {
  color: $light-grey-color;
}

/* common end */

/* elements begin */
.selects {
  display: flex;
  width: 100%;
}

.select {
  font-size: 14px;
  margin: 0;
  min-width: 60px;
  padding: 0 !important;
  width: 100%;

  @extend %text;
}

.select-item {
  @extend %text;

  &::first-letter {
    text-transform: uppercase;
  }
}

.select::v-deep .v-select__selection {
  &::first-letter {
    text-transform: uppercase;
  }
}

/* elements end */
</style>

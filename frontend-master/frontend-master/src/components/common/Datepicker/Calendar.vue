<template>
  <div class="calendar" :style="{ width }">
    <section class="navigation-container">
      <button @click="prev()">
        <v-icon class="arrow"> mdi-chevron-left </v-icon>
      </button>
      <div class="selects">
        <autocomplete-component
          v-if="monthSelect"
          v-model="monthValue"
          :items="monthsList"
          :clearable="false"
          class="select"
          hide-details
          @change="monthSelectChange"
        />
        <autocomplete-component
          v-if="yearSelect"
          v-model="yearValue"
          :items="numberOfYears"
          :clearable="false"
          class="select"
          hide-details
          @change="yearSelectChange"
        />
      </div>
      <button @click="next()">
        <v-icon class="arrow"> mdi-chevron-right </v-icon>
      </button>
    </section>
    <section class="subnavigation-container">
      <div class="week-container">
        <div v-for="(weekday, index) in weekdays" :key="index" class="weekday">
          {{ weekday }}
        </div>
      </div>
    </section>
    <section class="body-container">
      <div class="days-container">
        <!-- needs back and forward ranges -->
        <div v-for="(previousDay, index) in previousDaysList" :key="`previousDay-${index}`" class="day disabled">
          {{ previousDay.date() }}
        </div>
        <div
          v-for="(currentDay, index) in currentDaysList"
          :key="`currentDay-${index}`"
          :class="[
            'day',
            {
              disabled: !isBeetweenLimits(currentDay),
              selected: isActive(currentDay),
              today: isToday(currentDay),
            },
          ]"
          @click="isBeetweenLimits(currentDay) && select(currentDay)"
        >
          {{ currentDay.date() }}
        </div>
        <div v-for="(nextDay, index) in nextDaysList" :key="`nextDay-${index}`" class="day disabled">
          {{ nextDay }}
        </div>
      </div>
    </section>
  </div>
</template>

<script lang="ts">
import moment, { Moment } from 'moment';
import { Component, Model, Prop, Vue } from 'vue-property-decorator';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';

type DatepickerValue = string;

interface Selection {
  text: string;
  value: number;
}

@Component({
  name: 'calendar',
  components: {
    'select-component': SelectComponent,
    AutocompleteComponent,
  },
})
export default class Calendar extends Vue {
  @Model('change', { type: String }) readonly value!: DatepickerValue;

  @Prop({ type: String, default: 'ru' }) readonly localization!: string;
  @Prop({ type: String, default: 'YYYY-MM-DD' }) readonly format!: string;
  @Prop({ type: Boolean, default: true }) readonly yearSelect!: boolean;
  @Prop({ type: Boolean, default: true }) readonly monthSelect!: boolean;
  @Prop({ type: String, default: '260px' }) readonly width!: string;
  @Prop({ type: [String, Date], default: '' }) readonly limitFrom!: string | Date;
  @Prop({ type: [String, Date], default: '' }) readonly limitTo!: string | Date;

  date: Moment = moment(this.value || moment(), this.format);

  formattedValue = this.value;

  startingYear = 1940;
  yearValue: number = this.value ? moment(this.value).year() : this.defaultDate.year;

  monthValue: number = this.value ? moment(this.value).month() : this.defaultDate.month;
  startMonthValue = null;

  get numberOfYears(): number[] {
    const startingYear = this.momentLimitFrom.year();
    const endingYear = this.momentLimitTo.year() + 1;
    const countYear = endingYear - startingYear;

    return [...new Array(countYear)]
      .fill(null)
      .map((_, index) => startingYear + index)
      .reverse();
  }

  get year(): number {
    return this.yearValue;
  }

  get weekdays(): string[] {
    return moment.weekdaysMin();
  }

  get monthsList(): Selection[] {
    return moment()
      .localeData()
      .months()
      .reduce<Selection[]>((months, monthName, monthIndex) => {
        if (this.momentLimitTo.year() === this.yearValue) {
          const lastMonth = this.momentLimitTo.month();

          if (monthIndex > lastMonth) {
            return months;
          }
        }

        if (this.momentLimitFrom.year() === this.yearValue) {
          const startMonth = this.momentLimitFrom.month();

          if (startMonth > monthIndex) {
            return months;
          }
        }

        return [...months, { text: monthName, value: monthIndex }];
      }, []);
  }

  get currentDaysList(): Moment[] {
    return new Array(this.currentDays).fill(null).map((_, index) =>
      this.$moment()
        .set('y', this.year)
        .set('M', this.date.month())
        .set('D', index + 1)
    );
  }

  get previousDaysList(): moment.Moment[] {
    if (this.startWeek) {
      const { date, isNewYear } = this;
      const month = date.month();

      return new Array(this.previousDays)
        .fill(null)
        .map((_, index) =>
          this.$moment()
            .set('y', this.year)
            .set('M', isNewYear ? month - 1 : month)
            .set('D', index)
        )
        .slice(-this.startWeek);
    }

    return [];
  }

  get isNewYear(): boolean {
    const { date } = this;

    return date.month() > 0;
  }

  get nextDaysList(): number[] {
    return new Array(this.nextDays)
      .fill(null)
      .map((_, index) => index + 1)
      .slice(0, 7 - (this.endWeek + 1));
  }

  get previousDays(): number {
    return moment()
      .month(this.monthValue - 1)
      .clone()
      .endOf('month')
      .date();
  }

  get nextDays(): number {
    return moment()
      .month(this.monthValue + 1)
      .clone()
      .endOf('month')
      .date();
  }

  get currentDays(): number {
    return this.date.clone().endOf('month').date();
  }

  get startWeek(): number {
    return this.date.clone().startOf('month').day();
  }

  get endWeek(): number {
    return this.date.clone().endOf('month').day();
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
    return this.limitFrom ? moment(this.limitFrom, this.format) : moment(String(this.startingYear), 'YYYY');
  }

  get momentLimitTo(): Moment {
    return this.limitTo ? moment(this.limitTo, this.format) : moment().add(2, 'y');
  }

  monthSelectChange(): void {
    this.date = this.$moment().set('y', this.year).set('M', this.monthValue);
  }

  yearSelectChange(): void {
    this.date = this.$moment().set('y', this.year).set('M', this.monthValue);
  }

  isActive(date: Moment): boolean {
    return this.date.unix() === date.unix();
  }

  isToday(date: Moment): boolean {
    const today = moment();

    return date.date() === today.date() && date.year() === today.year() && date.month() === today.month();
  }

  isBeetweenLimits(date: Moment): boolean {
    return date.isBetween(this.momentLimitFrom, this.momentLimitTo);
  }

  next(): void {
    const edgeMonth = 11;

    const nextMonth = this.date.month() + 1;
    const month = nextMonth > edgeMonth ? 0 : nextMonth;
    const year = nextMonth > edgeMonth ? this.year + 1 : this.year;

    this.setMonthAndYear(month, year);
    this.date = this.$moment().set('y', year).set('M', month);
  }

  prev(): void {
    const edgeMonth = 11;

    const prevMonth = this.date.month() - 1;
    const month = prevMonth < 0 ? edgeMonth : prevMonth;
    const year = prevMonth < 0 ? this.year - 1 : this.year;

    this.setMonthAndYear(month, year);
    this.date = this.$moment().set('y', year).set('M', month);
  }

  select(date: Moment): void {
    this.date = date;
    this.formattedValue = this.date.format(this.format);
    this.$emit('change', this.formattedValue);
  }

  today(): void {
    this.select(moment());
    this.setMonthAndYear(this.date.month(), this.date.year());
  }

  setMonthAndYear(month: number, year: number): void {
    this.monthValue = month;
    this.yearValue = year;
  }

  created(): void {
    moment.locale(this.localization);
  }
}
</script>

<style scoped lang="scss">
@import '@/assets/styles/_variables';

.calendar {
  background: $white-color;
  border: 1px solid $input-border-color;
  border-radius: 4px;
  box-shadow: 0 16px 32px $input-border-color;
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

/* containers begin */
.header-containr {
  background: #5495c5;
  color: $white-color;
  padding: 15px 10px;
}

.navigation-container {
  border-bottom: 1px solid $light-grey-color;
  display: flex;
  justify-content: space-between;
  padding: 20px;
}

.subnavigation-container {
  border-bottom: 1px solid $light-grey-color;
  padding: 7px 20px;
}

.week-container {
  display: grid;
  gap: 18px;
  grid-template-columns: repeat(7, 1fr);
}

.body-container {
  padding: 16px 20px;
}

.days-container {
  display: grid;
  gap: 15px;
  grid-template-columns: repeat(7, 1fr);
}

/* containers end */

/* elements begin */
.selects {
  display: flex;
}

.select {
  font-size: 14px;
  margin: 0 5px;
  min-width: 60px;
  padding: 0 !important;

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

.weekday {
  color: $medium-grey-color;
  font-weight: bold;
  font-size: 13px;
  text-align: center;
  text-transform: uppercase;

  @extend %text;
}

.day {
  align-items: center;
  display: flex;
  height: 28px;
  justify-content: center;
  margin: 0 auto;
  width: 28px;

  @extend %text;

  &.selected {
    background-color: $gold-light-color;
    color: $white-color !important;
    border-radius: 20px;
  }

  &.today {
    background-color: rgba($gold-light-color, 0.6);
    color: $white-color !important;
    border-radius: 20px;
  }
}

/* elements end */
</style>

import Vue from 'vue';
import Component from 'vue-class-component';

@Component
export class Elevators extends Vue {
  elevatorsRows: any[] = [];

  elevatorsRowsAutocomplete: any[] = [];

  parametersElevator = {
    page: 0,
    size: 100,
  };

  totalElevator = 0;

  get getElevatorListMix(): Array<any> {
    return this.elevatorsRowsAutocomplete;
  }

  async fetchElevatorsMix(value = ''): Promise<void> {
    const res = await this.$store.dispatch('organization/getList', {
      filter: value,
      ...this.parametersElevator,
      sort: '-registrationDate',
    });

    this.totalElevator = res.totalElements;

    this.elevatorsRows = res.content.sort((a, b) => a.statusId - b.statusId);

    this.elevatorsRowsAutocomplete = this.elevatorsRows.map((v) => {
      return {
        value: v.subject_id,
        text: v.registration_number + ' ' + v.subject.name,
      };
    });
  }

  async searchElevatorsMix(value: string | null): Promise<void> {
    if (value === null) {
      return;
    }

    const itemIndex = this.elevatorsRowsAutocomplete.findIndex((item: any) => item.text === value);

    if (itemIndex === -1) {
      await this.fetchElevatorsMix(value);
    }
  }
}

import Vue from 'vue';
import Component from 'vue-class-component';
import { getElementByFilter } from '@/utils/methodsForViews';
import { mapGetters } from 'vuex';
import config from '@/views/NSI/config';
import { tomorrow } from '@/utils/date';

@Component({
  computed: {
    ...mapGetters({
      user: 'auth/getUserInfo',
    }),
  },
})
export class AdditionalMix extends Vue {
  tomorrow = tomorrow();
  today = new Date();
  elevatorsRows: any[] = [];
  rows: Array<any> = [];
  preloader = false;

  get user() {
    if (this.userSubject === undefined) {
      return null;
    }
    return this.$store.state.auth.user;
  }

  get subjectOfUser() {
    return this.userSubject;
  }

  get kindOfSubject() {
    if (this.isSubject) return [];
    return this.subjectOfUser.kind.split(',');
  }

  get isSubject(): boolean {
    return (
      this.subjectOfUser === null ||
      this.subjectOfUser === undefined ||
      this.subjectOfUser.kind === '' ||
      this.subjectOfUser.kind === undefined
    );
  }

  get isElevator() {
    return this.kindOfSubject.find((x) => x === 'ELEVATOR') !== undefined;
  }
  get userSubject() {
    return this.$store.state.auth.user.subject;
  }

  get getLotsTarget() {
    return this.$store.getters['nsi/getLotsTarget'];
  }

  get getLotsPurpose() {
    return this.$store.getters['nsi/getLotsPurpose'];
  }

  get getQualityIndicators() {
    return this.$store.getters['nsi/getQualityIndicators'];
  }

  /**
   * @param options
   */
  async getElementByFilterMix(options: Array<{ field: string; operator: string; value: any }>): Promise<void> {
    await getElementByFilter(this, options);
  }

  nsiConfigSectionByKey(key: string) {
    return config[key];
  }

  // /**
  //  * Возвращаем информацию по товаропро-лям, элеваторам и т.п.
  //  * @param id
  //  */
  // getManufactureBySdiz(id: number): object {
  //     const bySubjectId = value => value.subject_id === id;
  //TODO починить сбор мануфактуры
  // let manufacture = this.getManufacturerListMix.find(bySubjectId);
  //
  // if (manufacture === undefined) {
  //     manufacture = this.getManufacturerByIdMix(id);
  // }

  // if(typeof this.elevatorsRows !== 'undefined'){
  //     let elevator = this.elevatorsRows.find(bySubjectId);
  //
  //     if(elevator !== undefined){
  //         manufacture.registration_number = elevator.registration_number;
  //     }
  // }
  //
  //     return {};
  // }

  async fetchOkpd2Msh(lotType: object = { is_grain: true }, isActive = true) {
    const lotTypeKey = Object.keys(lotType)[0];
    const nsiConfigSection = this.nsiConfigSectionByKey('nsi-okpd2-msh');
    const okpd2Data = this.$store.getters[nsiConfigSection.storeGetter[lotTypeKey]];
    const payload: any = {
      url: nsiConfigSection.apiUrl,
      params: {
        pageable: {
          sort: [
            {
              property: 'name',
              direction: 'ASC',
            },
          ],
        },
        ...lotType,
      },
    };
    if (isActive) payload.params.is_actual = true;

    if (!okpd2Data.length) {
      const { content } = await this.$store.dispatch('nsi/getList', payload);

      this.$store.commit(nsiConfigSection.storeSetter[lotTypeKey], content);
    }
  }

  async fetchProductType(lotType: object = { is_grain: true }, isActive = true) {
    const lotTypeKey = Object.keys(lotType)[0];
    const nsiConfigSection = this.nsiConfigSectionByKey('nsi-type-product-msh');
    const typeProducts = this.$store.getters[nsiConfigSection.storeGetter[lotTypeKey]];
    const payload: any = {
      url: nsiConfigSection.apiUrl,
      params: {
        pageable: {
          sort: [
            {
              property: 'name',
              direction: 'ASC',
            },
          ],
        },
        ...lotType,
        active: isActive,
      },
    };

    if (!typeProducts.length) {
      const { content } = await this.$store.dispatch('nsi/getList', payload);

      this.$store.commit(nsiConfigSection.storeSetter[lotTypeKey], content);
    }
  }
  async fetchAddressById(id): Promise<void> {
    const response = await this.$store.dispatch('priorityAddress/showAddress', {
      id: id,
    });
    return response;
  }

  async fetchLotsTarget() {
    const { content } = await this.$store.dispatch('nsi/getList', {
      url: config['nsi-lots-target'].apiUrl,
      params: { actual: true },
    });
    this.$store.commit('nsi/setLotsTarget', content);
  }

  async fetchLotsPurpose() {
    const { content } = await this.$store.dispatch('nsi/getList', {
      url: config['nsi-lots-purpose'].apiUrl,
      params: { actual: true },
    });
    this.$store.commit('nsi/setLotsPurpose', content);
  }

  notify(title, notify) {
    this.$notify({
      clean: notify,
      group: 'notifications-m',
      type: 'warning',
      title: title,
      text: '',
    });
  }

  async fetchTnved(okpd2: string, actual = true) {
    const { data } = await this.$axios.post('/api/nci/tnved', {
      okpd2,
      actual,
    });

    return data.content;
  }

  async fetchTnvedById(id: number) {
    const { data } = await this.$axios.post('/api/nci/tnved/show', {
      id,
    });

    return data;
  }
}

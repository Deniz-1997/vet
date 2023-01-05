<template>
  <div class="sdiz">
    <v-container>
      <notifications group="sdiz" position="bottom right" />
      <notifications group="notifications-m" position="bottom right" />
      <router-view></router-view>
    </v-container>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Watch, Mixins } from 'vue-property-decorator';
import { RequestMix } from '@/utils/mixins/request';
import { FiasMix } from '@/utils/mixins/fias';
import { AdditionalMix } from '@/utils/mixins/additional';
import { ActionsMix } from '@/utils/mixins/actions';
import { DocsTransportsTypeVueModel } from '@/models/Sdiz/DocsTransportType.vue';
import {
  applyMask,
  numberThousandsMask,
  numberThousandsUnmask,
} from '@/components/common/inputs/mask/numberThousandsMask';
import nsiList from '@/views/NSI/config';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import { SdizGpbVueModel } from '@/models/Sdiz/Data/SdizGpb.vue';
import { ErrorMix } from '@/utils/mixins/error';
import { PermissionMix } from '@/utils/mixins/permission';
import { SdizElevatorModel } from '@/models/Sdiz/Data/SdizElevator.vue';

@Component({
  name: 'sdiz',
})
export default class Sdiz extends Mixins(RequestMix, FiasMix, AdditionalMix, ActionsMix, ErrorMix, PermissionMix) {
  @Prop({ type: String, default: 'АРМ Товаропроизводителя' }) public title!: string;

  types: DocsTransportsTypeVueModel[] = [];

  isLoading: boolean = true;

  // открыт ли диалог погашения
  isExtinguishSdiz = false;

  isGrowthFunc: boolean = false; // функция роста, пока в альфе висит

  // открыт ли диалог отказа погашения
  isReturnModalOpened: boolean = false;

  clear: number = 0;

  isClearFiltersAndReloadRows = false;

  numberThousandsMask = numberThousandsMask;

  numberThousandsUnmask = numberThousandsUnmask;

  applyMask = applyMask;

  subject_id: number = 0;

  subject_name: string = '';

  get selectItem(): DocsTransportsTypeVueModel[] {
    return this.types;
  }

  set selectItem(value: DocsTransportsTypeVueModel[]) {
    this.types = value;
  }

  @Watch('subjectOfUser')
  onSubjectOfUser(value) {
    if (value !== undefined) {
      this.subject_id = value.subject_id;
      this.subject_name = value.name;
    }
  }
  get subjectId(): number {
    return typeof this.$store.state.auth.user.subject === 'undefined'
      ? 0
      : this.$store.state.auth.user.subject.subject_id;
  }

  get subjectName(): string {
    return typeof this.$store.state.auth.user.subject === 'undefined' ? '-' : this.$store.state.auth.user.subject.name;
  }

  get subjectShortName(): string {
    return typeof this.$store.state.auth.user.subject === 'undefined'
      ? '-'
      : this.$store.state.auth.user.subject.short_name;
  }

  async fetchTypes(): Promise<void> {
    const types = this.$store.getters['sdiz/getTypes'];
    if (!types.length) {
      const { response } = await this.$store.dispatch('sdiz/getSdizTypes', {});
      this.$store.commit('sdiz/setTypes', response);
    }
  }

  async fetchTransports(): Promise<void> {
    const { content } = await this.$store.dispatch('nsi/getList', {
      url: nsiList['nsi-transport-type'].apiUrl,
      params: { actual: true },
    });

    this.selectItem = content.map(({ name, id }): DocsTransportsTypeVueModel => ({ label: name, value: id }));
  }

  callbackLoadList(model: any, modelArray: SdizVueModel[] | SdizGpbVueModel[], response: any[]): any[] {
    return response.map((entry) => {
      function removeEmpty(obj) {
        return Object.fromEntries(Object.entries(obj).filter(([_, v]) => v !== null));
      }

      const entity = removeEmpty(entry);

      return new model.constructor(entity);
    });
  }

  returnNewModel(data, componentName): SdizVueModel | SdizGpbVueModel | SdizElevatorModel {
    switch (componentName) {
      case 'sdiz_gpb':
        return new SdizGpbVueModel(data);
      case 'sdiz':
        return new SdizVueModel(data);
      case 'sdiz_elevator':
        return new SdizElevatorModel(data);
      default:
        return new (data as any).constructor(data);
    }
  }
}
</script>

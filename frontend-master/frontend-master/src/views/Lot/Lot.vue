<template>
  <div class="main">
    <notifications group="notifications-m" position="bottom right" />
    <notifications group="lot" position="bottom right" />
    <router-view></router-view>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Mixins } from 'vue-property-decorator';
import { RequestMix } from '@/utils/mixins/request';
import { FiasMix } from '@/utils/mixins/fias';
import { AdditionalMix } from '@/utils/mixins/additional';
import { ActionsMix } from '@/utils/mixins/actions';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import { ErrorMix } from '@/utils/mixins/error';
import { PermissionMix } from '@/utils/mixins/permission';
import { DictionariesMix } from '@/utils/mixins/dictionaries';
import { LotType } from '@/utils/enums/LotType';

@Component({ name: 'lot' })
export default class Lot extends Mixins(
  RequestMix,
  FiasMix,
  AdditionalMix,
  ActionsMix,
  ErrorMix,
  PermissionMix,
  DictionariesMix
) {
  @Prop({ type: String, default: 'АРМ Товаропроизводителя' }) public title!: string;
  @Prop({ type: String, default: 'lot/show' }) public showItem!: string;
  @Prop({ type: String, default: 'lot/getList' }) public getList!: string;
  @Prop({ type: String, default: 'lot/delete' }) public deleteLink!: string;
  @Prop({ type: String, default: 'lot/update' }) public updateLink!: string;
  @Prop({ type: String, default: 'lot/create' }) public createLink!: string;
  @Prop({ type: String, default: 'lot_list' }) public afterDeletePush!: string;
  @Prop({ type: String, default: 'lot_detail' }) public afterUpdatePush!: string;
  @Prop({ type: String, default: 'lot_detail' }) public afterCreatePush!: string;
  type: LotType | null | undefined = null;
  LotType = LotType;
  isLoading: boolean = false;
  clear = 0;
  disableActions = false;
  lotDataVueModel: LotDataVueModel = new LotDataVueModel();

  setIsLoading(value: boolean) {
    this.isLoading = value;
  }
}
</script>

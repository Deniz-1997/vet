<template>
  <div class="main">
    <notifications group="reserves" position="bottom right" />
    <router-view></router-view>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Mixins } from 'vue-property-decorator';
import { AdditionalMix } from '@/utils/mixins/additional';
import { currentDay } from '@/utils/date';
import { PermissionMix } from '@/utils/mixins/permission';

@Component({ name: 'reserves' })
export default class Reserves extends Mixins(AdditionalMix, PermissionMix) {
  @Prop({ type: String, default: 'АРМ Росрезерва' }) public title!: string;

  isShowModalForRecord = false;

  currentDay = currentDay();

  //todo перенести в стор
  get getUsername() {
    return this.$store.state.auth.user.first_name + ' ' + this.$store.state.auth.user.last_name;
  }

  get subjectId() {
    return this.$store.state.auth.user.subject?.subject_id;
  }
}
</script>

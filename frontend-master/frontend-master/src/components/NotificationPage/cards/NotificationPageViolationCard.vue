<template>
  <v-row>
    <v-col cols="12">
      <span>Организация: {{ item.subject.name }}</span>
    </v-col>
    <v-col cols="12">
      <span>{{ item.type.name }}: {{ item.difference }}</span>
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator';
import { ViolationItem } from '@/services/mappers/violation';

@Component({
  name: 'NotificationPageViolationCard',
})
export default class NotificationPageViolationCard extends Vue {
  @Prop({ type: Object, default: () => null }) readonly item!: ViolationItem;
}
</script>

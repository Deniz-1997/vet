<template>
  <div>
    <Dialog-component
      v-if="innerValue"
      :value="innerValue"
      :prompt="false"
      cancel-title=""
      confirm-title=""
      width="480"
      hide-actions
      controls-justify="justify-end"
    >
      <template #title>
        <span>{{ title }}</span>
      </template>

      <template #content>
        <component :is="component" v-if="form" :item="form" />

        <v-row justify="end">
          <v-col cols="12" class="col-exclude">
            <DefaultButton title="Закрыть" @click="innerValue = false" />
          </v-col>
        </v-row>
      </template>
    </Dialog-component>

    <v-overlay :value="value && !form" :absolute="true">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop, Watch } from 'vue-property-decorator';
import { NotificationItem } from '@/services/mappers/notification';
import { ENotificationObjectType } from '@/services/enums/notification';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import NotificationPageViolationCard from './cards/NotificationPageViolationCard.vue';

@Component({
  name: 'NotificationPageCardModal',
  components: { DefaultButton, DialogComponent },
})
export default class NotificationPageCardModal extends Vue {
  @Prop({ type: Boolean, required: true }) readonly value!: boolean;
  @Prop({ type: Object, default: () => null }) readonly item!: NotificationItem;
  isLoading = false;
  form: any = null;

  get innerValue() {
    return Boolean(this.value && this.form);
  }

  set innerValue(v) {
    this.$emit('input', v);
    this.form = null;
  }

  get title() {
    return this.item.object.name;
  }

  get component() {
    if (!this.item.object?.type) {
      return null;
    }

    const components = {
      [ENotificationObjectType.VIOLATION]: NotificationPageViolationCard,
    };
    return components[this.item.object?.type];
  }

  async fetch() {
    const { id, type } = this.item.object;
    if (type && id && this.$service.notification[type]) {
      const { data } = await this.$service.notification[type].findOne(id);
      this.form = data;
    }
  }

  @Watch('item')
  $onVisible(v) {
    if (v) {
      this.fetch();
    }
  }
}
</script>

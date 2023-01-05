<template>
  <v-dialog
    v-if="show"
    v-model="show"
    :content-class="addClass"
    :fullscreen="fullscreen"
    :persistent="persistent"
    :no-click-animation="noClickAnimation"
    :width="width"
    @input="closeDialog"
  >
    <div :class="customClass">
      <v-card>
        <v-card-title :class="`d-flex ${titleAlign}`">
          <slot name="title" />
          <div>
            <icon-component
              v-if="withFullscreen"
              :width="16"
              class="mr-2"
              icon-color="#828286"
              @click="toggleFullscreen"
            >
              <fullscreen-icon />
            </icon-component>
            <icon-component v-if="withCloseIcon" :width="16" icon-color="#828286" @click="handleCancel">
              <close-icon />
            </icon-component>
          </div>
        </v-card-title>
        <v-card-text>
          <slot name="content">
            <text-component variant="p" class="no-data-text">Данные отсутствуют</text-component>
          </slot>
        </v-card-text>
        <v-card-actions v-if="!hideActions && prompt" :class="['d-flex', controlsJustify]">
          <default-button
            v-if="confirmTitle"
            :disabled="confirmDisabled"
            :loading="isLoading"
            :title="confirmTitle"
            class="mr-2"
            size="micro"
            variant="primary"
            @click="handleConfirm"
          />
          <default-button
            v-if="cancelTitle"
            :title="cancelTitle"
            size="micro"
            variant="primary"
            @click="handleCancel"
          />
        </v-card-actions>
        <v-card-actions v-else-if="!hideActions" :class="['d-flex', controlsJustify]">
          <default-button
            v-if="cancelTitle"
            :title="cancelTitle"
            class="mr-2"
            size="micro"
            variant="default"
            @click="handleCancel"
          />
          <default-button
            v-if="confirmTitle"
            :disabled="confirmDisabled"
            :title="confirmTitle"
            size="micro"
            variant="primary"
            @click="handleConfirm"
          />
        </v-card-actions>
      </v-card>
    </div>
  </v-dialog>
</template>

<script lang="ts">
import { Component, Model, Prop, Vue, Watch } from 'vue-property-decorator';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import FullscreenIcon from '@/components/common/IconComponent/icons/FullscreenIcon.vue';
import CloseIcon from '@/components/common/IconComponent/icons/CloseIcon.vue';
import IconComponent from '@/components/common/IconComponent/IconComponent.vue';
import TextComponent from '@/components/common/Text/Text.vue';

@Component({
  name: 'dialog-component',
  components: {
    CloseIcon,
    DefaultButton,
    FullscreenIcon,
    IconComponent,
    TextComponent,
  },
})
export default class Dialog extends Vue {
  @Model('change', { type: Boolean, default: false }) value!: boolean;

  @Prop({ type: String, default: '' }) readonly id!: string;
  @Prop({ type: String, default: 'Да' }) readonly confirmTitle!: string;
  @Prop({ type: String, default: 'Нет' }) readonly cancelTitle!: string;
  @Prop({ type: String, default: 'auto' }) readonly width!: string;
  @Prop({ type: String, default: 'justify-space-between' }) readonly titleAlign!: string;
  @Prop({ type: String, default: 'justify-center' }) readonly controlsJustify!: string;
  // @Prop({ type: Boolean, default: true }) readonly closeOnOutsideClick!: boolean;
  @Prop({ type: Boolean, default: true }) readonly closeOnSuccess!: boolean;
  @Prop({ type: Boolean, default: true }) readonly closeOnCancel!: boolean;
  @Prop({ type: Boolean, default: false }) readonly noDataShow!: boolean;
  @Prop({ type: Boolean, default: true }) readonly prompt!: boolean;
  @Prop({ type: Boolean, default: false }) readonly persistent!: boolean;
  @Prop({ type: Boolean, default: false }) readonly noClickAnimation!: boolean;
  @Prop({ type: Boolean, default: false }) readonly isLoading!: boolean;
  @Prop(String) readonly title!: string;
  @Prop(Boolean) readonly withFullscreen!: boolean;
  @Prop(Boolean) readonly withCloseIcon!: boolean;
  @Prop(Boolean) readonly confirmDisabled!: boolean;
  @Prop(Boolean) readonly hideActions!: boolean;
  @Prop({ type: String, default: '' }) readonly customClass!: string;
  @Prop({ type: String, default: '' }) readonly addClass!: string;

  fullscreen = false;

  get show(): boolean {
    return this.value;
  }

  set show(value: boolean) {
    this.$emit('change', value);
  }

  handleConfirm(): void {
    if (this.closeOnSuccess) {
      this.closeDialog();
    }
    this.$emit('onSuccess');
  }

  handleCancel(): void {
    if (this.closeOnCancel) {
      this.closeDialog();
    }
    this.$emit('onCancel');
  }

  //некорреткно работал метод, поставил persistent
  handleOutsideClick(): void {
    if (this.persistent) {
      this.closeDialog();
    }
  }

  closeDialog(): void {
    this.show = false;
  }

  toggleFullscreen(): void {
    this.fullscreen = !this.fullscreen;
  }

  @Watch('show', { immediate: true })
  onInnerShowChange(show: boolean): void {
    if (show) {
      this.$emit('onOpen');
    } else {
      this.$service.notify.flush();
      this.$emit('close');
    }
  }
}
</script>

<style lang="scss">
.no-data-text {
  font-size: 18px;
  text-align: center;
}

.v-dialog > .v-card > .v-card__title,
.v-dialog > .v-card > .v-card__text,
.v-card__actions {
  padding: 10px 20px;
}
</style>

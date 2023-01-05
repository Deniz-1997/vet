<template>
  <v-dialog v-model="isModalOpen" :persistent="persistent" width="600">
    <div class="container">
      <div class="title">
        <span class="spanTitle">{{ title }}</span>
        <icon-component v-if="!persistent" :width="16" icon-color="#828286" @click="isModalOpen = false">
          <close-icon />
        </icon-component>
      </div>
      <div class="description">
        {{ text }}
      </div>
      <div class="buttons">
        <button
          v-for="(item, index) in buttons"
          :key="index"
          :class="item.type === 'decline' ? 'cancel' : 'apply'"
          @click="$emit('apply', item.callback)"
        >
          {{ item.label || getDefaultLabel(item.type) }}
        </button>
      </div>
      <v-overlay :value="loading" absolute>
        <v-progress-circular indeterminate size="64"></v-progress-circular>
      </v-overlay>
    </div>
  </v-dialog>
</template>

<script lang="ts">
import Modal from '@/utils/global/mixins/modal';
import { Component, Prop, Mixins } from 'vue-property-decorator';
import CloseIcon from '@/components/common/IconComponent/icons/CloseIcon.vue';
import IconComponent from '@/components/common/IconComponent/IconComponent.vue';

@Component({
  name: 'ConfirmModal',
  components: { CloseIcon, IconComponent },
})
export default class ModalConfirmDelete extends Mixins(Modal) {
  @Prop({ type: String }) readonly title!: string;
  @Prop({ type: String }) readonly text!: string;
  @Prop({ type: Array, default: () => [] }) readonly buttons!: any[];
  @Prop({ type: Boolean, default: false }) readonly loading!: boolean;
  @Prop({ type: Boolean, default: true }) readonly persistent!: boolean;

  getDefaultLabel(type) {
    if (type === 'decline') {
      return 'Отменить';
    }

    return 'Подтвердить';
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.container {
  background: $white-color;
  border: 1px solid $light-grey-color;
  box-sizing: border-box;
  box-shadow: 0 16px 32px rgba($black-color, 0.1);
  border-radius: 4px;
  z-index: 11;
  padding: 20px;
}

.title {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
}

.spanTitle {
  text-transform: uppercase;
  font-size: 18px;
  font-weight: bold;
  color: $footer-color;
}

.lineChoose {
  margin-top: 20px;
}

.spanChoose {
  text-decoration: underline;
  margin-right: 15px;
  font-size: 16px;
  color: $medium-grey-color;
  cursor: pointer;
}

.baseInformation {
  margin-top: 25px;

  @include respond-to('medium') {
    margin-top: 15px;
  }

  @include respond-to('small') {
    margin-top: 10px;
  }
}

.buttons {
  display: flex;
  margin-top: 20px;
  justify-content: center;

  & > button {
    margin-right: 20px;

    &:last-child {
      margin-right: 0;
    }
  }
}

.cancel {
  background-color: $white-color;
  border: 1px solid $input-border-color;
  border-radius: 4px;
  color: $medium-grey-color;
  padding: 9px 25px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin-right: 10px;
  cursor: pointer;
  outline: none;

  @include respond-to('medium') {
    padding: 9px 15px;
    font-size: 14px;
  }

  @include respond-to('small') {
    font-size: 12px;
  }

  &:hover {
    box-shadow: 0 0 5px rgba($black-color, 0.5);
  }
}

.description {
  color: $black-color;
  padding: 20px 0;
  text-align: center;
  font-size: 16px;
  font-weight: 500;
}

.apply {
  background-color: $gold-dark-color;
  border: none;
  border-radius: 4px;
  color: $white-color;
  padding: 9px 25px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  cursor: pointer;
  outline: none;

  @include respond-to('medium') {
    padding: 9px 15px;
    font-size: 14px;
  }

  @include respond-to('small') {
    font-size: 12px;
  }

  &:hover {
    box-shadow: 0 0 5px rgba($black-color, 0.5);
  }
}

.cross {
  cursor: pointer;
}
</style>

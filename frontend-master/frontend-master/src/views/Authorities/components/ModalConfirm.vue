<template>
  <div class="modalReasons">
    <div class="container">
      <div class="title">
        <span class="spanTitle">{{ title }}</span>
        <img src="/icons/cross.svg" class="cross" @click="clickClose" />
      </div>
      <div class="description">Заявление рассмотрено, замечаний нет №{{ item.request_id }}?</div>
      <div class="buttons">
        <button class="cancel" @click="clickClose">Отменить</button>
        <button class="apply" @click="onConfirm">Применить</button>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator';

type Props = {
  title: string;
  action: string;
  id: number | string;
  typeId: number | string;
  requestId: number;
  item: any;
};

@Component({
  name: 'modal-reasons',
})
export default class ModalReasons extends Vue {
  @Prop({
    type: String,
    default: () => '',
  })
  readonly title: Props['title'] | undefined;

  @Prop({
    type: String,
    default: () => '',
  })
  readonly action: Props['action'] | undefined;

  @Prop({
    type: Object,
    default: () => ({}),
  })
  readonly item: Props['item'] | undefined;

  reject_reason = '';
  reason = '';
  clickClose() {
    this.$emit('click-close');
  }

  async onConfirm() {
    if (this.action === 'confirm') {
      await this.$store.dispatch('approvalTask/approveTask', this.item);
    } else {
      const params = {
        id: this.item.id,
        reject_reason: this.reject_reason,
        reason: this.reason,
      };
      await this.$store.dispatch('approvalTask/rejectTask', params);
    }
    this.$emit('update-information');
    this.$emit('click-close');
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.container {
  position: fixed;
  top: calc(50vh - 180px);
  left: 37%;

  width: 506px;
  padding: 20px;

  z-index: 10;

  background: $white-color;
  border: 1px solid $light-grey-color;
  box-sizing: border-box;
  box-shadow: 0 16px 32px rgba($black-color, 0.1);
  border-radius: 4px;

  @include respond-to('medium') {
    width: 406px;
    top: calc(50vh - 120px);
  }

  @include respond-to('small') {
    width: 350px;
    top: calc(50vh - 120px);
  }
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
  justify-content: flex-end;
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
  font-weight: 700;
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

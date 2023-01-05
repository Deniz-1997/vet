<template>
  <div class="modal-element">
    <div class="overlay" @click="$emit('close')"/>
    <div class="modal">
      <div class="modal__header">
        <slot name="header">

        </slot>
        <div class="close">
          <img
            src="/icons/cross.svg"
            class="cross"
            @click="$emit('close')"
          >
        </div>
      </div>
      <div class="modal__body">
        <slot name="body"></slot>
      </div>
      <div class="modal__footer">
        <slot name="footer"></slot>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
  import { Component, Vue } from 'vue-property-decorator'

  @Component({
    name: 'modal',
  })

  export default class Modal extends Vue {
  }
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables.scss';
@import '@/assets/styles/_mixins.scss';

.overlay {
  background-color: rgba($black-color, 0.3);
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 9;
}


.container {
  position: fixed;
  left: 50%;
  width: 406px;
  top: 50%;
  z-index: 10;
  background: $white-color;
  border: 1px solid $input-border-color;
  box-sizing: border-box;
  box-shadow: 0 16px 32px rgba($black-color, 0.1);
  border-radius: 4px;
  z-index: 11;
  padding: 20px;
  transform: translate(-50%, -50%);
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
    box-shadow: 0 0 5px rgba($black-color,0.5);
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
    box-shadow: 0 0 5px rgba($black-color,0.5);
  }
}

.cross {
  cursor: pointer;
}

</style>
<template>
  <v-dialog v-model="showModal" persistent width="600">
    <div class="container">
      <div class="title">
        <span class="spanTitle">{{ title }}</span>
      </div>
      <div class="description">
        {{ text }} <span>{{ name }} ?</span>
      </div>
      <div class="buttons">
        <button class="cancel" @click="$emit('close')">Отменить</button>
        <button class="apply" @click="$emit('apply', name)">Подтвердить</button>
      </div>
    </div>
  </v-dialog>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator';

type Props = {
  title: any;
  action: any;
  name: any;
  text: any;
  showModal: boolean;
};

@Component({
  name: 'modal-reasons',
})
export default class ModalConfirmDelete extends Vue {
  @Prop({ type: String }) readonly title: Props['title'] | {};
  @Prop({ type: String }) readonly action: Props['action'] | {};
  @Prop({ type: String }) readonly name: Props['name'] | {};
  @Prop({ type: String }) readonly text: Props['text'] | {};
  @Prop(Boolean) showModal!: boolean;
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

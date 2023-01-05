<template>
  <v-menu content-class="dialog" offset-y>
    <template v-slot:activator="{ on, attrs }">
<!--      <v-btn v-on="on" v-bind="attrs" :width="width" :ripple="false"-->
<!--             class="s-btn text-none" elevation="0" color="primary">-->
<!--        {{ title }}-->
<!--      </v-btn>-->

      <default-button v-on="on" v-bind="attrs" :title="title" :variant="variant" :size="size" :class="{'ml-0': offLeftMrg}"/>

<!--      TODO return after correct component-->
<!--      <button-component-->
<!--        v-on="on"-->
<!--        @click="handleClick"-->
<!--        v-bind="attrs"-->
<!--        :append-icon="appendIcon"-->
<!--        :depressed="depressed"-->
<!--        :disabled="disabled"-->
<!--        :prepend-icon="prependIcon"-->
<!--        :size="size"-->
<!--        :title="title"-->
<!--        :type="type"-->
<!--        :variant="variant"-->
<!--        :width="width"-->
<!--        class="button dropdown-button"-->
<!--      />-->
    </template>
    <v-list>
      <slot name="list"/>
    </v-list>
  </v-menu>
</template>

<script lang="ts">
import {Component, Prop} from 'vue-property-decorator';
import ButtonComponent from './DefaultButton.vue';
import ActionsButtons from "@/components/Forms/ActionsButtons.vue";
import DefaultButton from "@/components/common/buttons/DefaultButton.vue";

@Component({
  name: 'dropdown-button',
  components: {
    DefaultButton,
    ActionsButtons,
    ButtonComponent,
  },
})
export default class DropDownButton extends ButtonComponent {
  @Prop(Boolean) offLeftMrg!: boolean;

  handleClick(event: Event): void {
    this.$emit('click', event);
  }
}
</script>

<style scoped lang="scss">
@import '@/assets/styles/mixins';
@import '@/assets/styles/variables';
@import '@/assets/styles/global';





//TODO delete styles after create correct btn

.s-btn.primary {
  border-color: $button-primary-background !important;
  background-color: $button-primary-background !important;
  color: $white-color !important;
}

.s-btn {
  text-decoration: none !important;
  background-color: $white-color !important;
  color: $medium-grey-color !important;
  text-align: center;
  letter-spacing: .5px;
  @extend .shadow-1;
  transition: background-color .2s ease-out !important;
  cursor: pointer;
  font-style: normal;
  font-weight: 400;
  font-size: 16px;
  font-feature-settings: 'zero' on;
  //height: 40px !important;
  //padding: 0 35px !important;

  &:hover {
    background-color: $button-raised-background-hover;
    @extend .shadow-1-half;
  }

  border: 1px solid $input-border-color !important;
  transition-duration: .0s !important;
}

.v-btn:hover {
  box-shadow: 0 0 5px rgba($black-color, 0.5) !important;
}

.v-btn::before {
  background-color: transparent;
}

.v-btn.v-btn--disabled.v-btn--has-bg.s-btn:disabled {
  pointer-events: none;
  box-shadow: none;
  cursor: default;
  background-color: $button-disabled-background !important;
  color: $button-disabled-color !important;
}

.theme--light.v-btn.v-btn--disabled.v-btn--has-bg.cancel-btn:disabled {
  background-color: $button-disabled-background !important;
  color: $button-disabled-color !important;
}

.warning-d{
  background-color: $button-disabled-background !important;
  color: $button-disabled-color !important;
}
</style>

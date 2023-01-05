<template>
  <v-col :cols="cols" :sm="sm" :md="md" :lg="lg" :xl="xl">
    <v-row justify="end" no-gutters class="block-btns">
      <v-col align="right" cols="12">
        <v-btn
            v-if="cancelTitle !== ''"
            :class="getClasses"
            :disabled="disabledCancelButton || loading"
            :height="height"
            :ripple="ripple"
            class="cancel-btn"
            elevation="0"
            :width="widthCancel"
            @click="onCancel">
          {{ cancelTitle }}
        </v-btn>
        <v-btn :class="getClassesSuccessBtn"
               :height="height"
               :disabled="disabledSuccessButton"
               :loading="loading || preloader"
               :ripple="false"
               class="mr-1"
               :width="widthSuccess"
               elevation="0"
               :color="getColorSuccessBtn"
               @click="onSuccess">
          {{ successTitle }}
        </v-btn>
        <v-col cols="12" class="text-center pa-0"
               :style="{'width': widthSuccess + 'px'}"
               v-show="hint !== null">
          <text-component class="text-caption text-center orange--text" variant="span">
            {{hint}}
          </text-component>
        </v-col>
      </v-col>
    </v-row>
  </v-col>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator'
import ButtonComponent from "@/components/common/buttons/DefaultButton.vue";
import TextComponent from "@/components/common/Text/Text.vue";

@Component({
  name: 'actions-buttons',
  components: {TextComponent, ButtonComponent}
})
export default class ActionsButtons extends Vue {
  @Prop({type: Boolean, default: false}) readonly tile!: boolean;
  @Prop({type: Boolean, default: false}) readonly ripple!: boolean;
  @Prop({type: Boolean, default: false}) readonly loading!: boolean;
  @Prop({type: Boolean, default: false}) readonly block!: boolean;
  @Prop({type: Boolean}) readonly disabledCancelButton!: boolean;
  @Prop({type: Boolean}) readonly disabledSuccessButton!: boolean;
  @Prop({type: Boolean}) readonly dropdown!: boolean;

  @Prop({type: String, default: 'primary'}) readonly color!: string;
  @Prop({default: 'end'}) isJustify!: string;
  @Prop({default: '6'}) xl!: string;
  @Prop({default: '6'}) lg!: string;
  @Prop({default: '12'}) md!: string;
  @Prop({default: '6'}) sm!: string;
  @Prop({default: '6'}) cols!: string;
  @Prop({default: false}) preloader!: boolean;
  @Prop({type: String, default: 'Оформить'}) readonly successTitle!: string;
  @Prop({type: String, default: ''}) readonly cancelTitle!: string;
  @Prop({default: null}) hint!: string;
  @Prop({default: 15}) marginCancelBtn!: number;
  @Prop({default: 150}) widthCancel!: number;
  @Prop({default: 150}) widthSuccess!: number;

  @Prop() readonly to!: string | object;
  @Prop() readonly height!: string | object;

  get getSize(): string {
    return this.$vuetify.breakpoint.name;
  }

  get getClasses() {
    return ['s-btn', 'text-none', 'mr-'+this.marginCancelBtn];
  }

  get getJustify(): string {
    return (this.getSize === 'sm')? 'center' : 'right';
  }

  get getClassesSuccessBtn(): string {
    return (this.hint === '')? 'warning-s s-btn text-none' : 's-btn text-none';
  }

  get getColorSuccessBtn(): string {
    // return (this.hint === '')? 'primary' : '';
    return 'primary';
  }

  onSuccess(event): void {
    this.$emit('click', event);
    this.$emit('onSuccess');
  }

  onCancel(event): void {
    this.$emit('onCancel');
  }
}
</script>
<style lang="scss" scoped>
@import '@/assets/styles/_mixins.scss';
@import '@/assets/styles/_variables.scss';
@import '@/assets/styles/_global.scss';


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
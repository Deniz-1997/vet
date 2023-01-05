<template>
  <v-container fluid>
    <v-row>
      <v-col cols="12">
        <text-component v-if="!detail" class="title d-flex align-center" variant="span">
          <span>{{ titleCreate }}</span>
        </text-component>
        <template v-else>
          <text-component class="title d-flex align-center" variant="span">
            <span>{{ detailTitle }}</span>
            <v-chip v-show="!create" :color="edit ? 'warning' : ''" class="ml-3" label> {{ subTitle }} </v-chip>
            <TooltipButton
              v-show="model.status_id !== 1"
              class="float-left ml-1 mt-1"
              tooltip-text="Печать"
              type="print"
              @click="isPrintModal = true"
            />
          </text-component>
          <slot name="subHeader" />
        </template>
      </v-col>
      <slot name="tabs" />
      <slot name="forms" />
      <slot name="activities" />
      <slot name="buttons" />
      <slot name="signature" />
      <slot name="dialog" />
    </v-row>

    <PrintModal v-model="isPrintModal" :measure-id="model.id" :service="`${model.export_apiendpoint}/pdf`" />
  </v-container>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import TextComponent from '@/components/common/Text/Text.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import { RshnFormMix } from '@/utils/mixins/rshn/rshnForm';
import TooltipButton from '@/components/common/buttons/TooltipButton.vue';
import PrintModal from '@/components/PrintModal/PrintModal.vue';

@Component({
  name: 'rshn-form',
  components: {
    TooltipButton,
    ButtonComponent,
    TextComponent,
    PrintModal,
  },
})
export default class RshnForm extends Mixins(RshnFormMix) {}
</script>

<style lang="scss" scoped></style>

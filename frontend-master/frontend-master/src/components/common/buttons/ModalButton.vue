<template>
  <v-dialog v-model="showModal" persistent :max-width="width">
    <template #activator="{ on, attrs }">
      <button-component
        :attrs="attrs"
        :disabled="disabled"
        :class="btnClass"
        :on="on"
        :title="buttonText"
        :variant="primary ? 'primary' : 'default'"
        size="micro"
      />
    </template>
    <v-card>
      <v-card-title class="text-justify">
        <text-component :variant="variantText">
          {{ modalText }}
        </text-component>
      </v-card-title>
      <v-card-actions class="px-6 py-4">
        <v-row>
          <v-col class="text-right">
            <button-component
              :title="cancelButtonText"
              size="micro"
              style="margin-right: 15px"
              @click="handleClose()"
            />
            <button-component
              variant="primary"
              :title="resumeButtonText"
              :loading="loadingBtn"
              size="micro"
              @click="onResumeClick()"
            />
          </v-col>
        </v-row>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import TextComponent from '@/components/common/Text/Text.vue';

@Component({
  name: 'modal-button',
  components: {
    TextComponent,
    ButtonComponent,
  },
})
export default class ModalButton extends Vue {
  @Prop({ type: String, default: '' }) buttonText!: string;
  @Prop({ type: String, default: '' }) modalText!: string;
  @Prop({ type: String, default: 'Отменить' }) cancelButtonText!: string;
  @Prop({ type: String, default: 'Продолжить' }) resumeButtonText!: string;
  @Prop({ type: String, default: '510' }) width!: string;
  @Prop({ type: String, default: 'p' }) variantText!: string;
  @Prop({ type: String, default: '' }) btnClass!: string;
  @Prop({ type: Boolean, default: false }) primary!: boolean;
  @Prop({ type: Boolean, default: false }) loadingBtn!: boolean;
  @Prop(Boolean) disabled!: boolean;

  showModal = false;

  handleClose(): void {
    this.showModal = false;
  }

  onResumeClick(): void {
    this.$emit('onResumeClick', this.handleClose);
  }
}
</script>

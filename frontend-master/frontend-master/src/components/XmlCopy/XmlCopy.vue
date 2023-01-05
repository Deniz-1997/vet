<template>
  <v-expansion-panels v-model="panel" multiple>
    <v-expansion-panel v-if="value">
      <v-expansion-panel-header> {{ title }} </v-expansion-panel-header>
      <v-expansion-panel-content>
        <TextareaComponent :value="value" readonly :rows="5" />
        <DefaultButton
          title="Скопировать"
          type="button"
          @click="(evt) => onCopy(evt.target, value)"
        />
      </v-expansion-panel-content>
    </v-expansion-panel>
  </v-expansion-panels>
</template>

<script lang="ts">
import { Component, Vue, Prop, Model } from 'vue-property-decorator';
import TextareaComponent from '@/components/common/inputs/TextAreaComponent.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';

@Component({
  name: 'XmlCopy',
  components: {
    TextareaComponent,
    DefaultButton,
  },
})
export default class XmlCopy extends Vue {
  @Prop({ type: String, default: '' }) readonly value!: string;
  @Prop({ type: String, default: 'XML' }) readonly title!: string;

  panel = null;

  onCopy(element: HTMLElement, content: string) {
    if ('clipboard' in navigator) {
      if ('write' in navigator.clipboard) {
        const type = 'text/plain';
        const blob = new Blob([content], { type });
        const data = [new ClipboardItem({ [type]: blob } as any)];
        (navigator.clipboard as any).write(data as any);
      } else {
        (navigator.clipboard as any).writeText(content);
      }
    } else if ('execCommand' in document) {
      document.execCommand('copy', false, content);
    }

    const button = this.getButton(element);

    if (button) {
      button.blur();
    }
  }

  getButton(element: HTMLElement): HTMLButtonElement | null {
    if (element.tagName.toLowerCase() === 'button') {
      return element as HTMLButtonElement;
    }

    return element.parentElement && this.getButton(element.parentElement);
  }
}
</script>

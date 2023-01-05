<template>
  <div>
    <v-skeleton-loader
      v-if="loading"
      type="article"
    />
    <text-component v-else-if="errorMessage">
      Ошибка загрузки pdf-файла: {{ errorMessage }}
    </text-component>
    <div
      v-else
      class="iframe"
    >
      <iframe
        :width="width"
        :height="height"
        :src="pdfContent"
        style="border: 0"
      />
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
// import { PdfFile } from '@/types/PdfFile';
import TextComponent from '@/components/common/Text/Text.vue';

@Component({
  name: 'pdf-view',
  components: {
    TextComponent,
  },
})
export default class PdfView extends Vue {
  @Prop(Boolean) loading!: boolean;
  @Prop({ type: String, default: '600px' }) height!: string;
  @Prop({ type: String, default: '380px' }) width!: string;
  @Prop(String) errorMessage!: string;
  @Prop(Object) file!: any;

  get pdfContent(): string {
    return `data:application/pdf;base64,${this.file.content}`;
  }
}
</script>

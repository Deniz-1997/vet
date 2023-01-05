<template>
  <div>
    <v-skeleton-loader v-if="loading" type="article" />
    <text-component v-else-if="errorMessage"> Ошибка загрузки pdf-файла: {{ errorMessage }} </text-component>
    <div v-else-if="pdfContent" class="iframe">
      <!-- <div id="pdfRenderer"></div> -->
      <!-- <object data="https://msh-dev-app.fors.ru/api/elevator-request/pdf/1144" type="application/pdf">
          <embed src="https://msh-dev-app.fors.ru/api/elevator-request/pdf/1144" type="application/pdf" />
      </object> -->
      <iframe :width="width" :height="height" :src="pdfContent" style="border: 0" />
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import TextComponent from '@/components/common/Text/Text.vue';
// import PDFObject from 'pdfobject';

@Component({
  name: 'pdf-view',
  components: {
    TextComponent,
  },
})
export default class PdfViewComponent extends Vue {
  @Prop(Boolean) loading!: boolean;
  @Prop({ type: String, default: '600px' }) height!: string;
  @Prop({ type: String, default: '380px' }) width!: string;
  @Prop(String) errorMessage!: string;
  @Prop(String) fileData!: string;

  pdfContent: any = null;

  // get pdfUrl(): string {
  //   return `data:application/pdf;base64,${this.fileData}`;
  // }

  created() {
    // const {data} = await axios.get(this.pdfUrl);
    this.pdfContent = this.fileData;

    // var pdf = new PDFObject({
    //   url: this.pdfContent,
    //   id: "pdfRendered",
    //   pdfOpenParams: {
    //     view: "FitH"
    //   }
    // }).embed("pdfRenderer");
  }
}
</script>

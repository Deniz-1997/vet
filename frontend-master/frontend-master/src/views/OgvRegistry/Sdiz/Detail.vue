<template>
  <sdiz-detail v-model="model" :is-show="false"/>
</template>

<script lang="ts">
import {Component} from "vue-property-decorator";
import Sdiz from "@/views/Sdiz/Sdiz.vue";
import SdizDetail from "@/views/Sdiz/components/Detail.vue";
import {SdizOgvVueModel} from "@/models/Sdiz/Ogv/SdizOgv.vue";

@Component({
  name: "sdiz-ogv-detail",
  components: {SdizDetail},
})
export default class SdizOgvDetail extends Sdiz {
  model: SdizOgvVueModel = new SdizOgvVueModel();
}
</script>

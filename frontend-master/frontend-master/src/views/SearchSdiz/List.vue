<template>
    <v-container>
      <notifications group="sdizList" position="bottom right"/>
      <sdiz-search-component></sdiz-search-component>
      <router-view></router-view>
    </v-container>
</template>

<script lang="ts">
import {Component, Vue} from 'vue-property-decorator'
import SdizSearchComponent from "@/views/SearchSdiz/component/SdizSearchComponent.vue";

@Component({
  name: 'SdizSearchList',
  components: {SdizSearchComponent, SdizSearchList}
})

export default class SdizSearchList extends Vue{

}
</script>

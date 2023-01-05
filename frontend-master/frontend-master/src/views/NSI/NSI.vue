<template>
  <div class="nsi">
    <NsiMainPage v-if="$route.path === '/nsi'" />
    <router-view></router-view>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import NsiMainPage from '@/views/NSI/components/MainPage/MainPage.vue';

@Component({
  name: 'nsi',
  components: { NsiMainPage },
})
export default class NSI extends Vue {
  @Prop({ type: String, default: 'Справочники' }) public title!: string;
}
</script>

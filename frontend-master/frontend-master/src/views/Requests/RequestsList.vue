<template>
  <div class="requests">
    <v-container>
      <Requests v-if="$route.path === '/requests'" />
      <router-view></router-view>
    </v-container>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import Requests from '@/views/Requests/Requests.vue';

@Component({
  name: 'requests-list',
  components: { Requests },
})
export default class RequestsList extends Vue {
  @Prop({
    type: String,
    default:
      'Реестр организаций, осуществляющих в качестве предпринимательской деятельности хранение зерна и оказывающих связанные с хранением услуги',
  })
  public title!: string;
}
</script>

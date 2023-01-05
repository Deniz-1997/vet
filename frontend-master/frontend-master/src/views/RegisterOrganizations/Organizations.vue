<template>
  <div class="organizations">
    <v-container>
      <RegisterOrganizations v-if="$route.path === '/register-organizations'" />
      <router-view></router-view>
    </v-container>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import RegisterOrganizations from '@/views/RegisterOrganizations/RegisterOrganizations.vue';

@Component({
  name: 'organizations',
  components: { RegisterOrganizations },
})
export default class Organizations extends Vue {
  @Prop({
    type: String,
    default:
      'Реестр организаций, осуществляющих в качестве предпринимательской деятельности хранение зерна и оказывающих связанные с хранением услуги',
  })
  public title!: string;
}
</script>

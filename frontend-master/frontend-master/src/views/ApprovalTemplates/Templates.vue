<template>
  <div class="templates">
    <v-container>
      <ApprovalTemplates v-if="$route.path === '/approval-templates'" />
      <router-view></router-view>
    </v-container>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import ApprovalTemplates from '@/views/ApprovalTemplates/ApprovalTemplates.vue';

@Component({
  name: 'templates',
  components: { ApprovalTemplates },
})
export default class Templates extends Vue {
  @Prop({ type: String, default: 'Шаблоны рассмотрения заявлений' }) public title!: string;
}
</script>

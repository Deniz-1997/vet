<template>
  <div class="approval">
    <v-container>
      <ApplicationsApproval
        v-if="$route.path === '/tasks-for-approval'"
        :search="searchString"
        @search="(v) => (searchString = v)"
      />
      <router-view></router-view>
    </v-container>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import ApplicationsApproval from '@/views/ApplicationsApproval/ApplicationsApproval.vue';

@Component({
  name: 'approval',
  components: { ApplicationsApproval },
})
export default class Approval extends Vue {
  @Prop({ type: String, default: 'Рассмотрение' }) public title!: string;
  searchString = '';
}
</script>

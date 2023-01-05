<template>
  <div class="userRoles">
    <user-roles-list v-if="this.$route.path === '/userRoles'"/>
    <router-view></router-view>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator'
import UserRolesList from "@/views/Administration/UserRoles/components/UserRolesList.vue"

@Component({
  name: 'UserRoles',
  components: { UserRolesList }
})

export default class UserRoles extends Vue {
  @Prop({type: String, default: 'Реестр пользователей'}) public title!: String;

}
</script>
<template>
  <div class="staff">
    <staff-list v-if="this.$route.path === '/staff'"/>
    <router-view></router-view>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator'
import StaffList from "@/views/Administration/Staff/components/StaffList.vue"

@Component({
  name: 'staff',
  components: { StaffList }
})

export default class Staff extends Vue {
  @Prop({type: String, default: 'Реестр пользователей'}) public title!: String;
}
</script>
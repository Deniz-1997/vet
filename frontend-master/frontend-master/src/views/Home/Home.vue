<template>
  <div class="home">
  <v-container>
      <main-page
        v-if="this.$route.path === '/home'"
      />
      <router-view></router-view>
    </v-container>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from "vue-property-decorator";
import MainPage from "@/views/Home/MainPage.vue";

@Component({
  name: "home",
  components: { MainPage },
})
export default class Home extends Vue {
  @Prop({ type: String, default: "АРМ Органов государственной власти" }) public title!: String;
}
</script>
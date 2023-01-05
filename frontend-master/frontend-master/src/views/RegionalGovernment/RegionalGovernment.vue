<template>
  <v-container>
    <router-view></router-view>
    <notifications group="regionalGovernment" position="bottom right" />
  </v-container>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

@Component({
  name: 'RegionalGovernment',
})
export default class RegionalGovernment extends Vue {}
</script>

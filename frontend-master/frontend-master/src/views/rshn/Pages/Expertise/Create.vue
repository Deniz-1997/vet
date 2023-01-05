<template>
  <v-container v-if="accessGrantedAuthorities(model.view_data_privileges)">
    <rshn-expertise-form v-model="model" create></rshn-expertise-form>
  </v-container>
</template>
<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import Rshn from '@/views/rshn/Rshn.vue';
import { RshnExpertiseData } from '@/models/Rshn/Expertise/RshnExpertiseData.vue';
import RshnExpertiseForm from '@/views/rshn/Pages/Expertise/From.vue';
import { PermissionMix } from '@/utils/mixins/permission';

@Component({
  components: {
    RshnExpertiseForm,
  },
})
export default class RshnExpertiseCreate extends Mixins(Rshn, PermissionMix) {
  model: RshnExpertiseData = new RshnExpertiseData();
}
</script>

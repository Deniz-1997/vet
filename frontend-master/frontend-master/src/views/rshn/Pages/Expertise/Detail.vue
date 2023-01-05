<template>
  <rshn-expertise-form
    v-if="model.id && accessGrantedAuthorities(model.view_data_privileges)"
    v-model="model"
    :update-link="model.update_apiendpoint"
    detail
    :edit="edit"
    @edit="setEdit"
  ></rshn-expertise-form>
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
export default class RshnExpertiseDetail extends Mixins(Rshn, PermissionMix) {
  model: RshnExpertiseData = new RshnExpertiseData();

  async created(): Promise<void> {
    this.showItem = this.model.show_apiendpoint;
    await this.findElementById(parseInt(this.$route.params.id));
  }
}
</script>
